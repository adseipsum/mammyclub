<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Recommended products broadcast controller.
 * @author Itirra - http://itirra.com
 */
class Recommended_Products_Broadcast_Controller extends Base_Project_Controller {

  const PROTECTION_CODE = '30ad3a3a1d2c7c63102e09e6fe4bb253';

  const VIEWS_DEFALUT_MODULE = 'email/recommended_products_broadcast';

  const EMAIL_TEMPLATES_DEFAULT_MODULE = 'recommended_products_broadcast';

  const DB_BROADCAST_RECORD = 'newsletter_recommended_products';

  /**
   * Constructor.
   */
  public function Recommended_Products_Broadcast_Controller() {
    parent::Base_Project_Controller();
    $this->load->helper('common/itirra_date');
    $this->layout->setModule(self::VIEWS_DEFALUT_MODULE);
  }

  /**
   * Subscribe process
   */
  public function broadcast_subscribe_process() {
    if ($this->isLoggedIn == FALSE) {
      uni_redirect('вход');
    }

    ManagerHolder::get('User')->updateById($this->authEntity['id'], self::DB_BROADCAST_RECORD, TRUE);

    if ($this->authEntity['pregnancyweek_current_id'] != null) {
      // Get broadcast_id by user's pregnancy week
      $where = array('pregnancy_week_id' => $this->authEntity['pregnancyweek_current_id']);
      $broadcast = ManagerHolder::get('RecommendedProductsBroadcastPregnancyWeek')->getOneWhere($where, 'e.*, recommended_products_broadcast.*');

      if ($broadcast == FALSE) {
        set_flash_notice('Спасибо за вашу подписку!');
        redirect('личный-кабинет/редактирование-информации');
      } else {
        ManagerHolder::get('RecommendedProductsBroadcast')->sendSingleLetterOfBroadcast($this->authEntity);
      }
    } else {
      set_flash_error('Вы не указали свою неделю беременности');
      redirect('личный-кабинет/редактирование-информации');
    }

    redirect(RECOMMENDED_PRODUCTS_BROADCAST_SUBSCRIBE_PAGE);
  }

  /**
   * Broadcast subscribe page
   */
  public function broadcast_subscribe_page() {
    // Get broadcast by user's pregnancy week
    $where = array('pregnancy_week_id' => $this->authEntity['pregnancyweek_current_id']);
    $broadcast = ManagerHolder::get('RecommendedProductsBroadcastPregnancyWeek')->getOneWhere($where, 'e.*, recommended_products_broadcast.*');

    $broadcastSubject = $broadcast['recommended_products_broadcast']['subject'];
    unset($broadcast, $where);

    if (!empty($broadcastSubject)) {
      $this->layout->setLayout('main');
      $this->layout->set('broadcastSubject', $broadcastSubject);
      $this->layout->view('subscribe_success');
    } else {
      show_404();
    }
  }

  /**
   * Resend single letter from broadcast
   */
  public function resend_single_letter_from_broadcast() {
    // Get broadcast_id by user's pregnancy week
    $where = array('pregnancy_week_id' => $this->authEntity['pregnancyweek_current_id']);
    $broadcast = ManagerHolder::get('RecommendedProductsBroadcastPregnancyWeek')->getOneWhere($where, 'e.*, recommended_products_broadcast.*');

    if ($broadcast == FALSE) {
      set_flash_notice('Вы не подписаны на рассылку!');
      redirect('личный-кабинет/редактирование-информации');
    } else {
      ManagerHolder::get('RecommendedProductsBroadcast')->sendSingleLetterOfBroadcast($this->authEntity, $broadcast);
      set_flash_notice('Письмо выслано повторно');
    }
    redirect_to_referral();
  }

  /**
   * Unsubscribe process.
   */
  public function unsubscribe_process() {
    ManagerHolder::get('User')->updateById($this->authEntity['id'], self::DB_BROADCAST_RECORD, FALSE);
    redirect(RECOMMENDED_PRODUCTS_BROADCAST_UNSUBSCRIBE_PAGE);
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
    ManagerHolder::get('User')->updateById($this->authEntity['id'], self::DB_BROADCAST_RECORD, TRUE);
    set_flash_notice('Вы снова подписаны на нашу рассылку "Полезные покупки для беременных"');
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
      $message .= 'был отписан от рассылки по причине: <br />' . $_POST['reason'];

      ManagerHolder::get('Email')->send($this->settings['site_email'], 'Причина отписки', $message);
    }

    set_flash_notice('Спасибо за ваш комментарий.');
    redirect_to_referral();
  }

  /**
   * Send invite to subscribe broadcast
   * Condition:
   * 1. pregnancy week >= 13
   * 2. previous opens of pregnancy week broadcast >= 3
   * 3. user doesnt subscribe to helpful product broadcast
   * @param unknown $protection_code
   */
  public function send_invite_broadcast($protection_code) {
    if ($protection_code != self::PROTECTION_CODE) {
      show_404();
    }

    log_message('info', '[send_invite_broadcast] - STARTED AT ' . date(DOCTRINE_DATE_FORMAT));

    $emailAmount = 0;

    $q = "SELECT id FROM pregnancy_week WHERE number >= 13 AND number <= 31";
    $pregnancyWeeksIds = ManagerHolder::get('PregnancyWeek')->executeNativeSQL($q);
    $pregnancyWeeksIds = get_array_vals_by_second_key($pregnancyWeeksIds, 'id');

    $usersWhere = array('pregnancyweek_current_id' => $pregnancyWeeksIds,
                        'newsletter_recommended_products' => FALSE);
    $usersWhat = 'e.*, auth_info.*'; // , MandrillBroadcastRecipient.*, MandrillBroadcastOpen.* was joined by vanya
    $users = ManagerHolder::get('User')->getAllWhere($usersWhere, $usersWhat);

    $userIds = get_array_vals_by_second_key($users, 'id');
    $mbrTotal = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere(array('user_id' => $userIds), 'broadcast_id');
    $mbrUsers = get_array_vals_by_second_key($mbrTotal, 'user_id');

    //------------------------------------------------------------------
    // Aggregate list of recipients
    foreach ($users as $uKey => $user) {

      $user['MandrillBroadcastRecipient'] = array();
      $mbrKeys = array_keys ($mbrUsers, $user['id']);
      if(!empty($mbrKeys)) {
        foreach ($mbrKeys as $mbrKey) {
          $user['MandrillBroadcastRecipient'][] = $mbrTotal[$mbrKey];
          unset($mbrTotal[$mbrKey]);
        }
      }

      if (count($user['MandrillBroadcastRecipient']) < 3) {
        unset($users[$uKey]);
        continue; // next user
      }

      // Get all past broadcasts of current user
      $broadcastIds = get_array_vals_by_second_key($user['MandrillBroadcastRecipient'], 'broadcast_id');
      $usersBroadcasts = ManagerHolder::get('MandrillBroadcast')->getAllWhere(array('id' => $broadcastIds), 'e.*');

      // Check if user receive "pregnancy_week_broadcast" today
      $receivedPregnancyBroadcstToday = $this->received_pregnancy_broadcast_today($user, $usersBroadcasts);
      if($receivedPregnancyBroadcstToday == FALSE) {
        unset($users[$uKey]);
        continue; // next user
      }

      // Check case when user has 3 opens of letter of pregnancy week broadcast
      $threeOpensExists = $this->check_for_three_pregnancy_week_broadcast_opens($user, $usersBroadcasts);
      if (!$threeOpensExists) {
        unset($users[$uKey]);
        continue; // next user
      }

      // Check if user opened at least 1 invite letter in the past
      $pastInviteOpen = $this->check_for_past_invite_open($user, $usersBroadcasts);
      if ($pastInviteOpen == TRUE) {
        unset($users[$uKey]);
        continue; // next user
      }

    }

    log_message('info', '[send_invite_broadcast] - Recipient amount for invite broadcast: ' . count($users));

    //------------------------------------------------------------------
    // Send broadcast
    if (!empty($users)) {
      // Subject - $settings['invite_helpful_product_broadcast_subject']
      // Content - $settings['invite_helpful_product_broadcast_content']
      $subject = $this->settings['invite_helpful_product_broadcast_subject'];
      $text = $this->settings['invite_helpful_product_broadcast_content'];

      if (strpos($text, '{SUBSCRIBE_BUTTON}') !== FALSE) {
        $this->layout->setLayout('empty');
        $buttonHtml = $this->layout->view('subscribe_button', TRUE);
        $text = str_replace('{SUBSCRIBE_BUTTON}', $buttonHtml, $text);
      }

      // Create broadcast
      $broadcastData = array('subject' => $subject,
                             'text' => $text,
                             'recipients_count' => count($users),
                             'read_count' => 0,
                             'link_visited_count' => 0,
                             'created_at' => date(DOCTRINE_DATE_FORMAT),
                             'type' => 'invite_to_recommended_products_broadcast');

      // Insert broadcast data
      $broadcastId = ManagerHolder::get('MandrillBroadcast')->insert($broadcastData);

      $standartViewData = array('subject' => $subject,
                                'text' => $text);
      ManagerHolder::get('MandrillBroadcast')->saveBroadcastLinks($standartViewData['text'], $broadcastId);

      $this->load->helper('project_broadcast');

      foreach ($users as $user) {
        $viewData = $standartViewData;
        kprintfLettersContent($user, $viewData);

        // Insert recipient data
        $userData = array('email' => $user['auth_info']['email'],
                          'user_id' => $user['id'],
                          'is_read' => 0,
                          'is_send' => 0,
                          'data' => serialize($user),
                          'broadcast_id' => $broadcastId,
                          'updated_at' => date(DOCTRINE_DATE_FORMAT));
        $recipientId = ManagerHolder::get('MandrillBroadcastRecipient')->insert($userData);

        ManagerHolder::get('MandrillBroadcast')->addLoginKeyToLink($viewData['text'], $user['login_key']);

        try {
          $metaData = array('broadcast_id' => $broadcastId,
                            'recipient_id' => $recipientId);
          ManagerHolder::get('EmailMandrill')->setMetadata($metaData);
          ManagerHolder::get('EmailMandrill')->sendTemplate($userData['email'], self::EMAIL_TEMPLATES_DEFAULT_MODULE . '/invite', $viewData, $broadcastData['subject']);
          $emailAmount++;
          log_message('info',  '[send_invite_broadcast] - Sending email to ' . $user['auth_info']['email'] . ' (recipient_id: ' . $recipientId . ') for invite broadcast');
        } catch (Exception $e) {
          log_message('error', '[send_invite_broadcast] - Broadcast send error:' . $e->getMessage() . '; on email: ' . $user['auth_info']['email']);
        }
      }
      ManagerHolder::get('MandrillBroadcast')->updateById($broadcastId, 'sent_date', date(DOCTRINE_DATE_FORMAT));
    }

    log_message('info', '[send_invite_broadcast] - FINISHED AT ' . date(DOCTRINE_DATE_FORMAT));
  }

  /**
   * Send broadcast
   * @param string $protection_code
   */
  public function send_broadcast($protection_code) {
    if ($protection_code != self::PROTECTION_CODE) {
      show_404();
    }

    log_message('info', '[send_rec_prod_broadcast] - STARTED AT ' . date(DOCTRINE_DATE_FORMAT));

    $emailAmount = 0;

    $broadcasts = ManagerHolder::get('RecommendedProductsBroadcast')->getAll('e.*, RecommendedProductsBroadcastPregnancyWeek.*, countries.*, products.*');

    foreach ($broadcasts as $broadcast) {

      log_message('info', '[send_rec_prod_broadcast] - Started processing broadcast: ' . $broadcast['name']);

      $pregnancyWeekIds = get_array_vals_by_second_key($broadcast['RecommendedProductsBroadcastPregnancyWeek'], 'pregnancy_week_id');
      $usersWhere = array('newsletter_recommended_products' => TRUE,
                          'pregnancyweek_current_id' => $pregnancyWeekIds);
      if(!empty($broadcast['countries'])) {
        $usersWhere['country'] = get_array_vals_by_second_key($broadcast['countries'], 'code');
      }
      $users = ManagerHolder::get('User')->getAllWhere($usersWhere, 'e.*, auth_info.*, pregnancyweek_current.*');
//       if(empty($users)) {
//         log_message('debug', '[send_rec_prod_broadcast] - No users found with where: ' . print_r($usersWhere, TRUE));
//         continue;
//       }

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
            if($pastUserBroadcast['type'] != 'recommended_products_broadcast') {
              continue;
            }

            // TODO This is a fucking hack implemented by Ivan
            if ($pastUserBroadcast['subject'] == $broadcast['subject']) {
              unset($users[$k]);
              break;
            }

          }
        }
      }

      log_message('info', '[send_rec_prod_broadcast] - Recipient amount for ' . $broadcast['name']. ' broadcast: ' . count($users));

      if (!empty($users)) {

        // Create broadcast
        $broadcastData = array('subject' => $broadcast['subject'],
                               'text' => '',
                               'recipients_count' => count($users),
                               'read_count' => 0,
                               'link_visited_count' => 0,
                               'created_at' => date(DOCTRINE_DATE_FORMAT),
                               'type' => 'recommended_products_broadcast');

        // Insert broadcast data
        $broadcastId = ManagerHolder::get('MandrillBroadcast')->insert($broadcastData);

        $standartViewData = array('subject' => $broadcast['subject'],
                                  'email_appeal' => $broadcast['email_appeal'],
                                  'email_intro' => $broadcast['email_intro'],
                                  'email_main_text' => $broadcast['email_main_text'],
                                  'email_outro' => $broadcast['email_outro']);

        $this->load->helper('project_broadcast');

        foreach ($users as $user) {
          $viewData = $standartViewData;
          if($user['country'] != 'UA') {
            $viewData['email_main_text'] = prepare_viewdata_not_ua($broadcast);
          }

          // Process products
          $products = array();
          if(isset($broadcast['products']) && !empty($broadcast['products'])) {
            foreach ($broadcast['products'] as $product) {
              $url = shop_url($product['page_url']);
              if (substr($url, -1) == '/') {
                $url = substr($url, 0 , -1);
              }
              $product['url_with_login_key'] = $url . '?' . LOGIN_KEY . '=' . $user['login_key'];
              $product['url_for_buy_button'] = $url . '?' . LOGIN_KEY . '=' . $user['login_key'] . '&' . REDIRECT_TO_CART_KEY . '=1';
              $products[] = $product;
            }
          }

          foreach ($viewData as &$data) {
            if (!empty($data)) {
              if(!empty($products)) {
                $data = ManagerHolder::get('ProductBroadcast')->process_product_broadcast_showcase($data, $products, $user);
              }
              $googleAnalData = array('utm_source'   => $user['pregnancyweek_current']['number'] . '_week_preg',
                                      'utm_medium'   => 'email',
                                      'utm_campaign' => 'UPP');
              ManagerHolder::get('MandrillBroadcast')->addLoginKeyToLink($data, $user['login_key'], $googleAnalData);
              kprintfLettersContent($user, $data);
              if (strpos($data, '{UNSUBSCRIBE_LINK}') !== FALSE) {
                $unsubscribeLink = site_url(RECOMMENDED_PRODUCTS_BROADCAST_UNSUBSCRIBE_PROCESS . '?' . LOGIN_KEY . '=' . $user['login_key']);
                $data = str_replace('{UNSUBSCRIBE_LINK}', $unsubscribeLink, $data);
                kprintfLettersContent($user, $viewData);
              }

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
            ManagerHolder::get('EmailMandrill')->sendTemplate($userData['email'], self::EMAIL_TEMPLATES_DEFAULT_MODULE . '/view', $viewData, $broadcastData['subject']);
            $emailAmount++;
            log_message('info',  '[send_rec_prod_broadcast] - Sending email to ' . $user['auth_info']['email'] . ' (recipient_id: ' . $recipientId . ') for broadcast: ' . $broadcast['name']);
          } catch (Exception $e) {
            log_message('error', '[send_rec_prod_broadcast] - Broadcast send error:' . $e->getMessage() . '; on email: ' . $user['auth_info']['email']);
          }
          unset($viewData, $metaData, $userData);
        }
        ManagerHolder::get('MandrillBroadcast')->updateById($broadcastId, 'sent_date', date(DOCTRINE_DATE_FORMAT));

      }

      log_message('info', '[send_rec_prod_broadcast] - Finished processing broadcast: ' . $broadcast['name']);
    }

    ManagerHolder::get('EmailNotice')->sendNoticeAboutBroadcastEnd('recommended_products_broadcast', $emailAmount);

    log_message('info', '[send_rec_prod_broadcast] - FINISHED AT ' . date(DOCTRINE_DATE_FORMAT));
  }


  /**
   * received_pregnancy_broadcast_today
   * @param array $user
   * @param array $broadcasts
   * @return boolean
   */
  private function received_pregnancy_broadcast_today($user, $broadcasts) {
    // Check broadcasts for pregnancy week today
    foreach ($broadcasts as $broadcast) {
      $broadcastDate = convert_date($broadcast['created_at'], 'Y-m-d');
      if ($broadcast['type'] == 'pregnancy_week_broadcast' && $broadcastDate == date('Y-m-d')) {
        return TRUE;
      }
    }
    return FALSE;
  }


  /**
   * check_for_three_pregnancy_week_broadcast_opens
   * @param array $user
   * @param array $broadcasts
   * @return boolean
   */
  private function check_for_three_pregnancy_week_broadcast_opens($user, $broadcasts) {
    // Check broadcasts for three pregnancy week
    // unset if user received less than three pregnancy week letters
    $pregnancyWeeksBroadcastCounter = 0;
    $pregnancyWeekBroadcasts = array();
    foreach ($broadcasts as $bKey => $broadcast) {
      if ($broadcast['type'] == 'pregnancy_week_broadcast') {
        $pregnancyWeeksBroadcastCounter++;
        $pregnancyWeekBroadcasts[] = $broadcast;
      }
    }

    if ($pregnancyWeeksBroadcastCounter < 3) {
      return FALSE; // User recieved less than 3 pregnancy week broadcasts.
    }

    // Check for three opened letters from pregnancy week broadcast
    // Unset user from list if she/he did not open three past letters
    $pregnancyWeeksBroadcastOpensCounter = 0;
    foreach ($pregnancyWeekBroadcasts as $broadcast) {
      foreach ($user['MandrillBroadcastRecipient'] as $recipient) {
        if ($broadcast['id'] == $recipient['broadcast_id']) {
          $openExistsWhere = array('recipient_id' => $recipient['id'],
                                   'broadcast_id' => $broadcast['id']);
          $openExists = ManagerHolder::get('MandrillBroadcastOpen')->existsWhere($openExistsWhere);
          if ($openExists) {
            $pregnancyWeeksBroadcastOpensCounter++;
          }
        }
      }
    }

    if ($pregnancyWeeksBroadcastOpensCounter < 3) {
      return FALSE;
    } else {
      return TRUE;
    }
  }


  /**
   * check_for_past_invite_open
   * @param array $user
   * @param array $broadcasts
   * @return boolean
   */
  private function check_for_past_invite_open($user, $broadcasts) {

    $inviteBroadcasts = array();

    // Search for last recieved broadcast
    foreach ($broadcasts as $broadcast) {
      if ($broadcast['type'] == "invite_to_recommended_products_broadcast") {
        $inviteBroadcasts[] = $broadcast;
      }
    }

    if(!empty($inviteBroadcasts)) {
      // Search for letter opens
      // Unset user if open exists
      foreach ($inviteBroadcasts as $broadcast) {
        foreach ($user['MandrillBroadcastRecipient'] as $recipient) {
          if ($broadcast['id'] == $recipient['broadcast_id']) {
            $openExistsWhere = array('recipient_id' => $recipient['id'],
                                     'broadcast_id' => $broadcast['id']);
            $openExists = ManagerHolder::get('MandrillBroadcastOpen')->existsWhere($openExistsWhere);
            if ($openExists == TRUE) {
              return TRUE;
            }
          }
        }
      }
    }

    return FALSE;
  }

  /**
   * check_for_past_invites
   * @param array $user
   * @param array $broadcasts
   * @return boolean
   */
  private function check_for_past_invites(&$user, &$broadcasts) {
    // Check case when user already recieved invites in the past
    // Unset cases:
    // 1. user recieved 2 invites (AND DID NOT OPEN THEM) but there is not 3 weeks after last
    // 2. user recieved 3 invites and did not open them.
    $today = date('Y-m-d');
    $threeWeeksInDays = 60 * 60 * 24 * 7 * 3;
    $invitesCounter = 0;
    $lastInviteDate = date(DOCTRINE_DATE_FORMAT, 1);
    $inviteBroadcasts = array();

    // Search for last recieved broadcast
    foreach ($broadcasts as $broadcast) {
      if ($broadcast['type'] == "invite_to_recommended_products_broadcast") {
        // Collect all invite broadcast to array
        $inviteBroadcasts[] = $broadcast;

        $invitesCounter++;
        if (strtotime($lastInviteDate) < strtotime($broadcast['created_at'])) {
          $lastInviteDate = $broadcast['created_at'];
        }
      }
    }

    if ($invitesCounter == 3) {
      return FALSE;
    }

    // Search for letter opens
    // Unset user if open exists
    foreach ($inviteBroadcasts as $broadcast) {
      foreach ($user['MandrillBroadcastRecipient'] as $recipient) {
        if ($broadcast['id'] == $recipient['broadcast_id']) {
          $openExistsWhere = array('recipient_id' => $recipient['id'],
                                   'broadcast_id' => $broadcast['id']);
          $openExists = ManagerHolder::get('MandrillBroadcastOpen')->existsWhere($openExistsWhere);
          if ($openExists) {
            return FALSE;
          }
        }
      }
    }

    if ($invitesCounter == 2) {
      $now = date(DOCTRINE_DATE_FORMAT);
      if (strtotime($now) - strtotime($lastInviteDate) == 0) {
        return TRUE;
      } else {
        return FALSE;
      }
    } else {
      return TRUE;
    }
  }

}
