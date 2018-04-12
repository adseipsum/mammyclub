<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Returning broadcast controller.
 * @author Itirra - http://itirra.com
 */
class Returning_Broadcast_Controller extends Base_Project_Controller {

  const PROTECTION_CODE = '30ad3a3a1d2c7c63102e09e6fe4bb253';

  const EMAIL_TEMPLATES_DEFAULT_MODULE = 'returning_broadcast';

  /**
   * Constructor.
   */
  public function Returning_Broadcast_Controller() {
    parent::Base_Project_Controller();
    $this->load->helper('common/itirra_date');
    $this->layout->setModule('email/returning_broadcast');
  }

  /**
   * Send broadcast
   * @param string $protection_code
   */
  public function send_broadcast($protection_code) {

    if ($protection_code != self::PROTECTION_CODE) {
      show_404();
    }

    define('RB_DEBUG', 0);

    $this->load->helper('project_broadcast');

    // =======================================================================
    // =========== 0) Validation =============================================
    // =======================================================================

    // Get broadcasts
    $broadcasts = ManagerHolder::get('ReturningBroadcast')->getAll('e.*');
    if(empty($broadcasts) || count($broadcasts) != 2) {
//       log_message('debug', '[send_returning_broadcast] - No ReturningBroadcast found in DB - exiting.');
      die();
    }
    // Validate broadcasts
    foreach ($broadcasts as $b) {
      if(!in_array($b['id'], array(RETURNING_BROADCAST_FIRST_ID, RETURNING_BROADCAST_SECOND_ID))) {
//         log_message('debug', '[send_returning_broadcast] - not valid ReturningBroadcast id');
        die();
      }
    }
    // Check if broadcast need to be send right now
    $this->checkSentDate($broadcasts[0]['sent_datetime']);

    log_message('info', '[send_returning_broadcast] - STARTED AT ' . date(DOCTRINE_DATE_FORMAT));

//     // =======================================================================
//     // =========== 1) Process second letter recipients on last week ==========
//     // ========= (if no link visited - update with newsletter = 0 ) ==========
//     $this->processLastWeekSecondLetterRecipients();

    // =======================================================================
    // =========== 2) Process first letter broadcast =========================
    // =======================================================================

    // 2.1. Get all users which are subscribed on pregnancy_week_broadcast and pregnancyweek_current_id <= 35
    $weeks = ManagerHolder::get('PregnancyWeek')->getAllWhere(array('number <=' => 35), 'id, number');
    $weekIds = get_array_vals_by_second_key($weeks, 'id');
    $users = ManagerHolder::get('User')->getAllWhere(array('newsletter' => TRUE, 'pregnancyweek_current_id' => $weekIds), 'e.*, auth_info.*');
    $userIds = get_array_vals_by_second_key($users, 'id');

    // 2.2. Get all MandrillBroadcastRecipient with broadcast type = pregnancy_week_broadcast and user_id = $userIds
    $recipients = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere(array('user_id' => $userIds, 'broadcast.type' => 'pregnancy_week_broadcast'), 'e.*');
    $recipientsUserIds =  get_array_vals_by_second_key($recipients, 'user_id');

//     // 2.3. Get last week first letter recipients
//     $weekAgoDateStart = date("Y-m-d", strtotime("-1 week")) . ' 00:00:00';
//     $weekAgoDateEnd = date("Y-m-d", strtotime("-1 week")) . ' 23:59:59';
//     $lastWeekFirstLetterRecipients = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere(array('updated_at BETWEEN' => $weekAgoDateStart . 'AND' . $weekAgoDateEnd, 'broadcast.type' => RETURNING_BROADCAST_FIRST), 'e.*, MandrillBroadcastVisitedLink.*');
//     $lastWeekFirstLetterRecipientsUserIds = get_array_vals_by_second_key($lastWeekFirstLetterRecipients, 'user_id');
    // 2.3. Get all first letter recipients
    $firstLetterRecipients = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere(array('broadcast.type' => RETURNING_BROADCAST_FIRST), 'e.*');
    $firstLetterRecipientsUserIds = get_array_vals_by_second_key($firstLetterRecipients, 'user_id');

    // 2.4. Loop through users and group by $recipients and order them by date
    $usersToProcess = array();
    foreach ($users as $k => $u) {

//       if(count($usersToProcess) == 20) {
//         break;
//       }

      // Check for last login in last 3 weeks
      if($u['auth_info']['last_login'] > date(DOCTRINE_DATE_FORMAT, strtotime("-3 weeks"))) {
        unset($users[$k]);
        continue;
      }

//       // Check if current user was a recipient of first letter broadcast last week
//       if(!empty($lastWeekFirstLetterRecipientsUserIds) && in_array($u['id'], $lastWeekFirstLetterRecipientsUserIds)) {
//         unset($users[$k]);
//         continue;
//       }
      // Check if current user was a recipient of first letter broadcast
      if(!empty($firstLetterRecipientsUserIds) && in_array($u['id'], $firstLetterRecipientsUserIds)) {
        unset($users[$k]);
        continue;
      }

      $userRecipientKeys = array_keys($recipientsUserIds, $u['id']);

      $u['MandrillBroadcastRecipient'] = array();
      if(!empty($userRecipientKeys)) {
        foreach ($userRecipientKeys as $rKey) {
          $u['MandrillBroadcastRecipient'][] = $recipients[$rKey];
          unset($recipients[$rKey]);
          unset($recipientsUserIds[$rKey]);
        }
      }

      if(count($u['MandrillBroadcastRecipient']) < 3) {
        unset($users[$k]);
        continue;
      }

      // NOTE: MandrillBroadcastRecipient is already ordered by created_at DESC
      $i = 1;
      foreach ($u['MandrillBroadcastRecipient'] as $mbr) {
        if($i > 3) {
          break;
        }
        if($mbr['is_read'] == TRUE) {
          unset($users[$k]);
          continue 2;
        }
        $i++;
      }

      $usersToProcess[] = $u;

    }

    $users = $usersToProcess;

    if(RB_DEBUG == TRUE) {
      trace('2) Process first letter broadcast -> $users filtered: ');
      trace($users);
      trace('3) Process first letter broadcast -> $lastWeekFirstLetterRecipients: ');
      trace($lastWeekFirstLetterRecipients);
      die('RB_DEBUG');
    }

    if(!empty($users)) {
      $this->sendBroadcast($users, $broadcasts[0], RETURNING_BROADCAST_FIRST);
    }

//     // =======================================================================
//     // =========== 3) Process second letter broadcast ========================
//     // =======================================================================
//     if(!empty($lastWeekFirstLetterRecipients)) {

//       // Filter $lastWeekFirstLetterRecipients by visited links and by 3 not opened pregnancy week broadcasts
//       $users = $this->processVisitedLinksAndBroadcastsOpen($lastWeekFirstLetterRecipients);

//       log_message('error', '[send_returning_broadcast] - Found users for second letter broadcast: ' . count($users));
//       if(!empty($users)) {
//         $this->sendBroadcast($users, $broadcasts[1], RETURNING_BROADCAST_SECOND);
//       }
//     }

    log_message('info', '[send_returning_broadcast] - FINISHED AT ' . date(DOCTRINE_DATE_FORMAT));
  }

  /**
   * success_page
   */
  public function success_page() {
    $this->layout->set('header', array('title' => 'С возвращением!'));
    $this->layout->setModule('auth');
    $this->layout->view('returning_success_page');
  }

  /**
   * sendBroadcast
   * @param array $users
   * @param array $broadcast
   * @param string $type
   */
  private function sendBroadcast($users, $broadcast, $type) {

    log_message('info', '[send_returning_broadcast] - Started processing broadcast: ' . $broadcast['name']);

    // Create broadcast
    $broadcastData = array('subject' => $broadcast['subject'],
                           'text' => '',
                           'recipients_count' => count($users),
                           'read_count' => 0,
                           'link_visited_count' => 0,
                           'created_at' => date(DOCTRINE_DATE_FORMAT),
                           'type' => $type);
    $broadcastId = ManagerHolder::get('MandrillBroadcast')->insert($broadcastData);

    log_message('info', '[send_returning_broadcast] - Recipient amount for ' . $broadcast['name'] . ' broadcast: ' . count($users));

    $emailAmount = 0;
    foreach ($users as $user) {

      // Collect data to array
      $viewData = ManagerHolder::get('ReturningBroadcast')->createBroadcastContent($broadcast, $user);

      // Save Broadcast Links
      foreach ($viewData as $data) {
        if (!empty($data)) {
          ManagerHolder::get('MandrillBroadcast')->saveBroadcastLinks($data, $broadcastId);
        }
      }

      // Insert recipient data
      $userData = array('email' => $user['auth_info']['email'],
                        'user_id' => $user['id'],
                        'is_read' => 0,
                        'is_send' => 0,
                        'data' => serialize($user),
                        'broadcast_id' => $broadcastId,
                        'updated_at' => date(DOCTRINE_DATE_FORMAT));
      $recipientId = ManagerHolder::get('MandrillBroadcastRecipient')->insert($userData);

      try {

        $metaData = array('broadcast_id' => $broadcastId,
                          'recipient_id' => $recipientId);
        ManagerHolder::get('EmailMandrill')->setMetadata($metaData);
        ManagerHolder::get('EmailMandrill')->sendTemplate($userData['email'], 'returning_broadcast/view', $viewData, $viewData['subject']);
        $emailAmount++;
        log_message('info',  '[send_returning_broadcast] - Sending email to ' . $user['auth_info']['email'] . ' (recipient_id: ' . $recipientId . ') for ' . $broadcast['name'] . ' broadcast');
      } catch (Exception $e) {
        log_message('error', '[send_returning_broadcast] - Broadcast send error:' . $e->getMessage() . '; on email: ' . $user['auth_info']['email']);
      }
      unset($viewData, $metaData, $userData);
    }

    ManagerHolder::get('MandrillBroadcast')->updateById($broadcastId, 'sent_date', date(DOCTRINE_DATE_FORMAT));

    ManagerHolder::get('EmailNotice')->sendNoticeAboutBroadcastEnd($type, $emailAmount);

    log_message('info', '[send_returning_broadcast] - Finished processing broadcast: ' . $broadcast['name']);
  }


  /**
   * Process VisitedLinks And BroadcastsOpen
   * @param array $lastWeekFirstLetterRecipients
   * @return array $users
   */
  private function processVisitedLinksAndBroadcastsOpen($lastWeekFirstLetterRecipients) {

    // Filter by $lastWeekFirstLetterRecipients visited links
    foreach ($lastWeekFirstLetterRecipients as $k => $r) {
      if(!empty($r['MandrillBroadcastVisitedLink'])) {
        unset($lastWeekFirstLetterRecipients[$k]);
      }
    }
    $userIds = get_array_vals_by_second_key($lastWeekFirstLetterRecipients, 'user_id');
    $users = ManagerHolder::get('User')->getAllWhere(array('id' => $userIds), 'e.*, auth_info.*');

    // Filter by 3 not opened pregnancy week broadcasts
    $PWRecipients = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere(array('user_id' => $userIds, 'broadcast.type' => 'pregnancy_week_broadcast'), 'e.*');
    $PWRecipientsUserIds = get_array_vals_by_second_key($PWRecipients, 'user_id');

    foreach ($users as $k => $u) {
      $userRecipientKeys = array_keys($PWRecipientsUserIds, $u['id']);
      if(count($userRecipientKeys) >= 3) {
        $isRead = FALSE;
        $i = 1;
        foreach ($userRecipientKeys as $rKey) {
          if($i <= 3 && $PWRecipients[$rKey]['is_read'] == TRUE) {
            $isRead = TRUE;
          }
          unset($PWRecipients[$rKey]);
          unset($PWRecipientsUserIds[$rKey]);
          $i++;
        }
        if($isRead == TRUE) {
          unset($users[$k]);
        }
      }
    }

    return $users;
  }

  /**
   * processLastWeekSecondLetterRecipients
   */
  private function processLastWeekSecondLetterRecipients() {

    $weekAgoDateStart = date("Y-m-d", strtotime("-1 week")) . ' 00:00:00';
    $weekAgoDateEnd   = date("Y-m-d", strtotime("-1 week")) . ' 23:59:59';
    $recipients = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere(array('updated_at BETWEEN' => $weekAgoDateStart . 'AND' . $weekAgoDateEnd, 'broadcast.type' => RETURNING_BROADCAST_SECOND), 'e.*, MandrillBroadcastVisitedLink.*');
    if(RB_DEBUG == TRUE) {
      trace('1) processLastWeekSecondLetterRecipients: ');
      trace($recipients);
      return FALSE;
    }

    log_message('debug', '[send_returning_broadcast -> processLastWeekSecondLetterRecipients] - Found LastWeekSecondLetterRecipients: ' . count($recipients));

    if(!empty($recipients)) {
      $updated = 0;
      foreach ($recipients as $r) {
        if(empty($r['MandrillBroadcastVisitedLink'])) {
          $userUpdateData = array('id'                            => $r['user_id'],
                                  'newsletter'                    => FALSE,
                                  'pregnancyweek_current_id'      => NULL,
                                  'pregnancyweek_current_started' => NULL);
          ManagerHolder::get('User')->update($userUpdateData);
          $updated++;
        }
      }
      log_message('debug', '[send_returning_broadcast -> processLastWeekSecondLetterRecipients] - updated users: ' . $updated);
    }

  }

  /**
   * checkSentDate
   * @param unknown $broadcastSentDatetime
   */
  private function checkSentDate($broadcastSentDatetime) {

    if($broadcastSentDatetime > date(DOCTRINE_DATE_FORMAT)) {
      $msg = '[send_returning_broadcast -> checkSentDate] - broadcast should be sent later - exiting.';
//       log_message('error', $msg);
      die($msg);
    }

    $currentDT = DateTime::CreateFromFormat("Y-m-d H:i:s", date(DOCTRINE_DATE_FORMAT));
    $sentDT    = DateTime::CreateFromFormat("Y-m-d H:i:s", $broadcastSentDatetime);

    $interval = $sentDT->diff($currentDT);
    $diffDays = $interval->format('%a');

    $result = FALSE;
    if($diffDays % 7 == 0) {
      $curHours  = $currentDT->format('H');
      $sentHours = $sentDT->format('H');
      if($curHours == $sentHours) {
        $result = TRUE;
      }
    }

    if($result == FALSE) {
      $msg = '[send_returning_broadcast -> checkSentDate] - days/time diff not matching the criteria - exiting';
//       log_message('error', $msg);
      die($msg);
    }
  }

}