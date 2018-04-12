<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Email notice manager
 */
class EmailNoticeManager {

  /** Admins emails */
  protected $emails;

  protected $ci;

  public function EmailNoticeManager() {
    $this->ci =& get_instance();
    $this->emails = $this->getAdminsEmail();
  }

  /**
   * Send new order notice to admins
   * @param integer $sId
   */
  public function sendNewOrderNoticeToAdmins($sId) {
    $this->ci->load->helper('common/itirra_commons');
    $order = ManagerHolder::get('SiteOrder')->getById($sId);

    $message = 'Новый заказ №' . $order['code'] . '<br />';
    $message .= '<b>Покупатель: ' . $order['fio'] . '</b><br />';
    $message .= '<b>Телефона покупателя: ' . $order['phone'] . '</b><br />';
    $message .= 'Адрес доставки: <br />';
    $message .= $order['delivery_city']['name'] . ', ' . $order['delivery_street'] . ' ' . $order['delivery_house'] . ', ' . $order['delivery_flat'] . '<br />';
    $message .= '<br />';
    $message .= '<a href="' . admin_site_url('siteorder/add_edit/' . $sId) . '">Перейти к заказу в админке</a>';

    $subject = 'Новый заказ №' . $order['code'];
    $this->sendEmails($subject, $message, TRUE);
  }

  /**
   * Send new order notice to admins
   * @param integer $sId
   */
  public function sendNewpostSyncError($sId) {
    $this->ci->load->helper('common/itirra_commons');
    $order = ManagerHolder::get('SiteOrder')->getById($sId);

    $message = 'Заказ №' . $order['code'] . '<br />';
    $message .= '<b>Имя: ' . $order['first_name'] . '</b><br />';
    $message .= '<b>Телефона покупателя: ' . $order['phone'] . '</b><br />';
    $message .= '<b>ТТН: ' . $order['ttn_code'] . '</b><br />';
    $message .= '<br />';
    $message .= '<a href="' . admin_site_url('siteorder/add_edit/' . $sId) . '">Перейти к заказу в админке</a>';

    $subject = 'Ошибка синхронизации статуса заказа №' . $order['code'];
    $this->sendEmails($subject, $message, TRUE);
  }

  /**
   * Send new liqpay checkout notice to admins
   * @param integer $sId
   */
  public function sendNewLiqpayCheckoutNoticeToAdmins($sId) {
    $this->ci->load->helper('common/itirra_commons');
    $order = ManagerHolder::get('SiteOrder')->getById($sId);
    $message = 'Заказ №' . $order['code'] . ' оплачен<br />';
    $message .= 'Сумма: ' . $order['total'] . '<br />';
    $message .= '<a href="' . admin_site_url('siteorder/add_edit/' . $sId) . '">Перейти к заказу в админке</a>';
    $subject = 'Заказ №' . $order['code'] . ' оплачен';
    $this->sendEmails($subject, $message, TRUE);
  }

  /**
   * Send new question notice to admins
   * @param integer $qId
   */
  public function sendNewQuestionNoticeToAdmins($qId) {
    $question = ManagerHolder::get('Question')->getById($qId);
    $user = ManagerHolder::get('User')->getById($question['user_id'], 'e.*, auth_info.*');

    $message = 'Пользователь <b>' . $user['name'] . '</b> (<b>' . $user['auth_info']['email'] . '</b>) добавил новый вопрос.<br />';
    $message .= 'Дата добавления - ' . $question['date'] . '<br />';
    $message .= 'Тема вопроса: <b>' . $question['name'] . '</b><br />';
    $message .= 'Вопрос:<br />' . $question['content'] . '<br />';
    $message .= '<a href="'. site_url($question['page_url']) . '">Читать вопрос на сайте</a>';

    $subject = 'Новый вопрос';
    $this->sendEmails($subject, $message, TRUE);
  }

  /**
   * Send new comment notice to admins
   * @param integer $cId
   * @param string $entityType
   */
  public function sendNewCommentNoticeToAdmins($cId, $entityType) {
    $comment = ManagerHolder::get($entityType . 'Comment')->getById($cId, 'e.*');
    $url = shop_url('/');
    if ($entityType != 'Shop') {
      $entity = ManagerHolder::get($entityType)->getById($comment['entity_id'], 'name, page_url');
      $url = site_url($entity['page_url']);
    }
    $user = ManagerHolder::get('User')->getById($comment['user_id'], 'e.*, auth_info.*');

    $message = 'Пользователь <b>' . $user['name'] . '</b> (<b>' . $user['auth_info']['email'] . '</b>) добавил новый комментарий к ';
    $message .= lang('email_notice.entity_type_dative.' . strtolower($entityType));
    if (isset($entity)) {
      $message .= ' <a href="' . $url . '">"' . $entity['name'] . '"</a>.';
    }
    $message .= '<br />';
    $message .= 'Дата добавления - ' . $comment['date'] . '<br />';
    $message .= '<b>Комментарий:</b><br />';
    $message .= $comment['content'] . '<br />';

    $message .= ' <a href="' . $url . '#commentid_' . $cId . '">Читать комментарий на сайте</a>.<br />';

    $subject = 'Новый комментарий';
    $this->sendEmails($subject, $message, TRUE);
  }

  /**
   * Send new pregnancy week review to admins
   * @param integer $rId
   */
  public function sendNewPregnancyWeekReviewToAdmins($rId) {
    $review = ManagerHolder::get('PregnancyReview')->getById($rId);

    $message = 'Пользователь <b>' . $review['user']['name'] . '</b> добавил новый отзыв на странице "Беременность по неделям" <br />';
    $message .= 'Текст отзыва: <br />';
    $message .= $review['name'] . '<br />';
    $message .= '<a href="' . site_url('беременность-по-неделям' . '#review-id-' . $review['id']) . '">Читать на сайте</a><br />';

    $subject = 'Новый отзыв на странице "Беременность по неделям"';
    $this->sendEmails($subject, $message);
  }

  /**
   * Send email notice to user about new answer to entity or comment

   * @param string $entityType
   * can be 'Question' or 'Artice'

   * @param integer $eId
   * entity id

   * @param integer $cId
   * comment id

   * @param array $authEntity
   */
  public function sendNewAnswerToQuestionNoticeToUser($entityType, $entityId, $cId, &$authEntity) {
    $what = $entityType == 'Question' ? 'name, page_url, user_id' : 'name, page_url';
    $entity = ManagerHolder::get($entityType)->getById($entityId, $what);

    $comment = ManagerHolder::get($entityType . 'Comment')->getById($cId, 'e.*');

    // In case when it simple comment to question
    // Dont send notice if it is article
    if ($entityType == 'Question') {
      if ($comment['parent_id'] == NULL) {
        $entityOwner = ManagerHolder::get('User')->getById($entity['user_id'], 'e.*, auth_info.*');
        if (!$entityOwner['newsletter_questions']) {
          return;
        }
        if ($entityOwner['id'] != $authEntity['id']) {
          $data = $this->createEmailContentForUserNotice('new_answer_to_question_notice_for_user', $entityOwner, $entity, $entityType, $comment['id']);
          $subject = 'Новый ответ на ваш вопрос';

          ManagerHolder::get('MandrillBroadcast')->processServiceEmailData($entityOwner, 'email_new_answer_on_your_question');

          ManagerHolder::get('EmailMandrill')->sendTemplate($entityOwner['auth_info']['email'], 'email_notice', $data, $subject);
        }
      }
    }

    // In case when comment is an answer to comment than is already exists
    if ($comment['parent_id'] != NULL) {
      $parentComment = ManagerHolder::get($entityType . 'Comment')->getById($comment['parent_id'], 'e.*');

      // User can answer to himself. Check for this case.
      if ($parentComment['user_id'] != $authEntity['id']) {
        $user = ManagerHolder::get('User')->getById($parentComment['user_id'], 'e.*, auth_info.*');
        if (!$user['newsletter_comments']) {
          return;
        }
        $data = $this->createEmailContentForUserNotice('new_answer_to_comment_notice_for_user', $user, $entity, $entityType, $comment['id']);
        $subject = 'Новый ответ на ваш комментарий';

        ManagerHolder::get('MandrillBroadcast')->processServiceEmailData($user, 'email_new_answer_on_your_comment');

        ManagerHolder::get('EmailMandrill')->sendTemplate($user['auth_info']['email'], 'email_notice', $data, $subject);
      }
    }
  }

  /**
   * Send new order notice to user
   * @param integer $sId
   */
  public function sendNewOrderNoticeToUser($sId) {
    $order = ManagerHolder::get('SiteOrder')->getById($sId);
    $order['delivery_city'] = $order['delivery_city']['name'];
    // subject key - new_order_notice_for_user_subject
    // content key - new_order_notice_for_user_content

    $subject = ManagerHolder::get('Settings')->getOneWhere(array('k' => 'new_order_notice_for_user_subject'), 'v');
    $subject = $subject['v'];
    $content = ManagerHolder::get('Settings')->getOneWhere(array('k' => 'new_order_notice_for_user_content'), 'v');
    $content = $content['v'];

    switch ($order['delivery_type']) {
      case 'delivery-to-post':
        $content = preg_replace('/{delivery-to-home}[\s\S]*?{\/delivery-to-home}/', '', $content);
        $content = str_replace('{delivery-to-post}', '', $content);
        $content = str_replace('{/delivery-to-post}', '', $content);
        break;
      case 'delivery-to-home':
        $content = preg_replace('/{delivery-to-post}[\s\S]*?{\/delivery-to-post}/', '', $content);
        $content = str_replace('{delivery-to-home}', '', $content);
        $content = str_replace('{/delivery-to-home}', '', $content);
        break;
    }

    $data['content'] = kprintf($content, $order);
    unset($content);

    if (strpos($data['content'], '{products_table}') !== FALSE) {
      $cartItems = ManagerHolder::get('SiteOrderItem')->getAllWhere(array('siteorder_id' => $order['id']), 'e.*, product.*');

      // Dont show column with discount price if any product in cart haven't it
      // Dont show column with additional params if any product in cart haven't them
      $discountPriceExists = FALSE;
      $paramsExists = FALSE;
      foreach ($cartItems as $cartItem) {
        if ($cartItem['discount_price'] > 0) {
          $discountPriceExists = TRUE;
        }
        if (is_not_empty($cartItem['additional_product_params'])) {
          $paramsExists = TRUE;
        }
        if ($discountPriceExists && $paramsExists) {
          break;
        }
      }

      $tdStyle = 'style="border: 1px solid black; padding: 10px;"';

      // Table's header
      $table = '<table style="border-collapse: collapse; width: 100%;">';
      $table .= '<tr><td style="border: 1px solid black; padding: 10px; width: 40%;">Товар</td>';
      if ($discountPriceExists) {
        $table .= '<td style="border: 1px solid black; padding: 10px; width: 10%;">Цена</td>';
        $table .= '<td style="border: 1px solid black; padding: 10px; width: 25%;">Цена с учетом скидки</td>';
      } else {
        $table .= '<td style="border: 1px solid black; padding: 10px; width: 10%;">Цена</td>';
      }
      if ($paramsExists) {
        $table .= '<td '.$tdStyle.'>Параметры</td>';
      }
      $table .= '<td '.$tdStyle.'>Кол-во</td><td '.$tdStyle.'>Всего</td></tr>';

      foreach ($cartItems as &$cartItem) {
        // Add params to products
        if (is_not_empty($cartItem['additional_product_params'])) {
          $cartItem['additional_product_params'] = unserialize($cartItem['additional_product_params']);
          $possibleParameters = ManagerHolder::get('ParameterProduct')->getById($cartItem['product']['possible_parameters_id'], 'e.*, parameter_main.*, parameter_secondary.*, possible_parameter_values.*');
          $possibleParameterValuesIDs = get_array_vals_by_second_key($possibleParameters['possible_parameter_values'], 'id');
        }

        $table .= '<tr>';
        $table .= '<td ' . $tdStyle . '><a href="' . shop_url($cartItem['product']['page_url']) . '">' . $cartItem['product']['name'] . '</a></td>';
        $table .= '<td ' . $tdStyle . '>' . $cartItem['price'] . '</td>';
        if ($discountPriceExists) {
          if ($cartItem['discount_price'] > 0) {
            $table .= '<td ' . $tdStyle . '>' . $cartItem['discount_price'] . '</td>';
          } else {
            $table .= '<td ' . $tdStyle . '></td>';
          }
        }

        $paramStr = '';
        if ($paramsExists) {

          if(is_not_empty($possibleParameterValuesIDs)) {

            // Checking main parameter/value
            if (is_not_empty($cartItem['additional_product_params'][0])) {
              $mainKey = array_search($cartItem['additional_product_params'][0], $possibleParameterValuesIDs);
              if($mainKey !== FALSE && $possibleParameters['possible_parameter_values'][$mainKey]['parameter_id'] ==  $possibleParameters['parameter_main_id']) {
                $paramStr .= $possibleParameters['parameter_main']['name'] . ': ' . $possibleParameters['possible_parameter_values'][$mainKey]['name'];

                // Checking secondary parameter/value
                if (is_not_empty($cartItem['additional_product_params'][1])) {
                  $secondaryKey = array_search($cartItem['additional_product_params'][1], $possibleParameterValuesIDs);
                  if($secondaryKey !== FALSE && $possibleParameters['possible_parameter_values'][$secondaryKey]['parameter_id'] ==  $possibleParameters['parameter_secondary_id']) {
                    $paramStr .= '<br />' . $possibleParameters['parameter_secondary']['name'] . ': ' . $possibleParameters['possible_parameter_values'][$secondaryKey]['name'];
                  }
                }
              }
            }

          }

          $table .= '<td ' . $tdStyle . '>' . $paramStr . '</td>';
        }

        $table .= '<td ' . $tdStyle . '>' . $cartItem['qty'] . '</td>';
        $table .= '<td ' . $tdStyle . '>' . $cartItem['item_total'] . '</td>';
        $table .= '</tr>';
      }

      $deliveryPrice = 0;
      if (!empty($order['delivery_price'])){
	      $deliveryPrice += $order['delivery_price'];
      }
      $deliveryName = 'Бесплатная';
      if (!empty($order['delivery'])){
      	$deliveryName = $order['delivery']['name'];
      }

      $colspan = 3;
      $colspan = $discountPriceExists ? ++$colspan : $colspan;
      $colspan = $paramsExists ? ++$colspan : $colspan;
	    $table .= '<tr><td ' . $tdStyle . ' colspan="' . $colspan . '">' . $deliveryName . '</td>';
	    $table .= '<td ' . $tdStyle . ' colspan="1">' . round($order['delivery_price']) . '</td></tr>';
      $table .= '<tr><td ' . $tdStyle . ' colspan="' . $colspan . '">Итого</td>';
      $table .= '<td ' . $tdStyle . ' colspan="1">' . round($order['Cart'][0]['total'] + $deliveryPrice) . '</td></tr>';
      $table .= '</table>';
      $data['content'] = str_replace('{products_table}', $table, $data['content']);
    }

    if (strpos($data['content'], '{liqpay_checkout_link}') !== FALSE) {
      $liqpayCheckoutLink = shop_url('liqpay-checkout/' . $order['id']);
      $data['content'] = str_replace('{liqpay_checkout_link}', $liqpayCheckoutLink, $data['content']);
    }

    if(strpos($data['content'], '{user_comment}') !== FALSE) {
      $comment = '';
      if(!empty($order['comment'])) {
        $comment = 'Ваш комментарий к заказу:<br/>';
        $comment .= $order['comment'];
      }
      $data['content'] = str_replace('{user_comment}', $comment, $data['content']);
    }

    $data['is_shop'] = TRUE;

    ManagerHolder::get('EmailMandrill')->sendTemplate($order['email'], 'email_notice', $data, $subject);
  }

  /**
   * Send notice about broadcast end
   * @param string $type
   * @param integer $emailAmount
   */
  public function sendNoticeAboutBroadcastEnd($type, $emailAmount) {
    if ($type == 'product_broadcast' && $emailAmount == 0) {
      return false;
    }

    $message = '';
    if ($emailAmount > 0) {
      $this->ci->load->helper('common/itirra_language');
      $message = '<p>Было отправлено ' . $emailAmount . ' ' . number_noun($emailAmount, 'emails', FALSE) . '</p><br />';
    } else {
      $message = '<p>Не было отправлено ни одного письма</p><br />';
    }
    $message .= '<a href="' . site_url(ADMIN_BASE_ROUTE . '/broadcast'). '">Перейти в рассылки</a>';

    switch ($type) {
    	case 'pregnancy_week_broadcast':
          $subject = 'Рассылка по неделям беременности отправлена';
          break;
    	case 'product_broadcast':
    	  $subject = 'Товарная рассылка отправлена';
    	  break;
  	  case 'recommended_products_broadcast':
  	    $subject = 'Рассылка ППБ отправлена';
  	    break;
	    case 'first_year_broadcast':
	      $subject = 'Рассылка "Мой малыш" отправлена';
	      break;
      case 'useful_tips_broadcast':
        $subject = 'Рассылка "Полезные советы" отправлена';
        break;
      case RETURNING_BROADCAST_FIRST:
        $subject = 'Рассылка "Возвращающие письма (Письмо 1)" отправлена';
        break;
      case RETURNING_BROADCAST_SECOND:
        $subject = 'Рассылка "Возвращающие письма (Письмо 2)" отправлена';
        break;
      case TY_BROADCAST:
        $subject = 'Рассылка "Спасибо за покупку" отправлена';
        break;
      case ORDER_BROADCAST:
        $subject = 'Рассылка "Ваш заказ отправлен" отправлена';
        break;
      case ORDER_CONFIRMED_BROADCAST:
        $subject = 'Рассылка "Ваш заказ подтвержден" отправлена';
        break;
    }
    $this->sendEmails($subject, $message, TRUE);

    return TRUE;
  }

  /**
   * Get admins email
   * @return array emails
   */
  private function getAdminsEmail() {
    $this->ci->load->helper('common/itirra_commons');
    $emails = ManagerHolder::get('Admin')->getAllWhere(array('email_notice' => TRUE), 'email');
    $emails = get_array_vals_by_second_key($emails, 'email');
    return $emails;
  }

  /**
   * Send emails
   * @param string $email
   * @param string $subject
   * @param string $message
   */
  private function sendEmails($subject, $message, $mandrill = FALSE) {
    $emailManager = 'Email';
    if($mandrill == TRUE) {
      $emailManager = 'EmailMandrill';
    }

    foreach ($this->emails as $email) {
      ManagerHolder::get($emailManager)->send($email, $subject, $message);
    }
  }

  /**
   * Create email content
   * @param string $settingKey
   * @param array $authEntity
   * @param array $entity
   * @param string $entityType
   * @param integer $cId
   * @return array
   */
  private function createEmailContentForUserNotice ($settingKey, &$user, &$entity, $entityType, $cId) {
    $data = array();
    $content = ManagerHolder::get('Settings')->getOneWhere(array('k' => $settingKey), 'v');
    $data['content'] = kprintf($content['v'], $user);
    if (strpos($data['content'], '{entity_name}') !== FALSE) {
      $data['content'] = str_replace('{entity_name}', $entity['name'], $data['content']);
    }
    if (strpos($data['content'], '{comment_link}') !== FALSE) {
      $data['content'] = str_replace('{comment_link}', site_url($entity['page_url'] . '#commentid_' . $cId), $data['content']);
    }
    if (strpos($data['content'], '{entity_type}') !== FALSE) {
      $data['content'] = str_replace('{entity_type}', ($entityType == 'Question') ? 'вопросе' : 'статье', $data['content']);
    }
    return $data;
  }

  /**
   * Send notice to admins with message from contact us page
   * @param unknown $subject
   * @param unknown $message
   */
  public function send_notice_to_admins_from_contact_us_page($subject, $message) {
    $this->sendEmails($subject, $message);
  }

  /**
   * Send notice to admins with message about how many queries to maxmind API remains
   * @param integer $questionCount
   */
  public function send_notice_to_admins_about_queries_remaining($questionCount) {
    $message = 'Осталось ' . $questionCount . ' запросов к Maxmind API. Пожалуйста заплатите.';
    $subject = 'Осталось мало запросов у API! Нужно срочно заплптить!';
    $this->sendEmails($subject, $message);
  }


  /**
   * Send email notice to user and admins about rejected webhook
   * @param array $recipient
   */
  public function senRejectedWebhookNotice($recipient) {
    $subject = 'Мы отписываем Вас от нашей рассылки.';

    $emails = array($recipient['email']);
    foreach ($emails as $e) {
      ManagerHolder::get('Email')->sendTemplate($e, 'reject_notice', array('recipient' => $recipient), $subject);
    }

  }

  /**
   * Send email bounce notice to admins
   * @param array $recipient
   */
  public function senBounceWebhookNoticeToAdmins($recipient) {
    $subject = 'Пользователь ' . $recipient['email'] . ' был удален из системы.';
    $message = 'Пользователь с email-ом ' . $recipient['email'] . ' был удален из системы из-за статуса bad_mailbox, invalid_domain';
    $this->sendEmails($subject, $message);
  }

  /**
   * Send Region Not Supported notice to admins
   */
  public function sendRegionNotSupportedNoticeToAdmins() {
    $subject = 'Попытка покупки иностранным пользователем';
    $message = $subject;
    $this->sendEmails($subject, $message);
  }

  /**
   * Send our stock product en
   * @param $endedProducts
   */
  public function sendOurStockProductEndedToAdmins($endedProducts) {
    $products = array();
    if (!empty($endedProducts['product_ids'])) {
      $products = ManagerHolder::get('Product')->getAllWhere(array('id' => $endedProducts['product_ids']), 'e.*');
    }
    $parameterGroups = array();
    if (!empty($endedProducts['parameter_group_ids'])) {
      $parameterGroups = ManagerHolder::get('ParameterGroup')->getAllWhere(array('id' => $endedProducts['parameter_group_ids']), 'e.*, product.*, main_parameter_value.*');
    }

    $subject = 'Закончился товар на складе';
    $CI = &get_instance();
    $CI->load->library('common/Layout');
    $data = array('products' => $products, 'parameterGroups' => $parameterGroups, 'subject' => $subject);

    $CI->layout->setArray($data);
    $CI->layout->setLayout('email');
    $CI->layout->setModule('email');
    $message = $CI->layout->view('product_ended_notification', TRUE, TRUE);

    $this->sendEmails($subject, $message, TRUE);
  }

}