<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * RecommendedProductsBroadcastManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class RecommendedProductsBroadcastManager extends BaseManager {

  /** Fields. */
  public $fields = array("name" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "subject" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "email_appeal" => array("type" => "tinymce", "attrs" => array("maxlength" => 255)),
                         "email_intro" => array("type" => "tinymce", "class" => "charCounter", "attrs" => array("maxlength" => 5000)),
                         "email_main_text" => array("type" => "tinymce", "class" => "charCounter", "attrs" => array("maxlength" => 5000)),
                         "email_short_text" => array("type" => "tinymce", "class" => "charCounter", "attrs" => array("maxlength" => 5000)),
                         "email_outro" => array("type" => "tinymce", "class" => "charCounter", "attrs" => array("maxlength" => 5000)),
                         "pregnancy_weeks" => array("type" => "multipleselect_chosen", "relation" => array("entity_name" => "PregnancyWeek", "search" => TRUE)),
                         "countries" => array("type" => "multipleselect_chosen", "relation" => array("entity_name" => "Country", "search" => TRUE)),
                         "products" => array("type" => "multipleselect", "relation" => array("entity_name" => "Product", "where_array" => array("not_in_stock" => FALSE, "published" => TRUE), "search" => TRUE, "sort" => TRUE)));

  /** List params. */
  public $listParams = array("name", array("pregnancy_weeks" => "name"), array("countries" => "name"));

  /**
   * Send pregnancy week e-mail
   * @param array $entity
   */
  public function sendSingleLetterOfBroadcast(&$user) {

    if (!isset($user['auth_info']) || empty($user['auth_info']) || !isset($user['pregnancyweek_current']) || empty($user['pregnancyweek_current'])) {
      $user = ManagerHolder::get('User')->getById($user['id'], 'e.*, auth_info.*, pregnancyweek_current.*');
    }

    $broadcastWhere = array('pregnancy_week_id' => $user['pregnancyweek_current_id']);
    $broadcast = ManagerHolder::get('RecommendedProductsBroadcastPregnancyWeek')->getOneWhere($broadcastWhere, 'e.*, recommended_products_broadcast.*');
//     $broadcast = $broadcast['recommended_products_broadcast'];
    $broadcast = ManagerHolder::get('RecommendedProductsBroadcast')->getById($broadcast['recommended_products_broadcast_id'], 'e.*, products.*');

    // Collect data to array
    $viewData = array();
    $viewData['subject'] = !empty($broadcast['subject']) ? $broadcast['subject'] : '';
    $viewData['data']['email_appeal'] = !empty($broadcast['email_appeal']) ? $broadcast['email_appeal'] : '';
    $viewData['data']['email_intro'] = !empty($broadcast['email_intro']) ? $broadcast['email_intro'] : '';
    $viewData['data']['email_outro'] = !empty($broadcast['email_outro']) ? $broadcast['email_outro'] : '';
    $viewData['data']['email_main_text'] = !empty($broadcast['email_main_text']) ? $broadcast['email_main_text'] : '';
    if($user['country'] != 'UA') {
      $viewData['data']['email_main_text'] = prepare_viewdata_not_ua($broadcast);
    }

    $ci = &get_instance();
    $ci->load->helper('project_broadcast');

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

    // Proccess data
    foreach ($viewData['data'] as &$data) {
      if (!empty($data)) {
        if(!empty($products)) {
          $data = ManagerHolder::get('ProductBroadcast')->process_product_broadcast_showcase($data, $products);
        }
        $googleAnalData = array('utm_source'   => $user['pregnancyweek_current']['number'] . '_week_preg',
                                'utm_medium'   => 'email',
                                'utm_campaign' => 'UPP');
        ManagerHolder::get('MandrillBroadcast')->addLoginKeyToLink($data, $user['login_key'], $googleAnalData);
        kprintfLettersContent($user, $viewData);
        // Search for short tag and replace it with link
        if (strpos($data, '{UNSUBSCRIBE_LINK}') !== FALSE) {
          $unsubscribeLink = site_url(RECOMMENDED_PRODUCTS_BROADCAST_UNSUBSCRIBE_PROCESS . '?' . LOGIN_KEY . '=' . $user['login_key']);
          $data = str_replace('{UNSUBSCRIBE_LINK}', $unsubscribeLink, $data);
        }
      }
    }

    // Create broadcast
    $broadcastData = array('subject' => $viewData['subject'],
                           'text' => '',
                           'recipients_count' => 1,
                           'read_count' => 0,
                           'link_visited_count' => 0,
                           'created_at' => date(DOCTRINE_DATE_FORMAT),
                           'type' => 'recommended_products_broadcast');
    $broadcastId = ManagerHolder::get('MandrillBroadcast')->insert($broadcastData);

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
      ManagerHolder::get('EmailMandrill')->setTag('Single letter of recommended products broadcast');
      ManagerHolder::get('EmailMandrill')->sendTemplate($user['auth_info']['email'], 'recommended_products_broadcast/view', $viewData['data'], $viewData['subject']);
      log_message('info',  '[send_rec_prod_broadcast_single] - Sending email to ' . $user['auth_info']['email'] . ' (recipient_id: ' . $recipientId . ') for broadcast: ' . $broadcast['name']);
    } catch (Exception $e) {
      log_message('error', '[send_rec_prod_broadcast_single] - Broadcast send error:' . $e->getMessage() . '; on email: ' . $user['auth_info']['email']);
    }

  }

  /**
   * PreProcessWhereQuery.
   * OVERRIDE THIS METHOD IN A SUBCLASS TO ADD joins and other stuff.
   * @param Doctrine_Query $query
   * @return Doctrine_Query
   */
  protected function preProcessWhereQuery($query, $pref, $what = "*") {
    $query = parent::preProcessWhereQuery($query, $pref, $what);
    if (strpos($what, 'products.') !== FALSE || $what == '*') {
      $query->addSelect("products_image.*")->leftJoin("products.image products_image");
    }
    return $query;
  }

}
