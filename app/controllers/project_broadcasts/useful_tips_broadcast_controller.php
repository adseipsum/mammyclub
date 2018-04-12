<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Useful tips broadcast controller.
 * @author Itirra - http://itirra.com
 */
class Useful_Tips_Broadcast_Controller extends Base_Project_Controller {

  const PROTECTION_CODE = '30ad3a3a1d2c7c63102e09e6fe4bb253';

  const EMAIL_TEMPLATES_DEFAULT_MODULE = 'useful_tips_broadcast';

  /**
   * Constructor.
   */
  public function Useful_Tips_Broadcast_Controller() {
    parent::Base_Project_Controller();
    $this->load->helper('common/itirra_date');
    $this->layout->setModule('email/useful_tips_broadcast');
  }

  /**
   * Unsubscribe process.
   */
  public function unsubscribe_process() {
    ManagerHolder::get('User')->updateById($this->authEntity['id'], 'newsletter_useful_tips', FALSE);
    redirect(USEFUL_TIPS_BROADCAST_UNSUBSCRIBE_PAGE);
  }

  /**
   * Unsubscribe.
   */
  public function unsubscribe() {
    if($this->isLoggedIn == FALSE || empty($this->authEntity)) {
      show_404();
    }

    $this->layout->set('user', $this->authEntity);
    $this->layout->view('unsubscribe');
  }

  /**
   * Resubscribe process.
   */
  public function resubscribe_process() {
    ManagerHolder::get('User')->updateById($this->authEntity['id'], 'newsletter_useful_tips', TRUE);
    set_flash_notice('Вы снова подписаны на нашу рассылку "Полезные советы от Mammyclub"');
    redirect('личный-кабинет/редактирование-информации');
  }

  /**
   * Unsubscribe reason process.
   */
  public function unsubscribe_reason_process() {
    if ($this->isLoggedIn == FALSE || empty($this->authEntity)) {
      show_404();
    }
    if (!isset($_POST['reason']) || empty($_POST['reason'])) {
      set_flash_error('Вы не указали причину отписки.');
      redirect_to_referral();
    }

    if (!empty($this->settings['site_email'])) {
      $message = 'Пользователь <a href="' . admin_site_url('user/add_edit/' . $this->authEntity['id']) . '">' . $this->authEntity['name'] . '</a>';
      $message .= 'был отписан от рассылки "Полезные советы от Mammyclub" по причине: <br />' . $_POST['reason'];

      ManagerHolder::get('Email')->send($this->settings['site_email'], 'Причина отписки', $message);
    }

    set_flash_notice('Спасибо за ваш комментарий.');
    redirect_to_referral();
  }

  /**
   * Send broadcast
   * @param string $protection_code
   */
  public function send_broadcast($protection_code) {

    if ($protection_code != self::PROTECTION_CODE) {
      show_404();
    }

    $this->load->helper('project_broadcast');

    $now = date(DOCTRINE_DATE_FORMAT);
    $emailAmount = 0;

    //------------------------------------------------------------------
    // Get broadcasts that must be sent now
    $broadcasts = ManagerHolder::get('UsefulTipsBroadcast')->getAllWhere(array('is_processing' => FALSE), 'e.*, pregnancy_weeks.*, article.*, countries.*, products.*, products_boys.*, products_girls.*');
    if(empty($broadcasts)) {
//       log_message('debug', '[send_useful_tips_broadcast] - No not sent broadcasts found at ' . $now);
      die();
    }

    // Filter broadcasts by frequency
    foreach ($broadcasts as $k => $b) {

      if($b['update_frequency'] == 0) {
        if($b['is_sent'] == TRUE || $b['sent_datetime'] >= $now) {
//           log_message('debug', '[send_useful_tips_broadcast] - unsetting with update_frequency = 0; Broadcast: ' . $b['id'] . ' - ' . $b['name']);
          unset($broadcasts[$k]);
        }
        continue;
      }

      // 1. Frequency logic here
      if(empty($b['sent_datetime']) || $b['sent_datetime'] > $now) {
        unset($broadcasts[$k]);
        continue;
      }
      // 2. Check if broadcast should be sent today
      $dStart = new DateTime(convert_date($b['sent_datetime'], 'Y-m-d'));
      $dEnd  = new DateTime(date('Y-m-d'));
      $dDiff = $dStart->diff($dEnd);
      if($dDiff->days % $b['update_frequency'] != 0) {
        unset($broadcasts[$k]);
        continue;
      }
      // 3. Check time
      $sentTime = convert_date($b['sent_datetime'], 'H:i');
      $nowTime = convert_date($now, 'H:i');
      if($nowTime < $sentTime) {
        unset($broadcasts[$k]);
        continue;
      }
      // 4. Check if broadcast was sent today
      $mbWhere = array('subject'            => $b['subject'],
                       'type'               => 'useful_tips_broadcast',
                       'created_at BETWEEN' => date('Y-m-d') . ' 00:00:00 AND ' . date('Y-m-d') . ' 23:59:59');
      $mbExists = ManagerHolder::get('MandrillBroadcast')->existsWhere($mbWhere);
      if($mbExists == TRUE) {
        unset($broadcasts[$k]);
        continue;
      }

    }

    if(empty($broadcasts)) {
      die();
    }

    log_message('info', '[send_useful_tips_broadcast] - STARTED AT ' . date(DOCTRINE_DATE_FORMAT));

    $allBroadcastIds = get_array_vals_by_second_key($broadcasts, 'id');
    ManagerHolder::get('UsefulTipsBroadcast')->updateAllWhere(array('id' => $allBroadcastIds), array('is_processing' => TRUE));

    foreach ($broadcasts as $broadcast) {

      log_message('info', '[send_useful_tips_broadcast] - Started processing broadcast: ' . $broadcast['name']);

      $users = ManagerHolder::get('UsefulTipsBroadcastUser')->getAllWhere(array('useful_tips_broadcast_id' => $broadcast['id']), 'e.*');
      if (!empty($users)) {
        $userIds = get_array_vals_by_second_key($users, 'user_id');
        $users = ManagerHolder::get('User')->getAllWhere(array('id' => $userIds), 'e.*, auth_info.*');
      } else {
        $usersWhere = array('newsletter_useful_tips' => TRUE);

        // 1. check for "countries"
        if(!empty($broadcast['countries'])) {
          $usersWhere['country'] = get_array_vals_by_second_key($broadcast['countries'], 'code');
        }
        $users = ManagerHolder::get('User')->getAllWhere($usersWhere, 'e.*, auth_info.*');

        // 2. check for "age_of_child" and "pregnancyweek_current_id"
        if(!empty($users) && (!empty($broadcast['age_of_child']) || !empty($broadcast['pregnancy_weeks'])) ) {
          $aoc = !empty($broadcast['age_of_child'])?explode(',', $broadcast['age_of_child']):null;
          $pwIds = !empty($broadcast['pregnancy_weeks'])?get_array_vals_by_second_key($broadcast['pregnancy_weeks'], 'id'):null;
          foreach ($users as $k => $u) {
            if(!empty($broadcast['age_of_child']) && in_array($u['age_of_child'], $aoc)) {
              continue;
            }
            if(!empty($broadcast['pregnancy_weeks']) && in_array($u['pregnancyweek_current_id'], $pwIds)) {
              continue;
            }
            unset($users[$k]);
          }
        }
      }

      if (!empty($users)) {
        foreach ($users as $k => $user) {

          $user['MandrillBroadcastRecipient'] = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere(array('user_id' => $user['id']), 'e.*');
          if(empty($user['MandrillBroadcastRecipient'])) {
            continue;
          }

          $pastAllTypesBroadcastIds = get_array_vals_by_second_key($user['MandrillBroadcastRecipient'], 'broadcast_id');
          $pastAllTypesUserBroadcasts = ManagerHolder::get('MandrillBroadcast')->getByIdArray($pastAllTypesBroadcastIds, 'e.*');
          foreach ($pastAllTypesUserBroadcasts as $pastUserBroadcast) {

            // Filter broadcasts by type
            if($pastUserBroadcast['type'] != 'useful_tips_broadcast') {
              continue;
            }

            // TODO This is a fucking hack implemented by Ivan
            if ($pastUserBroadcast['subject'] == $broadcast['subject']) {
//               log_message('debug', '[send_useful_tips_broadcast] - unsetting user');
              unset($users[$k]);
              break;
            }

          }
        }
      }

      log_message('info', '[send_useful_tips_broadcast] - Recipient amount for ' . $broadcast['name']. ' broadcast: ' . count($users));

      if (!empty($users)) {

        // Create broadcast
        $broadcastData = array('name' => $broadcast['name'],
                               'subject' => $broadcast['subject'],
                               'text' => '',
                               'recipients_count' => count($users),
                               'read_count' => 0,
                               'link_visited_count' => 0,
                               'created_at' => date(DOCTRINE_DATE_FORMAT),
                               'type' => 'useful_tips_broadcast');

        $broadcastId = ManagerHolder::get('MandrillBroadcast')->insert($broadcastData);

        foreach ($users as $user) {

          // Collect data to array
          $viewData = ManagerHolder::get('UsefulTipsBroadcast')->createBroadcastContent($broadcast, $user);

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
            ManagerHolder::get('EmailMandrill')->sendTemplate($userData['email'], 'useful_tips_broadcast/view', $viewData, $viewData['subject']);
            $emailAmount++;
            log_message('info',  '[send_useful_tips_broadcast] - Sending email to ' . $user['auth_info']['email'] . ' (recipient_id: ' . $recipientId . ') for broadcast: ' . $broadcast['name']);
          } catch (Exception $e) {
            log_message('error', '[send_useful_tips_broadcast] - Broadcast send error:' . $e->getMessage() . '; on email: ' . $user['auth_info']['email']);
          }
          unset($viewData, $metaData, $userData);
        }

        ManagerHolder::get('MandrillBroadcast')->updateById($broadcastId, 'sent_date', date(DOCTRINE_DATE_FORMAT));

      }

      ManagerHolder::get('UsefulTipsBroadcast')->updateById($broadcast['id'], 'is_sent', TRUE);

      log_message('info', '[send_useful_tips_broadcast] - Finished processing broadcast: ' . $broadcast['name']);
    }

    ManagerHolder::get('UsefulTipsBroadcast')->updateAllWhere(array('id' => $allBroadcastIds), array('is_processing' => FALSE));

    ManagerHolder::get('EmailNotice')->sendNoticeAboutBroadcastEnd('useful_tips_broadcast', $emailAmount);

    log_message('info', '[send_useful_tips_broadcast] - FINISHED AT ' . date(DOCTRINE_DATE_FORMAT));
  }

}