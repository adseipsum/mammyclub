<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Auto broadcast controller.
 * @author Itirra - http://itirra.com
 */
class Auto_Broadcast_Controller extends Base_Project_Controller {

  /* Security code. Ensures that nobody runs this controller, but cron */
  const PROTECTION_CODE = '30ad3a3a1d2c7c63102e09e6fe4bb253';

  /**
   * Constructor.
   */
  public function Auto_Broadcast_Controller() {
    parent::Base_Project_Controller();
  }

  /**
   * Generate and send Broadcast
   */
  public function send_broadcast($protection_code) {

    // Process get params
    if (strpos($protection_code, '?') !== FALSE) {
      $protCodeSegmets = explode('?', $protection_code);
      $protection_code = $protCodeSegmets[0];
      $getKVpairArr = explode('&', $protCodeSegmets[1]);
      foreach ($getKVpairArr as $kv) {
        $getSegm = explode('=', $kv);
        $_GET[$getSegm[0]] = urldecode($getSegm[1]);
      }
    }

    if ($protection_code != self::PROTECTION_CODE || !is_cli()) {
      show_404();
    }

    $testing = FALSE;
    if(isset($_GET['test']) && $_GET['test'] == TRUE) {
      $testing = TRUE;
      log_message('debug', '[send_pw_broadcast] - TEST MODE ENABLED');
    }

    $this->load->helpers(array('project_broadcast', 'common/itirra_date'));

    log_message('info', '[send_pw_broadcast] - STARTED AT ' . date(DOCTRINE_DATE_FORMAT));

    $todayDate = date('Y-m-d');
    $period = (60 * 60 * 24 * 6) + (60 * 60 * 11); //6 days 12 hours
    $emailAmount = 0;
    $pregnancyWeeks = ManagerHolder::get('PregnancyWeek')->getAll('e.*, products.*');

    foreach ($pregnancyWeeks as $week) {

      log_message('info', '[send_pw_broadcast] - Started processing broadcast: ' . $week['number'] . ' pregnancy week');

      $users = ManagerHolder::get('User')->getAllWhere(array('pregnancyweek_current_id' => $week['id'], 'pregnancyweek_current_started' => $todayDate, 'newsletter' => TRUE), 'e.*, auth_info.*');
      if (!empty($users)) {
        foreach ($users as $k => $v) {
          // User can manually set pregnancy week before this method starts and user will recieve email twice.
          // First after subscribe and second because of its method.
          // In this case value of 'pregnancyweek_current_started' will be today date.

          // Checks for register date of user
          // For example, user registered 1.03 00.00.00 - 23.59.59
          // Broadcast will be created only if datetime >= 7.03 12.00.00
          if ((strtotime(date(DOCTRINE_DATE_FORMAT)) - strtotime($v['auth_info']['created_at'])) <= $period) {
            unset($users[$k]);
          }
        }
      }

      if($testing == TRUE) {
        $toEmails = array('alexeii.boyko@gmail.com');
        $users = ManagerHolder::get('User')->getAllWhere(array('auth_info.email' => $toEmails), 'e.*, auth_info.*');
        trace($users);
      }

      log_message('info', '[send_pw_broadcast] - Recipient amount for ' . $week['number'] . ' pregnancy week: ' . count($users));

      if (!empty($users)) {

        $article = ManagerHolder::get('PregnancyArticle')->getOneWhere(array('pregnancyweek_id' => $week['id']));

        // Insert broadcast data
        $broadcastData = array('subject' => $week['email_subject'],
                               'text' => $article['content'],
                               'recipients_count' => count($users),
                               'read_count' => 0,
                               'link_visited_count' => 0,
                               'created_at' => date(DOCTRINE_DATE_FORMAT),
                               'type' => 'pregnancy_week_broadcast');
        $broadcastId = ManagerHolder::get('MandrillBroadcast')->insert($broadcastData);

        // Create view data
        $standartViewData = array();
        $standartViewData['email_subject'] = !empty($week['email_subject']) ? $week['email_subject'] : '';
        $standartViewData['data']['email_intro'] = !empty($week['email_intro']) ? $week['email_intro'] : '';
        $standartViewData['data']['email_outro'] = !empty($week['email_outro']) ? $week['email_outro'] : '';
        $standartViewData['data']['email_appeal'] = !empty($week['email_appeal']) ? $week['email_appeal'] : '';
        $standartViewData['data']['message'] = $article['content'];
        if(strpos($week['email_main_text'], '{PRODUCTS}') !== FALSE) {
          $standartViewData['data']['message'] .= $week['email_main_text'];
        }

        foreach ($users as $user) {

          $viewData = $standartViewData;
          if($user['country'] != 'UA') {
            $viewData['data']['message'] = prepare_viewdata_msg_ru($article);
          }

          // Process products
          $products = array();
          if(isset($week['products']) && !empty($week['products'])) {
            foreach ($week['products'] as $product) {
              $url = shop_url($product['page_url']);
              if (substr($url, -1) == '/') {
                $url = substr($url, 0 , -1);
              }
              $product['url_with_login_key'] = $url . '?' . LOGIN_KEY . '=' . $user['login_key'];
              $product['url_for_buy_button'] = $url . '?' . LOGIN_KEY . '=' . $user['login_key'] . '&' . REDIRECT_TO_CART_KEY . '=1';
              $products[] = $product;
            }
          }

          foreach ($viewData['data'] as &$data) {
            if (!empty($data)) {
              if(!empty($products)) {
                $data = ManagerHolder::get('ProductBroadcast')->process_product_broadcast_showcase($data, $products, $user);
              }
              $googleAnalData = array('utm_source'   => $week['number'] . '_week_preg',
                                      'utm_medium'   => 'email',
                                      'utm_campaign' => 'PWW');
              ManagerHolder::get('MandrillBroadcast')->addLoginKeyToLink($data, $user['login_key'], $googleAnalData);
              kprintfLettersContent($user, $viewData);
              if (strpos($data, '{UNSUBSCRIBE_LINK}') !== FALSE) {
                $unsubscribeLink = site_url('отписаться-от-рассылки?' . LOGIN_KEY . '=' . $user['login_key']);
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
            ManagerHolder::get('EmailMandrill')->setMetadata(array('broadcast_id' => $broadcastId,
                                                                   'recipient_id' => $recipientId));
            ManagerHolder::get('EmailMandrill')->sendTemplate($userData['email'], 'pregnancy_week_broadcast/view', $viewData['data'], $viewData['email_subject']);
            $emailAmount++;
            log_message('info',  '[send_pw_broadcast] - Sending email to ' . $user['auth_info']['email'] . ' (recipient_id: ' . $recipientId . ') for broadcast: '  . $week['number'] . ' pregnancy week');
          } catch (Exception $e) {
            log_message('error', '[send_pw_broadcast] - Broadcast send error:' . $e->getMessage() . '; on email: ' . $user['auth_info']['email']);
          };
        }

        $broadcastData['sent_date'] = date(DOCTRINE_DATE_FORMAT);
        ManagerHolder::get('MandrillBroadcast')->updateAllWhere(array('id' => $broadcastId), $broadcastData);
      }

      log_message('info', '[send_pw_broadcast] - Finished processing broadcast: ' . $week['number'] . ' pregnancy week');

      if($testing == TRUE) {
        break;
      }
    }

    ManagerHolder::get('EmailNotice')->sendNoticeAboutBroadcastEnd('pregnancy_week_broadcast', $emailAmount);

    log_message('info', '[send_pw_broadcast] - FINISHED AT ' . date(DOCTRINE_DATE_FORMAT));
    die();
  }

  /**
   * Send product broadcast
   * @param unknown $protection_code
   */
  public function send_product_broadcast($protection_code) {
    if ($protection_code != self::PROTECTION_CODE) {
      show_404();
    }

    $now = date(DOCTRINE_DATE_FORMAT);
    $emailAmount = 0;

    //------------------------------------------------------------------
    // Get product broadcasts that must be sent now
    $productBroadcasts = ManagerHolder::get('ProductBroadcast')->getAllWhere(array('is_sent' => FALSE, 'sent_datetime <' => $now), 'e.*, products.*, countries.*');
    if(empty($productBroadcasts)) {
      die();
    }

    log_message('info', '[send_product_broadcast] - STARTED AT ' . date(DOCTRINE_DATE_FORMAT));

    //------------------------------------------------------------------
    // Add pregnancy week or users (fucking piece of shit implemented by vanya)
    foreach ($productBroadcasts as &$prodBroadcast) {
      // Get users
      // Ignore pregnancy weeks if users exist
      $users = ManagerHolder::get('ProductBroadcastUser')->getAllWhere(array('product_broadcast_id' => $prodBroadcast['id']), 'e.*, user.*');
      if (!empty($users)) {
        $userIds = get_array_vals_by_second_key($users, 'user_id');
        $prodBroadcast['users'] = ManagerHolder::get('User')->getAllWhere(array('id' => $userIds), 'e.*, auth_info.*, pregnancyweek_current.*');
      } else {
        $pregnancyWeekIds = ManagerHolder::get('ProductBroadcastPregnancyWeek')->getAllWhere(array('product_broadcast_id' => $prodBroadcast['id']), 'e.*');
        if (!empty($pregnancyWeekIds)) {
          $pregnancyWeekIds = get_array_vals_by_second_key($pregnancyWeekIds, 'pregnancy_week_id');
          $prodBroadcast['pregnancy_weeks'] = ManagerHolder::get('PregnancyWeek')->getAllWhere(array('id' => $pregnancyWeekIds), 'name, email_subject');
        }
      }
    }

    //------------------------------------------------------------------
    foreach ($productBroadcasts as $productBroadcast) {

      log_message('info', '[send_product_broadcast] - Started processing broadcast: ' . $productBroadcast['name']);

      // Generate list of users
      $users = array();

      // Get recipients
      if ( !empty($productBroadcast['age_of_child']) || isset($productBroadcast['pregnancy_weeks']) ) {
        $pregnancyWeekIds = array();
        if(isset($productBroadcast['pregnancy_weeks']) && !empty($productBroadcast['pregnancy_weeks'])) {
          $pregnancyWeekIds = get_array_vals_by_second_key($productBroadcast['pregnancy_weeks'], 'id');
        }
        $users = ManagerHolder::get('User')->getAllWhereWeeksOrAgeOfChild($pregnancyWeekIds, $productBroadcast['age_of_child']);
      } elseif (isset($productBroadcast['users']) && !empty($productBroadcast['users'])) {
        $users = $productBroadcast['users'];
      }

      // Unset users if they didn't match the criteria
      if(!empty($users)) {

        foreach ($users as $key => $user) {
          if($productBroadcast['newsletter_recommended_products'] == TRUE && $user['newsletter_recommended_products'] == FALSE) {
            unset($users[$key]);
          }
          if($productBroadcast['newsletter_shop'] == TRUE && $user['newsletter_shop'] == FALSE) {
            unset($users[$key]);
          }
          if($productBroadcast['exclude_who_buys_without_discount'] == TRUE && $user['buys_without_discount'] == TRUE) {
            unset($users[$key]);
          }
          if(!empty($productBroadcast['countries'])) {
            $countryCodes = get_array_vals_by_second_key($productBroadcast['countries'], 'code');
            if(empty($user['country']) || !in_array($user['country'], $countryCodes)) {
              unset($users[$key]);
            }
          }
        }

      }

      log_message('info', '[send_product_broadcast] - Recipient amount for ' . $productBroadcast['name']. ' broadcast: ' . count($users));

      if (!empty($users)) {

        // Create mandrill broadcast
        $broadcastData = array('name' => $productBroadcast['name'],
                               'subject' => $productBroadcast['subject'],
                               'text' => '',
                               'recipients_count' => count($users),
                               'read_count' => 0,
                               'link_visited_count' => 0,
                               'created_at' => date(DOCTRINE_DATE_FORMAT),
                               'type' => 'product_broadcast');

        // Insert broadcast data
        $broadcastId = ManagerHolder::get('MandrillBroadcast')->insert($broadcastData);


        foreach ($users as $user) {

          // Insert recipient data
          $userData = array('email' => $user['auth_info']['email'],
                            'user_id' => $user['id'],
                            'is_read' => 0,
                            'is_send' => 0,
                            'data' => serialize($user),
                            'broadcast_id' => $broadcastId,
                            'updated_at' => date(DOCTRINE_DATE_FORMAT));
          $recipientId = ManagerHolder::get('MandrillBroadcastRecipient')->insert($userData);

          // Process data for view
          $viewData = ManagerHolder::get('ProductBroadcast')->createProductBroadcastContent($productBroadcast, $user);

          foreach ($viewData as $data) {
            ManagerHolder::get('MandrillBroadcast')->saveBroadcastLinks($data, $broadcastId);
          }

          $viewData['is_shop'] = TRUE;

          try {
            $metaData = array('broadcast_id' => $broadcastId,
                              'recipient_id' => $recipientId);
            ManagerHolder::get('EmailMandrill')->setMetadata($metaData);
            ManagerHolder::get('EmailMandrill')->sendTemplate($user['auth_info']['email'], 'product_broadcast/view', $viewData, $viewData['subject']);
            $emailAmount++;
            log_message('info',  '[send_product_broadcast] - Sending email to ' . $user['auth_info']['email'] . ' (recipient_id: ' . $recipientId . ') for broadcast: ' . $productBroadcast['name']);
          } catch (Exception $e) {
            log_message('error', '[send_product_broadcast] - Product broadcast send error: ' . $e->getMessage() . '; on email: ' . $user['auth_info']['email']);
          }
          unset($viewData, $metaData, $userData);
        }

        ManagerHolder::get('MandrillBroadcast')->updateById($broadcastId, 'sent_date', date(DOCTRINE_DATE_FORMAT));

      }
      unset($users);
      ManagerHolder::get('ProductBroadcast')->updateById($productBroadcast['id'], 'is_sent', TRUE);

      log_message('info', '[send_product_broadcast] - Finished processing broadcast: ' . $productBroadcast['name']);
    }

    ManagerHolder::get('EmailNotice')->sendNoticeAboutBroadcastEnd('product_broadcast', $emailAmount);

    log_message('info', '[send_product_broadcast] - FINISHED AT ' . date(DOCTRINE_DATE_FORMAT));
    die();
  }

  /**
   * Unsubscribe process.
   */
  public function recommended_product_broadcast_unsubscribe_process() {
    ManagerHolder::get('User')->updateById($this->authEntity['id'], 'newsletter_recommended_products', FALSE);
    redirect('отписка-от-рассылки-полезные-покупки-для-беременных');
  }

  /**
   * Unsubscribe.
   */
  public function recommended_product_broadcast_unsubscribe() {
    if($this->isLoggedIn == FALSE || empty($this->authEntity)) {
      show_404();
    }
    $header = array('title' => 'Вы успешно отписались от рассылки "Полезные покупки для беременных"',
                    'description' => 'Вы успешно отписались от рассылки "Полезные покупки для беременных"');
    $this->layout->set('header', $header);

    $this->layout->setModule('week');
    $this->layout->set('user', $this->authEntity);
    $this->layout->view('unsubscribe');
  }

  /**
   * Newsletters subscribe process.
   */
  public function recommended_products_broadcast_subscribe_process() {
    if ($this->isLoggedIn == FALSE) {
      uni_redirect('вход');
    }

    ManagerHolder::get('User')->updateAllWhere(array('id' => $this->authEntity['id']), $data);

    $this->auth->refresh();
    $this->authEntity = $this->auth->getAuthEntity();
    //ManagerHolder::get('PregnancyWeek')->sendPregnancyWeekEmail($this->authEntity);
    //redirect('успешная-подписка?pregnancy_week=' . $_POST['pregnancyweek_id']);
    redirect(RECOMMENDED_PRODUCTS_BROADCAST_SUBSCRIBE_PAGE_URL);
  }

  /**
   * Subscribe success page
   */
  public function recommended_products_broadcast_subscribe_success_page() {
    if (isset($_GET['pregnancy_week']) && !empty($_GET['pregnancy_week'])) {
      $pregnancyWeek = ManagerHolder::get('PregnancyWeek')->getById($_GET['pregnancy_week'], 'e.*');
      if (!empty($pregnancyWeek)) {
        $this->layout->setLayout('main');
        $this->layout->set('pregnancyWeek', $pregnancyWeek);
        $this->layout->view('subscribe_success');
      } else {
        show_404();
      }
    } else {
      show_404();
    }
  }

}