<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * ProductBroadcastManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class ProductBroadcastManager extends BaseManager {

  /** Fields. */
  public $fields = array("name" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "subject" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "email_appeal" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "email_intro" => array("type" => "tinymce", "class" => "charCounter", "attrs" => array("maxlength" => 5000)),
                         "email_main_text" => array("type" => "tinymce", "class" => "charCounter", "attrs" => array("maxlength" => 5000)),
                         "email_outro" => array("type" => "tinymce", "class" => "charCounter", "attrs" => array("maxlength" => 5000)),
                         "utm_source" => array("type" => "enum", "class" => "required"),
                         "sent_datetime" => array("type" => "datetime"),
                         "is_sent" => array("type" => "checkbox"),
                         "countries" => array("type" => "multipleselect_chosen", "relation" => array("entity_name" => "Country", "search" => TRUE)),
                         "pregnancy_weeks" => array("type" => "multipleselect_chosen", "relation" => array("entity_name" => "PregnancyWeek", "search" => TRUE)),
                         "age_of_child" => array("type" => "input"),
                         "users" => array("type" => "multipleselect_chosen", "relation" => array("name_field" => "auth_info.email", "entity_name" => "User", "search" => TRUE)),
                         "newsletter_recommended_products" => array("type" => "checkbox"),
                         "newsletter_shop" => array("type" => "checkbox"),
                         "exclude_who_buys_without_discount" => array("type" => "checkbox"),
                         "products" => array("type" => "multipleselect", "relation" => array("entity_name" => "Product", "where_array" => array("not_in_stock" => FALSE, "published" => TRUE), "search" => TRUE, "sort" => TRUE)));

  /** List params. */
  public $listParams = array("utm_source", "name", "sent_datetime", "is_sent", array("pregnancy_weeks" => "name"), "age_of_child", array("countries" => "name"));

  /**
   * Create product btoadcast content
   * @param array $broadcast
   * @param array $user
   * @return array
   */
  public function createProductBroadcastContent($broadcast, $user) {

    $viewData = array();
    $viewData['subject'] = !empty($broadcast['subject']) ? $broadcast['subject'] : '';
    $viewData['email_appeal'] = !empty($broadcast['email_appeal']) ? $broadcast['email_appeal'] : '';
    $viewData['email_intro'] = !empty($broadcast['email_intro']) ? $broadcast['email_intro'] : '';
    $viewData['email_outro'] = !empty($broadcast['email_outro']) ? $broadcast['email_outro'] : '';
    $viewData['email_main_text'] = !empty($broadcast['email_main_text']) ? $broadcast['email_main_text'] : '';

    $ci = &get_instance();
    $ci->load->helper('project_broadcast');

    $products = array();
    if (isset($broadcast['products']) && !empty($broadcast['products'])) {
      $products = $broadcast['products'];
      foreach ($products as &$product) {
        $url = shop_url($product['page_url']);
        if (substr($url, -1) == '/') {
          $url = substr($url, 0 , -1);
        }
        $product['url_with_login_key'] = $url . '?' . LOGIN_KEY . '=' . $user['login_key'];
        $product['url_for_buy_button'] = $url . '?' . LOGIN_KEY . '=' . $user['login_key'] . '&' . REDIRECT_TO_CART_KEY . '=1';
      }
    }

    // Proccess data
    foreach ($viewData as &$data) {
      if (!empty($data)) {
        if(!empty($products)) {
          $data = $this->process_product_broadcast_showcase($data, $products, $user);
        }

        // ------- Process $googleAnalData -------------------
        $googleAnalData = array('utm_source'   => $broadcast['utm_source'],
                                'utm_medium'   => 'email',
                                'utm_campaign' => 'LAP');

        ManagerHolder::get('MandrillBroadcast')->addLoginKeyToLink($data, $user['login_key'], $googleAnalData);
        kprintfLettersContent($user, $data);
        $data = process_week_tag($data, $user);
        // Search for short tag and replace it with link
        if (strpos($data, '{UNSUBSCRIBE_LINK}') !== FALSE) {
          $unsubscribeLink = site_url('отписаться-от-товарной-рассылки?' . LOGIN_KEY . '=' . $user['login_key']);
          $data = str_replace('{UNSUBSCRIBE_LINK}', $unsubscribeLink, $data);
        }
      }
    }

    return $viewData;
  }

  /**
   * process_product_broadcast_showcase
   * @param string $text
   * @param array $products
   * @param array $user
   * @return string
   */
  public function process_product_broadcast_showcase($text, $products, $user = array()) {
    if(!empty($products) && strpos($text, '{PRODUCTS}') !== FALSE) {
      $CI =& get_instance();
      $CI->load->library("common/layout");
      $this->layout = new Layout();
      $showcase = '<div style="padding: 10px 20px;">';

      if (!empty($user)) {
        ManagerHolder::get('User')->addAvailableSalestoUser($user);

        foreach ($products as &$product) {
          ManagerHolder::get('Sale')->addAvailableSaleToProducts($user, $product);
        }
      }

      $showcase .= $this->layout->render('includes/email/product_broadcast/product_broadcast_showcase', array('products' => $products), TRUE);
      $showcase .= '</div>';
      $text = str_replace('<p><span class="description">{PRODUCTS}</span></p>', $showcase, $text);
      $text = str_replace('<p><span>{PRODUCTS}</span></p>', $showcase, $text);
      $text = str_replace('<p>{PRODUCTS}</p>', $showcase, $text);
    }
    return $text;
  }

  /**
   * process_cartitems_broadcast_table
   * @param string $text
   * @param array $siteorder
   * @return string
   */
  public function process_cartitems_broadcast_table($text, $siteorder) {

    $cartItems = ManagerHolder::get('SiteOrderItem')->getAllWhere(array('siteorder_id' => $siteorder['id']), 'e.*, product.*');

    if (!empty($cartItems)) {

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
        $table .= '<td ' . $tdStyle . '>' . $cartItem['price'] . ' грн.</td>';
        if ($discountPriceExists) {
          if ($cartItem['discount_price'] > 0) {
            $table .= '<td ' . $tdStyle . '>' . $cartItem['discount_price'] . ' грн.</td>';
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
        $table .= '<td ' . $tdStyle . '>' . $cartItem['item_total'] . ' грн.</td>';
        $table .= '</tr>';
      }
	    $deliveryName = 'Доставка';
	    if (!empty($siteorder['delivery'])){
		    $deliveryName = $siteorder['delivery']['name'];
	    }

      $colspan = 3;
      $colspan = $discountPriceExists ? ++$colspan : $colspan;
      $colspan = $paramsExists ? ++$colspan : $colspan;

      if ($siteorder['total_discount'] > 0) {
        $table .= '<tr><td ' . $tdStyle . ' colspan="' . $colspan . '">' . $deliveryName . '</td>';
        $table .= '<td ' . $tdStyle . ' colspan="1">' . round($siteorder['delivery_price']) . ' грн.</td></tr>';
        $table .= '<tr><td ' . $tdStyle . ' colspan="' . $colspan . '">Cкидка по заказу</td>';
        $table .= '<td ' . $tdStyle . ' colspan="1">' . round($siteorder['total_discount']) . ' грн.</td></tr>';
        $table .= '<tr><td ' . $tdStyle . ' colspan="' . $colspan . '">Итого</td>';
        $table .= '<td ' . $tdStyle . ' colspan="1">' . round($siteorder['total_with_discount']) . ' грн.</td></tr>';
      } else {
	      $table .= '<tr><td ' . $tdStyle . ' colspan="' . $colspan . '">' . $deliveryName . '</td>';
	      $table .= '<td ' . $tdStyle . ' colspan="1">' . round($siteorder['delivery_price']) . ' грн.</td></tr>';
        $table .= '<tr><td ' . $tdStyle . ' colspan="' . $colspan . '">Итого</td>';
        $table .= '<td ' . $tdStyle . ' colspan="1">' . round($siteorder['total_with_discount']) . ' грн.</td></tr>';
      }

      $table .= '</table>';

      return str_replace('{products_table}', $table, $text);
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
    if (strpos($what, 'users.') !== FALSE || $what == '*') {
      $query->addSelect("auth_info.*")->leftJoin("users.auth_info auth_info");
    }
    if (strpos($what, 'products.') !== FALSE || $what == '*') {
      $query->addSelect("products_image.*")->leftJoin("products.image products_image");
    }
    return $query;
  }

}