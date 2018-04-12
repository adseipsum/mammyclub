<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * First year broadcast controller.
 * @author Itirra - http://itirra.com
 */
class First_Year_Broadcast_Controller extends Base_Project_Controller {

  const PROTECTION_CODE = '30ad3a3a1d2c7c63102e09e6fe4bb253';

  const EMAIL_TEMPLATES_DEFAULT_MODULE = 'first_year_broadcast';

  /**
   * Constructor.
   */
  public function First_Year_Broadcast_Controller() {
    parent::Base_Project_Controller();
    ini_set('MAX_EXECUTION_TIME', -1);
    $this->load->helper('common/itirra_date');
    $this->layout->setModule('email/first_year_broadcast');
  }

  /**
   * Subscribe process
   */
  public function broadcast_subscribe_process() {
    if ($this->isLoggedIn == FALSE) {
      uni_redirect('вход');
    }

    $this->load->helper('common/itirra_validation');
    simple_validate_post(array('child_birth_date', 'child_sex'));

    $updateArr = array();
    $updateArr['id'] = $this->authEntity['id'];
    $updateArr['newsletter_first_year'] = TRUE;
    $updateArr['child_birth_date'] = $_POST['child_birth_date'];
    $updateArr['age_of_child_current_started'] = date('Y-m-d');
    $updateArr['child_sex'] = $_POST['child_sex'];
    $updateArr['age_of_child'] = calculate_age_in_weeks($_POST['child_birth_date']);

    // Unsubscribe from basic pregnancy week broadcast
    $updateArr['newsletter'] = FALSE;
    $updateArr['pregnancyweek_id'] = null;
    $updateArr['pregnancyweek_current_id'] = null;
    $updateArr['pregnancyweek_current_started'] = null;
    ManagerHolder::get('UserPregnancyWeek')->deleteAllWhere(array('user_id' => $this->authEntity['id']));

    ManagerHolder::get('User')->update($updateArr);

    $broadcasts = ManagerHolder::get('FirstYearBroadcast')->getAllWhere(array('age_of_child <=' => $updateArr['age_of_child']), 'e.*, countries.*, article.*, products.*, products_boys.*, products_girls.*');
    log_message('debug', '[broadcast_subscribe_process] - Found broadcasts for send: ' . count($broadcasts));
    if(!empty($broadcasts)) {
      foreach ($broadcasts as $b) {
        ManagerHolder::get('FirstYearBroadcast')->sendSingleLetterOfBroadcast($b, $this->authEntity);
      }
    }
    redirect(FIRST_YEAR_BROADCAST_SUBSCRIBE_SUCCESS_PAGE . '/' . $updateArr['age_of_child']);
  }

  /**
   * Subscribe success page
   * @param int $fullWeeks
   */
  public function subscribe_success_page($fullWeeks) {

    if(!is_numeric($fullWeeks)) {
      show_404();
    }
    $this->layout->set('fullWeeks', $fullWeeks);

    $broadcasts = ManagerHolder::get('FirstYearBroadcast')->getAllWhere(array('age_of_child <=' => $fullWeeks), 'e.*');
    $this->layout->set('broadcasts', $broadcasts);

    $header = array('title' => 'Спасибо за то, что подписались на нашу рассылку!');
    $this->layout->set('header', $header);

    $this->layout->view('subscribe_success');
  }

  /**
   * Resend broadcast
   * @param array $fullWeeks
   */
  public function resend_broadcast($fullWeeks) {

    if(!is_numeric($fullWeeks)) {
      show_404();
    }

    $broadcasts = ManagerHolder::get('FirstYearBroadcast')->getAllWhere(array('age_of_child <=' => $fullWeeks), 'e.*');
    if(empty($broadcasts)) {
      set_flash_notice('Вы не подписаны на рассылку!');
      redirect('личный-кабинет/редактирование-информации');
    }

    foreach ($broadcasts as $b) {
      ManagerHolder::get('FirstYearBroadcast')->sendSingleLetterOfBroadcast($b, $this->authEntity);
    }

    set_flash_notice('Письма высланы повторно');
    redirect_to_referral();
  }

  /**
   * Unsubscribe process.
   */
  public function unsubscribe_process() {

    $data = array();
    $data['id'] = $this->authEntity['id'];
    $data['newsletter_first_year'] = FALSE;
    $data['age_of_child'] = null;
    $data['age_of_child_current_started'] = null;

    ManagerHolder::get('User')->update($data);
    redirect(FIRST_YEAR_BROADCAST_UNSUBSCRIBE_PAGE);
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
    ManagerHolder::get('User')->updateById($this->authEntity['id'], 'newsletter_first_year', TRUE);
    set_flash_notice('Вы снова подписаны на нашу рассылку "Первый год жизни"');
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
      $message .= 'был отписан от рассылки "Первый год жизни" по причине: <br />' . $_POST['reason'];

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

    log_message('info', '[send_first_year_broadcast] - STARTED AT ' . date(DOCTRINE_DATE_FORMAT));

    $this->load->helper('project_broadcast');

    $emailAmount = 0;

    $broadcasts = ManagerHolder::get('FirstYearBroadcast')->getAll('e.*, article.*, countries.*, products.*, products_boys.*, products_girls.*');
    foreach ($broadcasts as $broadcast) {

      log_message('info', '[send_first_year_broadcast] - Started processing broadcast: ' . $broadcast['name']);

      $usersWhere = array('newsletter_first_year' => TRUE,
                          'age_of_child' => $broadcast['age_of_child']);
      if(!empty($broadcast['countries'])) {
        $usersWhere['country'] = get_array_vals_by_second_key($broadcast['countries'], 'code');
      }
      $users = ManagerHolder::get('User')->getAllWhere($usersWhere, 'e.*, auth_info.*');
//       if(empty($users)) {
//         log_message('debug', '[send_first_year_broadcast] - No users found with where: ' . print_r($usersWhere, TRUE));
//         continue;
//       }

      if (!empty($users)) {
        foreach ($users as $k => $user) {

//           log_message('debug', '[send_first_year_broadcast] - Processing user: ' . $user['auth_info']['email']);

          $userMandrillBroadcastRecipient = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere(array('user_id' => $user['id']), 'id, broadcast_id');
          if(empty($userMandrillBroadcastRecipient)) {
            continue;
          }

          $pastBroadcastIds = get_array_vals_by_second_key($userMandrillBroadcastRecipient, 'broadcast_id');
          $pastUserBroadcasts = ManagerHolder::get('MandrillBroadcast')->getAllWhere(array('id' => $pastBroadcastIds, 'type' => 'first_year_broadcast'), 'id, type, subject');
          if (!empty($pastUserBroadcasts)) {
            foreach ($pastUserBroadcasts as $pastUserBroadcast) {
              // TODO This is a fucking hack implemented by Ivan
              if ($pastUserBroadcast['subject'] == $broadcast['subject']) {
  //               log_message('debug', '[send_first_year_broadcast] - unsetting user');
                unset($users[$k]);
                break;
              }
            }
          }
          unset($pastBroadcastIds, $pastUserBroadcasts, $userMandrillBroadcastRecipient);
        }
      }

      log_message('info', '[send_first_year_broadcast] - Recipient amount for ' . $broadcast['name']. ' broadcast: ' . count($users));

      if (!empty($users)) {

        // Create broadcast
        $broadcastData = array('subject' => $broadcast['subject'],
                               'text' => '',
                               'recipients_count' => count($users),
                               'read_count' => 0,
                               'link_visited_count' => 0,
                               'created_at' => date(DOCTRINE_DATE_FORMAT),
                               'type' => 'first_year_broadcast');

        $broadcastId = ManagerHolder::get('MandrillBroadcast')->insert($broadcastData);

        foreach ($users as $user) {

          // Collect data to array
          $viewData = ManagerHolder::get('FirstYearBroadcast')->createFirstYearBroadcastContent($broadcast, $user);

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
            ManagerHolder::get('EmailMandrill')->sendTemplate($userData['email'], 'first_year_broadcast/view', $viewData, $viewData['subject']);
            $emailAmount++;
            log_message('info',  '[send_first_year_broadcast] - Sending email to ' . $user['auth_info']['email'] . ' (recipient_id: ' . $recipientId . ') for broadcast: ' . $broadcast['name']);
          } catch (Exception $e) {
            log_message('error', '[send_first_year_broadcast] - Broadcast send error:' . $e->getMessage() . '; on email: ' . $user['auth_info']['email']);
          }
          unset($viewData, $metaData, $userData);
        }
        ManagerHolder::get('MandrillBroadcast')->updateById($broadcastId, 'sent_date', date(DOCTRINE_DATE_FORMAT));

      }

      log_message('info', '[send_first_year_broadcast] - Finished processing broadcast: ' . $broadcast['name']);
    }

    ManagerHolder::get('EmailNotice')->sendNoticeAboutBroadcastEnd('first_year_broadcast', $emailAmount);

    log_message('info', '[send_first_year_broadcast] - FINISHED AT ' . date(DOCTRINE_DATE_FORMAT));
  }

}