<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

use Masterminds\HTML5;
use NovaPoshta\ApiModels\TrackingDocument;

/**
 * Cron controller.
 * @property RetailCrm\ApiClient $retailcrmapi
 * @author Itirra - http://itirra.com
 */
class Crm_Sync_Controller extends Base_Project_Controller {

  /* Security code. Ensures that nobody runs this controller, but cron */
  const PROTECTION_CODE = '30ad3a3a1d2c7c63102e09e6fe4bb253';

  private $proxyIndex = 0;

  /**
   * Constructor.
   */
  public function Cron_Controller() {
    parent::Base_Project_Controller();
    if (!url_contains(self::PROTECTION_CODE)) show_404();
    set_time_limit(0);
  }

  /**
   * Run periodic task
   */
  public function run_periodic_task() {
    log_message('debug', 'CRM SYNC PERIODIC TICK');

    $currentMin = date('i');


    if (isset($_GET['force']) && $_GET['force'] == 'cleaner') {
      $this->cleaner();
      die();
    }

    if (($currentMin == 5 || $currentMin == 30) || (isset($_GET['force']) && $_GET['force'] == 'parse_inventory_from_web')) {
      $parseOnlyMagBaby = FALSE;
      if ($currentMin % 10 == 0) {
        $parseOnlyMagBaby = TRUE;
      }

      $this->parse_inventory_from_web_new($parseOnlyMagBaby);
    }
    die();

    if ($currentMin == 5 || $currentMin == 30) {
      $this->generate_icml_catalog();
    }

    if ($currentMin == 5 || $currentMin == 30) {
      $this->newpost_sync_order_statuses();
    }

    if ($currentMin == 5 || $currentMin == 30) {
      $this->newpost_sync_order_supplier_requests();
    }

    if (isset($_GET['force']) && $_GET['force'] == 'updateProductStatuses') {
      ManagerHolder::get('Product')->updateAllWhere(array(), array('on_order' => TRUE));
      ManagerHolder::get('ParameterGroup')->updateAllWhere(array(), array('on_order' => TRUE));
      ManagerHolder::get('StoreInventory')->updateProductStatuses();
    }

    if (isset($_GET['force']) && $_GET['force'] == 'order_sync') {
      $this->order_sync();
    }

    if (isset($_GET['force']) && $_GET['force'] == 'test_email_form') {
      $this->test_email_form();
    }

    if (isset($_GET['force']) && $_GET['force'] == 'test') {
      trace($_GET);
      traced($_POST);
    }

    if (($currentMin == 5 || $currentMin == 30) || (isset($_GET['force']) && $_GET['force'] == 'export_inventory')) {
      $this->export_changes_inventory();
    }

    if (isset($_GET['force']) && $_GET['force'] == 'full_inventory_export') {
      $this->full_inventory_export();
    }

    if (isset($_GET['force']) && $_GET['force'] == 'zammler_inventory') {
      $this->zammler_inventory();
    }

    if (isset($_GET['force']) && $_GET['force'] == 'fix_parameter_not_in_stock') {
      $productIds = ManagerHolder::get('Product')->getAllWhere(array('not_in_stock' => TRUE), 'e.id');
      $productIds = get_array_vals_by_second_key($productIds, 'id');

      ManagerHolder::get('ParameterGroup')->updateAllWhere(array('product_id' => $productIds), array('not_in_stock' => TRUE));
      die('DONE');
    }

    if (isset($_GET['force']) && $_GET['force'] == 'TEST') {
      $cartItems = ManagerHolder::get('CartItem')->getAllWhere(array('parameter_group_id<>' => 'NULL'), 'e.*, parameter_group.*, cart.*');

      $wrongItems = array();
      foreach ($cartItems as $cartItem) {
        $additional = unserialize($cartItem['additional_product_params']);
        if ($additional[0] != $cartItem['parameter_group']['main_parameter_value_id']) {
          $wrongItems[] = $cartItem;
        }
      }

      traced($wrongItems);
    }
  }


  public function test_email_form() {
    $order = ManagerHolder::get('SiteOrder')->getById(3);

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
      $cartItems = ManagerHolder::get('CartItem')->getAllWhere(array('cart_id' => $order['Cart'][0]['id']), 'e.*, product.*');

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

      $colspan = 3;
      $colspan = $discountPriceExists ? ++$colspan : $colspan;
      $colspan = $paramsExists ? ++$colspan : $colspan;
      $table .= '<tr><td ' . $tdStyle . ' colspan="' . $colspan . '">Итого</td>';
      $table .= '<td ' . $tdStyle . ' colspan="1">' . round($order['Cart'][0]['total']) . '</td></tr>';
      $table .= '</table>';
      $table .= '<form action="https://mammyclub.com/crm_sync/run_periodic_task/30ad3a3a1d2c7c63102e09e6fe4bb253" method="GET">
          Ваша оценка: <select name="rating"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option> </select>
          <input type="hidden" name="force" value="test">
          <input type="submit" value="Отправить">
      ';

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

    ManagerHolder::get('EmailMandrill')->sendTemplate('oleg.poda@thelauncher.pro', 'email_notice', $data, $subject);
  }


  /**
   * NewPost sync city
   */
  private function newpost_sync_city() {
    $this->load->library('NewPostSdk');

    $cities = $this->newpostsdk->getCities();

    foreach ($cities as $city) {
      $entity = array();
      $entity['name'] = $city->Description;
      $entity['name_ru'] = $city->DescriptionRu;


      $exists = ManagerHolder::get('City')->existsWhere(array('ref' => $city->Ref));
      if ($exists) {
        ManagerHolder::get('City')->updateAllWhere(array('ref' => $city->Ref), $entity);
      } else {
        $entity['ref'] = $city->Ref;
        ManagerHolder::get('City')->insert($entity);
      }
    }
  }

  /**
   * NewPost sync city
   */
  private function newpost_sync_warehouse_types() {
    $this->load->library('NewPostSdk');

    $types = $this->newpostsdk->getWarehouseTypes();

    foreach ($types as $type) {
      $entity = array();
      $entity['name'] = $type->Description;

      $exists = ManagerHolder::get('WarehouseType')->existsWhere(array('ref' => $type->Ref));
      if ($exists) {
        ManagerHolder::get('WarehouseType')->updateAllWhere(array('ref' => $type->Ref), $entity);
      } else {
        $entity['ref'] = $type->Ref;
        ManagerHolder::get('WarehouseType')->insert($entity);
      }
    }
  }

  private function newpost_sync_counterparties() {
    $this->load->library('NewPostSdk');
    $counterparties = $this->newpostsdk->getCounterparties();

    foreach ($counterparties as $counterparty) {
      $entity = array();
      $entity['name'] = $counterparty->Description;
      $entity['property'] = 'Sender';

      $city = ManagerHolder::get('City')->getOneWhere(array('ref' => $counterparty->City), 'e.*');
      $entity['city_id'] = $city['id'];

      $exists = ManagerHolder::get('Counterparty')->existsWhere(array('ref' => $counterparty->Ref));
      if ($exists) {
        ManagerHolder::get('Counterparty')->updateAllWhere(array('ref' => $counterparty->Ref), $entity);
      } else {
        $entity['ref'] = $counterparty->Ref;
        ManagerHolder::get('Counterparty')->insert($entity);
      }
    }
  }

  private function newpost_sync_counterparty_address() {
    $this->load->library('NewPostSdk');
    $result = $this->newpostsdk->getCounterpartyAddresses();

    foreach ($result as $address) {
      $entity = array();
      $entity['name'] = $address->Description;
      $entity['street'] = $address->StreetDescription;
      $entity['building'] = $address->BuildingDescription;
      $entity['street_ref'] = $address->StreetRef;
      $entity['building_ref'] = $address->BuildingRef;
      $entity['counterparty_id'] = 1;

      $city = ManagerHolder::get('City')->getOneWhere(array('ref' => $address->CityRef), 'e.*');
      $entity['city_id'] = $city['id'];

      $exists = ManagerHolder::get('CounterpartyAddress')->existsWhere(array('ref' => $address->Ref));
      if ($exists) {
        ManagerHolder::get('CounterpartyAddress')->updateAllWhere(array('ref' => $address->Ref), $entity);
      } else {
        $entity['ref'] = $address->Ref;
        ManagerHolder::get('CounterpartyAddress')->insert($entity);
      }
    }
  }

  /**
   * newpost_sync_statuses
   */
  public function newpost_sync_order_statuses() {
    $this->load->library('NewPostSdk');

    $statusesIds = array(28, 41, 40);
    $statusMap = ManagerHolder::get('SiteOrderStatus')->getAsViewArray(array(), array('k' => 'id'));

    $orders = ManagerHolder::get('SiteOrder')->getAllWhere(array('siteorder_status_id' => $statusesIds, 'ttn_code<>' => 'NULL'), 'e.*');
    foreach ($orders as $k => $order) {
      if (empty($order['ttn_code'])) {
        unset($orders[$k]);
      } else {
        $orders[$k]['ttn_code'] = trim($orders[$k]['ttn_code']);
      }
    }
    $ttns = get_array_vals_by_second_key($orders, 'ttn_code');
    $ttns = array_chunk($ttns, 100);
    $ttns = array(array('20400074653778'));
    foreach($ttns as $ttn) {
      $refs = $ttn;

      $data = new \NovaPoshta\MethodParameters\InternetDocument_documentsTracking();
      $data->setDocuments($refs);

      $result = TrackingDocument::getStatusDocuments($data);
      traced($result);

      if ($result->success) {
        foreach ($result->data as $data) {
          $newStatusCode = NULL;
          $newPostStatusCode = $data->StatusCode;
          $newPostTtn = $data->Number;

          if ($newPostStatusCode == 106 || $newPostStatusCode == 9 || $newPostStatusCode == 10 || $newPostStatusCode == 11) {
            $newStatusCode = 'complete';
          }
          if ($newPostStatusCode == 41 || $newPostStatusCode == 4 || $newPostStatusCode == 5 || $newPostStatusCode == 6) {
            $newStatusCode = 'delivering';
          }
          if ($newPostStatusCode == 7 || $newPostStatusCode == 8) {
            $newStatusCode = 'delivered-to-post';
          }

          if (!empty($newStatusCode)) {
            $newStatusId = $statusMap[$newStatusCode];
            $siteOrder = ManagerHolder::get('SiteOrder')->getOneWhere(array('ttn_code' => $newPostTtn), 'e.*');

            if ($siteOrder['siteorder_status_id'] != $newStatusId) {
              ManagerHolder::get('SiteOrder')->updateById($siteOrder['id'], 'siteorder_status_id', $newStatusId);

              if ($newStatusCode == 'delivering') {
                $result = ManagerHolder::get('OrderBroadcast')->sendBySiteOrder($siteOrder['id']);
                if (!$result) {
                  ManagerHolder::get('SiteOrder')->updateById($siteOrder['id'], 'siteorder_status_id', $siteOrder['siteorder_status_id']);
                  ManagerHolder::get('EmailNotice')->sendNewpostSyncError($siteOrder['id']);
                }
              }
            }
          }
        }
      }
    }
  }

  /**
   * newpost_sync_statuses
   */
  public function newpost_sync_order_supplier_requests() {
    $this->load->library('NewPostSdk');

    $requests = ManagerHolder::get('SupplierRequest')->getAllWhere(array('supplier_request_status_id' => array(2, 3), 'ttn_code<>' => 'NULL'), 'e.*');

    foreach ($requests as $k => $request) {
      if (empty($request['ttn_code'])) {
        unset($request[$k]);
      } else {
        $requests[$k]['ttn_code'] = trim($requests[$k]['ttn_code']);
      }
    }

    $ttns = get_array_vals_by_second_key($requests, 'ttn_code');
    $ttns = array_chunk($ttns, 100);
    foreach($ttns as $ttn) {
      $refs = $ttn;

      $data = new \NovaPoshta\MethodParameters\InternetDocument_documentsTracking();
      $data->setDocuments($refs);

      $result = TrackingDocument::getStatusDocuments($data);
      trace($result);

      if ($result->success) {
        foreach ($result->data as $data) {
          $newStatusCode = NULL;
          $newPostStatusCode = $data->StatusCode;
          $newPostTtn = $data->Number;

          if ($newPostStatusCode == 41 || $newPostStatusCode == 4 || $newPostStatusCode == 5 || $newPostStatusCode == 6) {
            $newStatusCode = 3; //'shipped';
          }
          if ($newPostStatusCode == 7 || $newPostStatusCode == 8) {
            $newStatusCode = 5; //'delivered_to_post';
          }

          if (!empty($newStatusCode)) {
            $supplierRequest = ManagerHolder::get('SupplierRequest')->getOneWhere(array('ttn_code' => $newPostTtn), 'e.*');
            if ($supplierRequest['supplier_request_status_id'] != $newStatusCode) {
              ManagerHolder::get('SupplierRequest')->updateById($supplierRequest['id'], 'supplier_request_status_id', $newStatusCode);
            }
          }
        }
      }
    }

  }

  /**
   * Process mkarapuz price file
   */
  public function process_mkarapuz_price_file() {
    $xml = file_get_contents('http://www.mkarapuz.com.ua/price/mamaclub.xml');
    $xml = $movies = new SimpleXMLElement($xml);
    $storeId = 131;

    foreach ($xml->item as $item) {
      $barCode = (string)$item->EAN[0];

      $parameterGroup = ManagerHolder::get('ParameterGroup')->getOneWhere(array('bar_code' => $barCode), 'e.*, product.*');
      if (empty($parameterGroup)) {
        $product = ManagerHolder::get('Product')->getOneWhere(array('bar_code' => $barCode), 'e.*');
      } else {
        $product = $parameterGroup['product'];
      }

      if (empty($product)) {
        continue;
      }

      $where = array('product_id' => $product['id'], 'bar_code' => $barCode, 'store_id' => $storeId);
      $exists = ManagerHolder::get('StoreInventory')->existsWhere($where);
      $entity = array();
      $entity['qty'] = (int)$item->qty[0];

      if (!empty($parameterGroup)) {
        $entity['product_group_id'] = $parameterGroup['id'];
      }

      $entity['update_by_admin_id'] = '';
      $entity['update_source'] = 'web';
      $entity['updated_at'] = date(DOCTRINE_DATE_FORMAT);

      if ($exists) {
        ManagerHolder::get('StoreInventory')->updateAllWhere($where, $entity);
      } else {
        $entity = array_merge($entity, $where);
        ManagerHolder::get('StoreInventory')->insert($entity);
      }

      $productUpdate = array();
      $productUpdate['price'] = $item->rrc;
      $productUpdate['cost_price'] = $item->price;
      ManagerHolder::get('Product')->updateAllWhere(array('id' => $product['id']), $productUpdate);
    }
  }


  public function recountSortorder($entityId) {

    ManagerHolder::get('ProductComment')->setOrderBy('date DESC');
    $productComments = ManagerHolder::get('ProductComment')->getAllWhere(array('entity_id' => $entityId), 'id, parent_id, level, sortorder');
    $countComments = count($productComments);
    $tree = $this->buildTree($productComments);
    $updatedSortoder = array();
    foreach ($tree as $key => $item) {
      if (empty($item['children'])) {
        $updatedSortoder[$key]['sortorder'] = $countComments;
        $countComments--;
      } else {
        $updatedSortoder[$key]['sortorder'] = $countComments;
        $countComments--;
        $incrementChildSortoder = 0;
        foreach ($item['children'] as $k => $child) {
          $countChild = count($item['children']);
          $updatedSortoder[$k]['sortorder'] = $updatedSortoder[$item['id']]['sortorder'] - $countChild + $incrementChildSortoder;
          $countComments--;
          $incrementChildSortoder++;
        }
      }
    }

    foreach ($updatedSortoder as $kk => $vv) {
//      ManagerHolder::get('ProductComment')->updateAllWhere(array('id' => $kk,), array('sortorder' => $vv['sortorder']));
      ManagerHolder::get('ProductComment')->updateById($kk, 'sortorder', $vv['sortorder']);
    }
  }

  public function buildTree(array &$elements, $parentId = 0) {
    $branch = array();
    foreach ($elements as $element) {
      if ($element['parent_id'] == $parentId) {
        $children = $this->buildTree($elements, $element['id']);
        if ($children) {
          $element['children'] = $children;
        }
        $branch[$element['id']] = $element;
      }
    }
    return $branch;
  }

  /**
   * Cleaner
   */
  public function cleaner() {
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    Events::trigger('ExternalSource.processMkarapuzPriceFile', array());
    die('DONE');

    ManagerHolder::get('SiteOrder')->updateAllWhere(array('id' => 6), array('siteorder_status_id' => 1));
    die();


    $siteOrders = ManagerHolder::get('SiteOrder')->getAllWhere(array('shipment_date>' => '2010-12-12'), 'code, shipment_date');
    foreach ($siteOrders as $siteOrder) {
      ManagerHolder::get('SupplierRequestProductParameterGroup')->updateWhere(array('siteorder_code' => $siteOrder['code']), 'shipment_date', $siteOrder['shipment_date']);
    }

    die('DONE');

    $this->load->library('RetailCrmApi');
    $customers = array();

    $response = $this->retailcrmapi->getClient()->request->customersList(array(), 1, 50);
    foreach ($response->customers as $customerData) {
      $siteOrder = ManagerHolder::get('SiteOrder')->getOneWhere(array('email' => $customerData['email']), 'e.*, user.*');
      traced($customerData);
      if (empty($siteOrder)) {
        continue;
      }
      $customer = array();
      $customer['id'] = $customerData['id'];
      $customer['externalId'] = $siteOrder['user_id'];

      $customers[] = $customer;
      ManagerHolder::get('User')->updateById($siteOrder['user_id'], 'is_export_to_crm', TRUE);
    }
    traced($customers);
    $response = $this->retailcrmapi->getClient()->request->customersFixExternalIds($customers);
    traced($response);

    die();

    $k = 0;
    $siteOrders = ManagerHolder::get('SiteOrder')->getAllWhere(array('id' => 7727), 'e.*');
    foreach ($siteOrders as $siteOrder) {
      ManagerHolder::get('SiteOrder')->exportToRetailCrm($siteOrder['id'], TRUE);
      $k++;
      if (rand(0, 5) == 3) {
        sleep(1);
      }
    }

    die('DONE');

    $user = ManagerHolder::get('User')->getOneWhere(array('name' => 'elabor59'), 'e.*, pregnancyweek.*, pregnancyweek_current.*, auth_info.*');

    $this->load->library('RetailCrmApi');

    $customer = array();
    $customer['externalId'] = $user['id'];
    $customer['firstName'] = 'Андрей';
    $customer['lastName'] = 'Бусалов';
    $customer['email'] = $user['auth_info']['email'];
    $customer['customFields']['pregnancyweek'] = $user['pregnancyweek_current']['number'];
    $customer['browserId'] = '8671a2493d114d43adddc6b0e4289f66';

    $response = $this->retailcrmapi->getClient()->request->customersCreate($customer);

    traced($response);

    die('DONE');

    Events::trigger('Feed.remarketingFeedExport', array());
    die('DONE');

    $this->generate_icml_catalog();
    die();

    $event_logs = ManagerHolder::get('EventLog')->getAllWhere(array('event_method' => 'changeStatus', 'event_model' => 'SiteOrder'),'e.*');
    foreach ($event_logs as $event_log) {
      if ($event_log['data'] != 'Array') {
        $decodeData = json_decode($event_log['data'], true);

        // Check if not empty Complete status date in siteOrder entity
        $csd = ManagerHolder::get('SiteOrder')->existsWhere(array('id' => $event_log['entity_id'], 'complete_status_date <>' => ''));
        if (!$csd) {
          if ($decodeData['to']['k'] == 'complete') {
            ManagerHolder::get('SiteOrder')->updateById($event_log['entity_id'], 'complete_status_date', $event_log['created_at']);
          }
        }
      }
    }
    die('DONE');

    $this->newpost_sync_order_statuses();

    die();
    $products  = ManagerHolder::get('Product')->getAll('e.*');
    foreach ($products as $product) {
      $this->recountSortorder($product['id']);
    }

    die();
    ManagerHolder::get('AlphaSMS')->sendMessage('+380980088241', 'Test message');

    die();
    $this->newpost_sync_order_statuses();
    die('DONE');


    $lastActionDate = date('H:i', strtotime('2015-07-06 15:51:07') + 60*60);

    traced(date(DOCTRINE_DATE_FORMAT, strtotime(date($lastActionDate))));

    $lastActionDate = date('H:i', strtotime('2015-07-06 15:51:07') + 60*60);
    trace($lastActionDate);

    trace($currentTime);

    // If $currentTime greater than $lastActionDate
    trace(strtotime(date($currentTime)));
    trace(strtotime(date($lastActionDate)));

    die();


    // For products
    $productsBarCodes = ManagerHolder::get('Product')->getAll('e.bar_code');

    foreach ($productsBarCodes as $code) {
      $trimBarCode = ltrim($code['bar_code'], 0);

      if (!empty($trimBarCode) && strlen($trimBarCode) != strlen($code['bar_code'])) {
        ManagerHolder::get('Product')->updateById($code['id'], 'bar_code', $trimBarCode);
      }
    }
    unset($productsBarCodes);

    // For products parameters
    $productsParametersBarCode = ManagerHolder::get('ParameterGroup')->getAll('e.bar_code');
    foreach ($productsParametersBarCode as $code) {
      $trimBarCode = ltrim($code['bar_code'], 0);

      if (!empty($trimBarCode) && strlen($trimBarCode) != strlen($code['bar_code'])) {
        ManagerHolder::get('ParameterGroup')->updateById($code['id'], 'bar_code', $trimBarCode);
      }
    }
    unset($productsParametersBarCode);


    // For products parameters
    $storeInventoryBarCode = ManagerHolder::get('StoreInventory')->getAll('e.bar_code');
    foreach ($storeInventoryBarCode as $code) {
      $trimBarCode = ltrim($code['bar_code'], 0);

      if (!empty($trimBarCode) && strlen($trimBarCode) != strlen($code['bar_code'])) {
        ManagerHolder::get('StoreInventory')->updateById($code['id'], 'bar_code', $trimBarCode);
      }
    }
    unset($productsParametersBarCode);
    die('DONE');


    $siteOrder = ManagerHolder::get('SiteOrder')->getOneWhere(array('ttn_code' => 123), 'id');
    ManagerHolder::get('AdminNotification')->sendNotification('test', 'SiteOrder', $siteOrder['id']);

    die();
    $supplierRequests = ManagerHolder::get('SupplierRequest')->getAll('e.*');
    $statusMap = ManagerHolder::get('SupplierRequestStatus')->getAsViewArray(array(), array('k' => 'id'));
    trace($statusMap);

    foreach ($supplierRequests as $supplierRequest) {
      $id = $statusMap[$supplierRequest['status']];
      ManagerHolder::get('SupplierRequest')->updateById($supplierRequest['id'], 'supplier_request_status_id', $id);
    }

    die();

    $this->process_mkarapuz_price();
    die('FINISH');


    $xml = file_get_contents('http://www.mkarapuz.com.ua/price/mamaclub.xml');
    $xml = $movies = new SimpleXMLElement($xml);
    $storeId = 1;

    foreach ($xml->item as $item) {
      $barCode = $item->EAN;

      $parameterGroup = ManagerHolder::get('ParameterGroup')->getOneWhere(array('bar_code' => $barCode), 'e.*, product.*');
      if (empty($parameterGroup)) {
        $product = ManagerHolder::get('Product')->getOneWhere(array('bar_code' => $barCode), 'e.*');
      } else {
        $product = $parameterGroup['product'];
      }

      if (empty($product)) {
        continue;
      }

      $where = array('product_id' => $product['id'], 'bar_code' => $barCode, 'store_id' => $storeId);
      $exists = ManagerHolder::get('StoreInventory')->existsWhere($where);
      $entity = array();
      $entity['qty'] = $item->qty;

      if (!empty($parameterGroup)) {
        $entity['product_group_id'] = $parameterGroup['id'];
      }

      $entity['update_by_admin_id'] = '';
      $entity['update_source'] = 'web';
      $entity['updated_at'] = date(DOCTRINE_DATE_FORMAT);
      if ($exists) {
        ManagerHolder::get('StoreInventory')->updateAllWhere($where, $entity);
      } else {
        $entity = array_merge($entity, $where);
        ManagerHolder::get('StoreInventory')->insert($entity);
      }

      $productUpdate = array();
      $productUpdate['price'] = $item->rrc;
      $productUpdate['cost_price'] = $item->price;
      ManagerHolder::get('Product')->updateAllWhere(array('id' => $product['id']), $productUpdate);
      die();
    }

    die();
    ManagerHolder::get('SMS')->sendMessage('380980088241', 'Test', TRUE);

    die();
//    $monthAgo = date(DOCTRINE_DATE_FORMAT, time() - (3600 * 24 * 30));
//    $weekAgo = date(DOCTRINE_DATE_FORMAT, time() - (3600 * 24 * 7));
    $where = array('total_with_discount' => NULL);
    ManagerHolder::get('SiteOrder')->setOrderBy('created_at DESC');
    $siteOrders = ManagerHolder::get('SiteOrder')->getAllWhere($where, 'e.*, Cart.*, items.*');

    foreach ($siteOrders as $siteOrder) {
      $fieldMap = array(
        'product_id',
        'parameter_group_id',
        'additional_product_params',
        'qty',
        'price',
        'discount_price',
        'item_total',
        'sale_id',
        'zammler_inventory_qty',
        'other_stores_inventory_qty'
      );

      if (!empty($siteOrder['items'])) {
        die('NOT EMPTY');
      }
      if (empty($siteOrder['Cart'])) {
        continue;
      }

      foreach ($siteOrder['Cart'][0]['items'] as $cartItem) {
        $siteOrderItem = array_copy_by_keys($cartItem, $fieldMap);
        $siteOrderItem['siteorder_id'] = $siteOrder['id'];
        $siteOrderItemId = ManagerHolder::get('SiteOrderItem')->insert($siteOrderItem);

//        ManagerHolder::get('SupplierRequestProductParameterGroup')->updateWhere(array('cart_item_id' => $cartItem['id']), 'siteorder_item_id', $siteOrderItemId);
//        ManagerHolder::get('StoreReserve')->updateWhere(array('cart_item_id' => $cartItem['id']), 'siteorder_item_id', $siteOrderItemId);
      }

      $update = array();
      $update['total'] = $siteOrder['Cart'][0]['total'];
      $update['total_with_discount'] = $siteOrder['Cart'][0]['total'];
      ManagerHolder::get('SiteOrder')->updateAllWhere(array('id' => $siteOrder['id']), $update);
    }
    die('DONE');


//    $this->load->library('NewPostSdk');
//
//    $refs = array('20400064742159');
//    $data = new \NovaPoshta\MethodParameters\InternetDocument_documentsTracking();
//    $data->setDocuments($refs);
//
//    $result = TrackingDocument::getStatusDocuments($data);
//    traced($result);

//    $monthAgo = date(DOCTRINE_DATE_FORMAT, time() - (3600 * 24 * 30));
//    $where = array('created_at>' => $monthAgo, 'siteorder_status.is_cancel_reserve_status' => TRUE);
//    $siteOrders = ManagerHolder::get('SiteOrder')->getAllWhere($where, 'e.*, siteorder_status.*');
//
//    foreach ($siteOrders as $siteOrder) {
//      trace($siteOrder['id']);
//      ManagerHolder::get('SiteOrder')->processStatusChange($siteOrder['id']);
//    }
//
//    die('DONE');
//    $this->newpost_sync_order_supplier_requests();
//    $this->newpost_sync_order_statuses();

//    die();

//    $monthAgo = date(DOCTRINE_DATE_FORMAT, time() - (3600 * 24 * 30));
//    $where = array('created_at<' => $monthAgo);
//    $siteOrders = ManagerHolder::get('SiteOrder')->getAllWhere($where, 'e.*');
//    foreach ($siteOrders as $siteOrder) {
//      $update = array();
//      $fio = explode(' ', $siteOrder['fio']);
//      $firstName = isset($fio[1]) ? $fio[1] :'';
//      $lastName = isset($fio[0]) ? $fio[0] :'';
//      $middleName = isset($fio[2]) ? $fio[2] :'';
//
//      $update['first_name'] = $firstName;
//      $update['last_name'] = $lastName;
//      $update['middle_name'] = $middleName;
//      ManagerHolder::get('SiteOrder')->updateAllWhere(array('id' => $siteOrder['id']), $update);
//    }
//    die('DONE');
//    $this->newpost_sync_order_statuses();
//    die();

//    $monthAgo = date(DOCTRINE_DATE_FORMAT, time() - (3600 * 24 * 30));
//    $where = array('created_at>' => $monthAgo);
//    $siteOrders = ManagerHolder::get('SiteOrder')->getAllWhere($where, 'e.*');
//
//    foreach ($siteOrders as $siteOrder) {
//      $update = array();
//
//      $city = ManagerHolder::get('City')->getOneWhere(array('ref' => $siteOrder['delivery_city_ref']), 'e.*');
//      $update['delivery_city_id'] = $city['id'];
//
//      if (!empty($siteOrder['delivery_post_ref'])) {
//        $warehouse = ManagerHolder::get('Warehouse')->getOneWhere(array('ref' => $siteOrder['delivery_post_ref']), 'e.*');
//        $update['delivery_warehouse_id'] = $warehouse['id'];
//      }
//
//      ManagerHolder::get('SiteOrder')->updateAllWhere(array('id' => $siteOrder['id']), $update);
//    }
//
//    die('DONE');

    $counterparties = $this->newpostsdk->getAllWarehouses();
    traced($counterparties);


    $result = $this->newpostsdk->getDocuments();
    traced($result->data);

    foreach ($result as $contact) {
      $entity = array();
      $entity['name'] = $contact->Description;
      $entity['first_name'] = $contact->FirstName;
      $entity['last_name'] = $contact->LastName;
      $entity['middle_name'] = $contact->MiddleName;
      $entity['phones'] = $contact->Phones;
      $entity['email'] = $contact->Email;
      $entity['counterparty_id'] = 1;

      $exists = ManagerHolder::get('CounterpartyContactPerson')->existsWhere(array('ref' => $contact->Ref));
      if ($exists) {
        ManagerHolder::get('CounterpartyContactPerson')->updateAllWhere(array('ref' => $contact->Ref), $entity);
      } else {
        $entity['ref'] = $contact->Ref;
        ManagerHolder::get('CounterpartyContactPerson')->insert($entity);
      }
    }


    die();

    $loader = require_once BASEPATH . 'vendor/autoload.php';
    require_once BASEPATH . 'vendor/querypath/querypath/src/qp.php';

    $settings = ManagerHolder::get('StoreInventoryParserSetting')->getAllWhere(array('product_id' => 6253, 'url<>' => ''), 'e.*,store.*,product.possible_parameters_id', 50);
    $webPage = $this->getWebPage($settings[0]['url']);
    $markup = $webPage['result'];

    if ($settings[0]['store']['code'] == 'i-love-mum') {
      $this->parse_inventory_i_love_mum($markup, $settings[0]);
    }
    ManagerHolder::get('StoreInventory')->updateProductStatuses();
    die();
    $params = ManagerHolder::get('StoreInventory')->getAllWhere(array('product_group.id' => NULL), 'e.*');

    foreach ($params as $param) {
      if (empty($param['product_group_id'])) {
        continue;
      }

      if (!ManagerHolder::get('ParameterGroup')->exists($param['product_group_id'])) {
        ManagerHolder::get('StoreInventory')->deleteById($param['id']);
      }
    }
    ManagerHolder::get('StoreInventory')->updateProductStatuses();

    die('DONE');
    $webPage = $this->getWebPage('https://magbaby.com.ua/p82656112-azhurnyj-vyazannyj-pled.html');

    traced($webPage);


    $this->parse_inventory_from_web_new();
    die();

    foreach(qp($dom, 'h3:contains(Доступные опции)')->next('div')->children('div')->first()->children('div') as $item) {
//      traced(qp($item)->html());
      //      print "\n";

      $param = qp($item)->find('label')->text();
      $param = trim($param);
      $param = ManagerHolder::get('ParameterValue')->getOneWhere(array('name' => $param), 'e.*');

      $value = qp($item)->find('label')->attr('title');
      $value = trim($value);

      if ($value == 'нет в наличии') {
        $qty = 0;
      } else {
        preg_match("/в наличии ([0-9]*) шт./", $value, $mch);
        $qty = $mch[1];
      }

      $params[$param['id']] = $qty;
    }
    traced($params);

    die();
    ManagerHolder::get('Product')->updateWhere(array('not_in_stock' => FALSE), 'not_in_stock', TRUE);
    ManagerHolder::get('ParameterGroup')->updateWhere(array('not_in_stock' => FALSE), 'not_in_stock', TRUE);
    ManagerHolder::get('StoreInventory')->updateProductStatuses();


    die();
    $this->load->library('NewPostSdk');
//    $result = $this->newpostsdk->createInternetDocument();
    $result = $this->newpostsdk->getCounterparties();

    traced($result);

    die();

    $products = ManagerHolder::get('Product')->executeNativeSQL("SELECT id, bar_code FROM `product` WHERE `bar_code` LIKE '% %'");
    traced($products);

    foreach ($products as $product) {
      ManagerHolder::get('ParameterGroup')->updateById($product['id'], 'bar_code', trim($product['bar_code']));
    }

    die('f');
    foreach ($products as $product) {
      ManagerHolder::get('ParameterGroup')->updateById($product['id'], 'bar_code', trim($product['bar_code']));
    }

    die();
    $inventories = ManagerHolder::get('StoreInventory')->executeNativeSQL('SELECT id, product_id, store_id, product_code FROM store_inventory WHERE product_group_id IS NULL GROUP BY product_id, store_id, product_code');

    foreach ($inventories as $inventory) {
      $codeValid = ManagerHolder::get('Product')->existsWhere(array('id' => $inventory['product_id'], 'product_code' => $inventory['product_code']));

      if (!$codeValid) {
        $isDoubled = ManagerHolder::get('StoreInventory')->getCountWhere(array('product_id' => $inventory['product_id'], 'store_id' => $inventory['store_id']));
        if ($isDoubled > 1) {
          ManagerHolder::get('StoreInventory')->deleteById($inventory['id']);
        }

      }
    }

    die('DONE');
  }

  /**
   * Update zammler inventory
   */
  public function update_zammler_inventory() {
    $this->load->library('RetailCrmApi');

//    $offer = array();
//    $offer['externalId'] = '5765228837641';
//    $offer['stores'][] = array(
//      'code' => 'our-stock-zammler',
//      'available' => 5
//    );
//
//    $response = $this->retailcrmapi->storeInventoriesUpload(array($offer));
//    traced($response);


    $response = $this->retailcrmapi->ordersGet($_GET['order_id'], 'id');
    foreach($response->order['items'] as $item) {
      if (!isset($item['offer']['externalId'])) {
        continue;
      }
      $barCode = $item['offer']['externalId'];

      trace($item);
      $response = $this->retailcrmapi->storeInventories(array('details' => 1, 'offerExternalId' => $barCode));
      trace($response->offers);
    }


    die('OK');
  }

  /**
   * Export changes inventory
   * @param null $iteration
   */
  public function export_changes_inventory($iteration = NULL) {
    return;
    $this->load->library('RetailCrmApi');

    if ($iteration === NULL) {
      $iteration = 1;
    } else {
      $iteration++;
    }

    if ($iteration > 15) {
      die('FINISH BY ITERATION');
    }

    $changedInventoryIds = ManagerHolder::get('StoreInventory')->executeNativeSQL('SELECT id FROM store_inventory WHERE last_sync_at < updated_at OR last_sync_at IS NULL LIMIT 250');
    if (empty($changedInventoryIds)) {
      return TRUE;
    }

    $changedInventoryIds = get_array_vals_by_second_key($changedInventoryIds, 'id');
    $changes = ManagerHolder::get('StoreInventory')->getAllWhere(array('id' => $changedInventoryIds), 'e.*, store.code', 250);

    if (!empty($changes)) {
      $offers = array();
      foreach ($changes as $inventory) {
        if (empty($inventory['bar_code'])) {
          continue;
        }

        if ($inventory['qty'] < 0) {
          $inventory['qty'] = 0;
        }

        $offer = array();
        $offer['externalId'] = $inventory['bar_code'];
        $offer['stores'][] = array(
          'code' => $inventory['store']['code'],
          'available' => $inventory['qty']
        );

        $offers[] = $offer;
      }

      $response = $this->retailcrmapi->getClient('v4')->request->storeInventoriesUpload($offers);
      if ($response->isSuccessful()) {
        sleep(1);
        ManagerHolder::get('StoreInventory')->updateAllWhere(array('id' => $changedInventoryIds), array('last_sync_at' => date(DOCTRINE_DATE_FORMAT)));
        $this->export_changes_inventory($iteration);
      } else {

      }
    }
  }

  /**
   * Export changes inventory
   */
  public function full_inventory_export() {
    $this->load->library('RetailCrmApi');
    log_message('info', 'START full_inventory_export');
    $perPage = 1000;

    $pageCount = ManagerHolder::get('Product')->getTotalPageCount($perPage);
    $stores = ManagerHolder::get('Store')->getAsViewArray(array(), array('id' => 'code'));

    for($page = 1; $page <= $pageCount; $page++) {
      $products = ManagerHolder::get('Product')->getAllWhereWithMyPager(array(), $page, $perPage, 'e.*,parameter_groups.*,inventories.bar_code,inventories.store_id,inventories.qty');
      $products = $products->data;

      $list = array();
      foreach ($products as $k => $product) {
        $inventories = array();
        if (!empty($product['inventories'])) {
          foreach ($product['inventories'] as $inventory) {
            $inventories[$inventory['bar_code']][$inventory['store_id']] = $inventory;
          }
        }

        if (empty($product['parameter_groups'])) {
          $row = array();
          $row['bar_code'] = $product['bar_code'];
          if (isset($inventories[$product['bar_code']])) {
            $row['inventory'] = $inventories[$product['bar_code']];
          } else {
            $row['inventory'] = array();
          }

          $list[] = $row;
        }  else {
          foreach ($product['parameter_groups'] as $group) {
            $row = array();
            $row['bar_code'] = $group['bar_code'];
            if (isset($inventories[$group['bar_code']])) {
              $row['inventory'] = $inventories[$group['bar_code']];
            } else {
              $row['inventory'] = array();
            }

            $list[] = $row;
          }
        }
      }

      $rowCount = ceil(count($list) / 250);

      for($i = 0; $i < $rowCount; $i++) {
        $rows = array_slice($list, (250 * $i), 250);

        $offers = array();
        foreach ($rows as $row) {
          if (empty($row['bar_code'])) {
            continue;
          }

          $offer = array();
          $offer['externalId'] = $row['bar_code'];
          $offer['stores'] = array();
          foreach ($stores as $id => $code) {
            $qty = 0;
            if (isset($row['inventory'][$id])) {
              $qty = $row['inventory'][$id]['qty'];
            }
            if ($qty < 0) {
              $qty = 0;
            }

            $offer['stores'][] = array(
              'code' => $code,
              'available' => $qty
            );
          }
          $offers[] = $offer;
        }

        if (!count($offers)) {
          continue;
        }
        $response = $this->retailcrmapi->storeInventoriesUpload($offers);
        if (rand(0, 4) == 2) {
          sleep(1);
        }
      }
      log_message('info', 'Export ' . count($list) . ' offers');
    }

    log_message('info', 'Finish full_inventory_export');
    die('DONE');
  }

  /**
   * Get ICML catalog
   */
  public function generate_icml_catalog() {
    log_message('debug', 'START generate_icml_catalog');

    $shop = 'MammyClub';
    $date = new DateTime();
    $xmlstr = '<yml_catalog date="' . $date->format('Y-m-d H:i:s') . '"><shop><name>' . $shop . '</name></shop></yml_catalog>';
    $xml = new SimpleXMLElement($xmlstr);
    $categoriesXml = $xml->shop->addChild('categories', '');

    $categories = ManagerHolder::get('ProductCategory')->getWhere(array(), 'e.*');
    $categories = $this->processCategoryLoop($categories);

    $this->addCategoriesToIcml($categoriesXml, $categories);
    $offersXml = $xml->shop->addChild('offers', '');

    $products = ManagerHolder::get('Product')->getAll('e.*, parameter_groups.*, brand.*');
    $productsCatalog = array();

    foreach ($products as $k => $product) {
      if (empty($product['parameter_groups'])) {
        $productsCatalog[] = $product;
      } else {
        foreach ($product['parameter_groups'] as $group) {
          $p = $product;
          $p['bar_code'] = $group['bar_code'];
          if (!empty($group['price'])) {
            $p['price'] = $group['price'];
          }
          $p['offer_name'] =  $p['name'] . ' ' . $group['main_parameter_value']['name'];
          $productsCatalog[] = $p;
        }
      }
      unset($products[$k]);
    }

    foreach ($productsCatalog as $product) {
      $productXml = $offersXml->addChild('offer', '');

      $productXml->addAttribute('productId', 'p' . $product['id']);
      $productXml->addAttribute('id', $product['bar_code']);

      $productXml->addChild('categoryId', $product['category_id']);
      $productXml->addChild('price', $product['price']);
      if (isset($product['offer_name']) && !empty($product['offer_name'])) {
        $productXml->addChild('name', htmlspecialchars($product['offer_name']));
      } else {
        $productXml->addChild('name', htmlspecialchars($product['name']));
      }

      if (!$product['published'] || $product['not_in_stock']) {
        $productXml->addChild('productActivity', 'N');
      } else {
        $productXml->addChild('productActivity', 'Y');
      }

      $productXml->addChild('productName', htmlspecialchars($product['name']));
      if (!empty($product['cost_price'])) {
        $product['cost_price'] = str_replace(',', '.', $product['cost_price']);
        $product['cost_price'] = str_replace(' ', '', $product['cost_price']);
        $productXml->addChild('purchasePrice', (float)$product['cost_price']);
      }

      $productXml->addChild('vendor', htmlspecialchars($product['brand']['name']));

      $unitXml = $productXml->addChild('unit', '');
      $unitXml->addAttribute('code', 'pcs');
      $unitXml->addAttribute('name', 'Штука');
      $unitXml->addAttribute('sym', 'шт.');

      $articleXml = $productXml->addChild('param', htmlspecialchars($product['product_code']));
      $articleXml->addAttribute('name', 'Артикул');
      $articleXml->addAttribute('code', 'article');
    }

    file_put_contents('web/icml_file/our_catalog.xml', $xml->asXML());
    log_message('debug', 'FINISH generate_icml_catalog');
  }


  public function parse_inventory_from_web_new($parseOnlyMagBaby = FALSE) {
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    $loader = require_once BASEPATH . 'vendor/autoload.php';
    require_once BASEPATH . 'vendor/querypath/querypath/src/qp.php';
    log_message('debug', 'START parse_inventory_from_web');

    $dayAgo = date(DOCTRINE_DATE_FORMAT, (time() - (3600 * 12)));

    $settings = array();
    if (!$parseOnlyMagBaby) {
      $where = array('last_parse_at<' => $dayAgo, 'store_id' => 79, 'url<>' => '');
      $settings = ManagerHolder::get('StoreInventoryParserSetting')->getAllWhere($where, 'e.*,store.*,product.possible_parameters_id', 75);
    }
//    $magBabySettings = ManagerHolder::get('StoreInventoryParserSetting')->getAllWhere(array('last_parse_at<' => $dayAgo, 'store_id' => 29, 'url<>' => ''), 'e.*,store.*', 10);
//
//    $settings = array_merge($settings, $magBabySettings);
    $parseMagBaby = TRUE;
    foreach ($settings as $setting) {
      $sleep = 2;

      if ($setting['store']['code'] == 'Magbaby' && !$parseMagBaby) {
        continue;
      }

      if ($setting['store']['code'] == 'i-love-mum') {
        continue;
      }

      $webPage = $this->getWebPage($setting['url']);
      ManagerHolder::get('StoreInventoryParserSetting')->updateById($setting['id'], 'last_http_code', $webPage['header']['http_code']);

      if ($setting['store']['code'] == 'Magbaby' && (($webPage['header']['http_code'] != 200 && $webPage['header']['http_code'] != 404) || (!empty($webPage['header']['redirect_url'] && strpos($webPage['header']['redirect_url'], 'prom.ua') !== FALSE)))) {
        $webPage = $this->getWebPage($setting['url'], TRUE);

        if (($webPage['header']['http_code'] != 200 && $webPage['header']['http_code'] != 404) || (!empty($webPage['header']['redirect_url'] && strpos($webPage['header']['redirect_url'], 'prom.ua') !== FALSE))) {
          $parseMagBaby = FALSE;
          continue;
        } else {
          ManagerHolder::get('StoreInventoryParserSetting')->updateById($setting['id'], 'last_http_code', $webPage['header']['http_code']);
        }
      }

      $markup = $webPage['result'];

//      if ($setting['store']['code'] == 'Magbaby') {
//        $this->parse_inventory_magbaby($markup, $setting);
//        $sleep = 25;
//      }

      if ($setting['store']['code'] == 'i-love-mum') {
        $this->parse_inventory_i_love_mum($markup, $setting);
      }

      if ($setting['store']['code'] == 'White Rabbit') {
        $this->parse_inventory_white_rabbit($markup, $setting);
      }

      ManagerHolder::get('StoreInventoryParserSetting')->updateById($setting['id'], 'last_parse_at', date(DOCTRINE_DATE_FORMAT));
      sleep(rand($sleep, $sleep + 5));
    }
    ManagerHolder::get('StoreInventory')->updateProductStatuses();

    log_message('debug', 'FINISH parse_inventory_from_web');
  }

  /**
   * @param $markup
   * @param $setting
   */
  private function parse_inventory_magbaby($markup, $setting) {
    if (strpos($markup, '<span class="b-product__state b-product__state_type_available">В наличии</span>') !== FALSE) {
      $qty = 10;
    } else {
      $qty = 0;
    }
    if (!empty($setting['product_group_id'])) {
      $productGroup = ManagerHolder::get('ParameterGroup')->getById($setting['product_group_id'], 'bar_code');
      $barCode = $productGroup['bar_code'];
    } else {
      $product = ManagerHolder::get('Product')->getById($setting['product_id'], 'bar_code');
      $barCode = $product['bar_code'];
    }

    $where = array('product_id' => $setting['product_id'], 'bar_code' => $barCode, 'store_id' => $setting['store_id']);
    $exists = ManagerHolder::get('StoreInventory')->existsWhere($where);
    $entity = array();
    $entity['qty'] = $qty;
    if (!empty($setting['product_group_id'])) {
      $entity['product_group_id'] = $productGroup['id'];
    }
    $entity['update_by_admin_id'] = '';
    $entity['update_source'] = 'web';
    $entity['updated_at'] = date(DOCTRINE_DATE_FORMAT);
    if ($exists) {
      ManagerHolder::get('StoreInventory')->updateAllWhere($where, $entity);
    } else {
      $entity = array_merge($entity, $where);
      ManagerHolder::get('StoreInventory')->insert($entity);
    }
  }

  /**
   * @param $markup
   * @param $setting
   */
  private function parse_inventory_i_love_mum($markup, $setting) {
    $params = array();

    $possibleParams = ManagerHolder::get('ParameterProduct')->getById($setting['product']['possible_parameters_id'], 'possible_parameter_values.*');

    if (strpos($markup, '<li>Доступность: На складе</li>') !== FALSE) {
      $html5 = new HTML5();
      $dom = $html5->loadHTML($markup);

      foreach(qp($dom, 'h3:contains(Доступные опции)')->next('div')->children('div')->first()->children('div') as $item) {
//      traced(qp($item)->html());
        //      print "\n";

        $param = qp($item)->find('label')->text();
        $param = trim($param);

        foreach ($possibleParams['possible_parameter_values'] as $possibleParam) {
          if ($possibleParam['name'] == $param) {
            $param = $possibleParam;
          }
        }
//        $param = ManagerHolder::get('ParameterValue')->getOneWhere(array('name' => $param), 'e.*');

        $value = qp($item)->find('label')->attr('title');
        $value = trim($value);

        if ($value == 'нет в наличии') {
          $qty = 0;
        } else {
          preg_match("/в наличии ([0-9]*) шт./", $value, $mch);
          $qty = $mch[1];
        }

        if (isset($param['id'])) {
          $params[$param['id']] = $qty;
        }
      }
    } else {
      $productGroups = ManagerHolder::get('ParameterGroup')->getAllWhere(array('product_id' => $setting['product_id']), 'main_parameter_value_id');
      foreach ($productGroups as $pg) {
        $params[$pg['main_parameter_value_id']] = 0;
      }
    }

    if (!empty($params)) {
      $this->parse_inventory_process_param_list($params, $setting);
    }
  }

  /**
   * @param $markup
   * @param $setting
   */
  private function parse_inventory_white_rabbit($markup, $setting) {
    $html5 = new HTML5();
    $dom = $html5->loadHTML($markup);

    foreach(qp($dom, '.variations')->find('select')->children('option') as $item) {
      if (qp($item)->attr('value') == '') {
        continue;
      }
      $param = qp($item)->text();
      $param = ManagerHolder::get('ParameterValue')->getOneWhere(array('name' => $param), 'e.*');
      $qty = 5;

      $params[$param['id']] = $qty;
    }

    if (!empty($params)) {
      $this->parse_inventory_process_param_list($params, $setting);
    }
  }

  /**
   * @param $params
   * @param $setting
   */
  private function parse_inventory_process_param_list($params, $setting) {
    $zeroUpdate = array();
    $zeroUpdate['update_by_admin_id'] = '';
    $zeroUpdate['update_source'] = 'web';
    $zeroUpdate['qty'] = 0;
    $zeroUpdate['updated_at'] = date(DOCTRINE_DATE_FORMAT);
    ManagerHolder::get('StoreInventory')->updateAllWhere(array('product_id' => $setting['product_id'], 'store_id' => $setting['store_id']), $zeroUpdate);
    foreach ($params as $paramId => $qty) {
      $productGroup = ManagerHolder::get('ParameterGroup')->getOneWhere(array('product_id' => $setting['product_id'], 'main_parameter_value_id' => $paramId), 'bar_code');
      if (!$productGroup) {
        continue;
      }

      $where = array('product_id' => $setting['product_id'], 'bar_code' => $productGroup['bar_code'], 'store_id' => $setting['store_id']);
      $exists = ManagerHolder::get('StoreInventory')->existsWhere($where);
      $entity = array();
      $entity['qty'] = $qty;
      $entity['product_group_id'] = $productGroup['id'];
      $entity['update_by_admin_id'] = '';
      $entity['update_source'] = 'web';
      $entity['updated_at'] = date(DOCTRINE_DATE_FORMAT);
      if ($exists) {
        ManagerHolder::get('StoreInventory')->updateAllWhere($where, $entity);
      } else {
        $entity = array_merge($entity, $where);
        ManagerHolder::get('StoreInventory')->insert($entity);
      }
    }
  }

  /**
   * Parse inventory from web
   */
  public function parse_inventory_from_web() {
    require_once BASEPATH . 'phpQuery/phpQuery.php';
    log_message('debug', 'START parse_inventory_from_web');

    $dayAgo = date(DOCTRINE_DATE_FORMAT, (time() - (3600 * 12)));
    $settings = ManagerHolder::get('StoreInventoryParserSetting')->getAllWhere(array('last_parse_at<' => $dayAgo, 'url<>' => ''), 'e.*,store.*', 200);

    foreach ($settings as $setting) {
      $webPage = $this->getWebPage($setting['url']);
      ManagerHolder::get('StoreInventoryParserSetting')->updateById($setting['id'], 'last_http_code', $webPage['header']['http_code']);

      $markup = $webPage['result'];

      if ($setting['store']['code'] == 'Magbaby') {
        if (strpos($markup, '<span class="b-product__state b-product__state_type_available">В наличии</span>') !== FALSE) {
          $qty = 10;
        } else {
          $qty = 0;
        }
        if (!empty($setting['product_group_id'])) {
          $productGroup = ManagerHolder::get('ParameterGroup')->getById($setting['product_group_id'], 'bar_code');
          $barCode = $productGroup['bar_code'];
        } else {
          $product = ManagerHolder::get('Product')->getById($setting['product_id'], 'bar_code');
          $barCode = $product['bar_code'];
        }

        $where = array('product_id' => $setting['product_id'], 'bar_code' => $barCode, 'store_id' => $setting['store_id']);
        $exists = ManagerHolder::get('StoreInventory')->existsWhere($where);
        $entity = array();
        $entity['qty'] = $qty;
        if (!empty($setting['product_group_id'])) {
          $entity['product_group_id'] = $productGroup['id'];
        }
        $entity['update_by_admin_id'] = '';
        $entity['update_source'] = 'web';
        $entity['updated_at'] = date(DOCTRINE_DATE_FORMAT);
        if ($exists) {
          ManagerHolder::get('StoreInventory')->updateAllWhere($where, $entity);
        } else {
          $entity = array_merge($entity, $where);
          ManagerHolder::get('StoreInventory')->insert($entity);
        }

        ManagerHolder::get('StoreInventoryParserSetting')->updateById($setting['id'], 'last_parse_at', date(DOCTRINE_DATE_FORMAT));
      }

      if ($setting['store']['code'] == 'i-love-mum') {
        $markup = file_get_contents($setting['url']);

        $params = array();
        if (strpos($markup, '<li>Доступность: На складе</li>') !== FALSE) {
          @phpQuery::newDocumentHTML($markup);

          foreach(pq('h3:contains(Доступные опции)')->next('div')->find('div:first > div') as $item) {
    //      print pq($item)->html();
    //      print "\n";

            $param = pq($item)->find('label')->text();
            $param = trim($param);
            $param = ManagerHolder::get('ParameterValue')->getOneWhere(array('name' => $param), 'e.*');

            $value = pq($item)->find('label')->attr('title');
            $value = trim($value);
            if ($value == 'нет в наличии') {
              $qty = 0;
            } else {
              preg_match("/в наличии ([0-9]*) шт./", $value, $mch);
              $qty = $mch[1];
            }

            $params[$param['id']] = $qty;
          }
        } else {
          $productGroups = ManagerHolder::get('ParameterGroup')->getAllWhere(array('product_id' => $setting['product_id']), 'main_parameter_value_id');
          foreach ($productGroups as $pg) {
            $params[$pg['main_parameter_value_id']] = 0;
          }
        }

        ManagerHolder::get('StoreInventory')->updateAllWhere(array('product_id' => $setting['product_id'], 'store_id' => $setting['store_id']), array('qty' => 0));
        foreach ($params as $paramId => $qty) {
          $productGroup = ManagerHolder::get('ParameterGroup')->getOneWhere(array('product_id' => $setting['product_id'], 'main_parameter_value_id' => $paramId), 'bar_code');
          if (!$productGroup) {
            continue;
          }

          $where = array('product_id' => $setting['product_id'], 'bar_code' => $productGroup['bar_code'], 'store_id' => $setting['store_id']);
          $exists = ManagerHolder::get('StoreInventory')->existsWhere($where);
          $entity = array();
          $entity['qty'] = $qty;
          $entity['product_group_id'] = $productGroup['id'];
          $entity['update_by_admin_id'] = '';
          $entity['update_source'] = 'web';
          $entity['updated_at'] = date(DOCTRINE_DATE_FORMAT);
          if ($exists) {
            ManagerHolder::get('StoreInventory')->updateAllWhere($where, $entity);
          } else {
            $entity = array_merge($entity, $where);
            ManagerHolder::get('StoreInventory')->insert($entity);
          }

          ManagerHolder::get('StoreInventoryParserSetting')->updateById($setting['id'], 'last_parse_at', date(DOCTRINE_DATE_FORMAT));
        }
      }
      sleep(rand(1,3));
    }

    ManagerHolder::get('StoreInventory')->updateProductStatuses();
    log_message('debug', 'FINISH parse_inventory_from_web');
  }

  /**
   * Zammler inventory
   */
  public function zammler_inventory() {
    $this->load->library('RetailCrmApi');
    $perPage = 100;

    $result = $this->retailcrmapi->ordersList(array('extendedStatus' => array('need', 'assembling-complete', 'send-to-delivery')), 1, $perPage);
    $orders = $result->orders;
    $data = array();
    $this->getItemsQtyFromOrders($data, $orders);

    $pagination = $result->pagination;
    for($i = ($pagination['currentPage'] + 1); $i <= $pagination['totalPageCount']; $i++) {
      $result = $this->retailcrmapi->ordersList(array('extendedStatus' => array('need')), $i, $perPage);
      $orders = $result->orders;
      $this->getItemsQtyFromOrders($data, $orders);
    }

    traced($data);
  }

  public function string_return() {
    return 'I returned a string. Cakes and Pies!';
  }

  public function order_create_by_crm() {
    $this->load->library('RetailCrmApi');
    log_message('debug', 'ORDER SYNC DATA:' . print_r($_GET, true));

    if (!isset($_GET['order_id'])) {
      show_404();
    }

    $response = $this->retailcrmapi->ordersGet($_GET['order_id'], 'id');
    $order = $response->order;


  }

  /**
   * Order sync
   */
  public function order_sync()
  {
    $this->load->library('RetailCrmApi');
    log_message('debug', 'ORDER SYNC DATA:' . print_r($_GET, true));

    if (!isset($_GET['order_id'])) {
      show_404();
    }

    $response = $this->retailcrmapi->getClient('v5')->request->ordersGet($_GET['order_id'], 'id');
    $order = $response->order;

    $user = $this->retailcrmapi->getClient('v4')->request->usersGet($order['managerId']);
    $user = $user->user;
    $admin = ManagerHolder::get('Admin')->getOneWhere(array('email' => $user['email']), 'id');
    $status = ManagerHolder::get('SiteOrderStatus')->getOneWhere(array('k' => $order['status']), 'e.*');
    $store = array();
    if (isset($order['shipmentStore'])) {
      $store = ManagerHolder::get('Store')->getOneWhere(array('code' => $order['shipmentStore']), 'e.*');
    }

    if (!empty($order['items'])) {
      $items = array();
      foreach ($order['items'] as $item) {
        $items[$item['offer']['externalId']]['qty'] = $item['quantity'];
        $items[$item['offer']['externalId']]['price'] = $item['initialPrice'];
        $items[$item['offer']['externalId']]['discountTotal'] = $item['discountTotal'];
      }
    }

    $orderId = str_replace('TEST', '', $order['externalId']);
    $cart = ManagerHolder::get('Cart')->getOneWhere(array('siteorder_id' => $orderId), 'e.*');
    $cartItems = ManagerHolder::get('CartItem')->getAllWhere(array('cart_id' => $cart['id']), 'e.*, product.*, parameter_group.*');

    foreach ($cartItems as $cartItem) {
      $barCode = NULL;
      if (!empty($cartItem['parameter_group'])) {
        $barCode = $cartItem['parameter_group']['bar_code'];
      } else {
        $barCode = $cartItem['product']['bar_code'];
      }
      if (isset($items[$barCode])) {
        $itemTotal = $items[$barCode]['qty'] * $items[$barCode]['price'] - $items[$barCode]['discountTotal'];
        ManagerHolder::get('CartItem')->updateById($cartItem['id'], 'item_total', $itemTotal);

        if ($items[$barCode]['qty'] != $cartItem['qty']) {
          ManagerHolder::get('CartItem')->updateById($cartItem['id'], 'qty', $items[$barCode]['qty']);
        }
      } else {
        ManagerHolder::get('CartItem')->deleteById($cartItem['id']);
      }
      unset($items[$barCode]);
    }

    if (!empty($items)) {
      foreach ($items as $barCode => $item) {
        $cartItem = array();
        $cartItem['cart_id'] = $cart['id'];

        $productGroup = ManagerHolder::get('ParameterGroup')->getOneWhere(array('bar_code' => $barCode), 'e.*');
        if (empty($productGroup)) {
          $product = ManagerHolder::get('Product')->getOneWhere(array('bar_code' => $barCode), 'e.*');
        } else {
          $product = ManagerHolder::get('Product')->getById($productGroup['product_id'], 'e.*');
          $cartItem['parameter_group_id'] = $productGroup['id'];
        }

        $cartItem['product_id'] = $product['id'];
        $itemTotal = $item['qty'] * $item['price'] - $item['discountTotal'];
        $cartItem['item_total'] = $itemTotal;
        $cartItem['qty'] = $item['qty'];

        // Get inventories data
        $inventoriesWhere = array();
        $inventoriesWhere['product_id'] = $cartItem['product_id'];
        if (!empty($cartItem['parameter_group_id'])) {
          $inventoriesWhere['product_group_id'] = $cartItem['parameter_group_id'];
        }
        $inventories = ManagerHolder::get('StoreInventory')->getAllWhere($inventoriesWhere, 'store_id, qty');
        $cartItem['zammler_inventory_qty'] = 0;
        $cartItem['other_stores_inventory_qty'] = 0;
        if (!empty($inventories)) {
          foreach ($inventories as $inventory) {
            if ($inventory['store_id'] == ZAMMLER_STORE_ID) {
              $cartItem['zammler_inventory_qty'] = $inventory['qty'];
            } else {
              $cartItem['other_stores_inventory_qty'] += $inventory['qty'];
            }
          }
        }

        ManagerHolder::get('CartItem')->insert($cartItem);
      }
    }

    $ourOrder = array();

    $ourOrder['fio'] = $order['lastName'] . ' ' . $order['firstName'] . ' ' . $order['patronymic'];
    $ourOrder['email'] = $order['email'];
    $ourOrder['phone'] = '+3' . $order['phone'];
    $ourOrder['order_type'] = $order['orderType'];

    if (isset($order['delivery']['data']['pickuppointId'])) {
      $ourOrder['delivery_post'] = $order['delivery']['data']['pickuppoint'];
      $ourOrder['delivery_post_ref'] = $order['delivery']['data']['pickuppointId'];
    }

    if (isset($order['payments']) && !empty($order['payments'])) {
      $payment = reset($order['payments']);
      if ($payment['type'] == 'bank-card') {
        $ourOrder['payment_type'] = 'privatbank';
      } else {
        $ourOrder['payment_type'] = 'cash';
      }
    }

    if (!empty($status)) {
      $ourOrder['siteorder_status_id'] = $status['id'];
    }
    if (!empty($store)) {
      $ourOrder['shipment_store_id'] = $store['id'];
    }
    if (isset($order['shipmentDate'])) {
      $ourOrder['shipment_date'] = $order['shipmentDate'];
    }
    if (!empty($admin)) {
      $ourOrder['manager_id'] = $admin['id'];
    }
    $ourOrder['total_with_discount'] = $order['totalSumm'];

    $order['externalId'] = str_replace('TEST', '', $order['externalId']);
    $ourOrder['id'] = $order['externalId'];
    ManagerHolder::get('SiteOrder')->needSync = FALSE;
    ManagerHolder::get('SiteOrder')->update($ourOrder);
    die('OK');
  }

  /**
   * Get web page
   * @param $url
   * @param bool $useProxy
   * @return array
   */
  private function getWebPage($url, $useProxy = FALSE) {
    $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    if ($useProxy) {
      curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
      $proxy = $this->getProxy();
      curl_setopt($ch, CURLOPT_PROXY, $proxy);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    }

    $result = curl_exec($ch);
    $header  = curl_getinfo($ch);

    if ($useProxy && $result === false) {
      echo $proxy . '<br>';
      echo "Proxy is not working: " . curl_error($ch) . '<br>';
    }

    if ($useProxy && $result !== false) {
      echo $proxy . '<br>';
      echo "Proxy is working";
    }

    trace($header);

    curl_close($ch);

    return array('header' => $header, 'result' => $result);
  }

  /**
   * Get proxy
   */
  private function getProxy() {
    $proxyList = array(
      '78.187.73.210:8080',
      '37.186.201.151:8080',
      '31.25.141.148:8080'
    );

    $proxy = $proxyList[$this->proxyIndex];

    if ($this->proxyIndex < count($proxyList) - 1) {
      $this->proxyIndex++;
    } else {
      $this->proxyIndex = 0;
    }

    return $proxy;
  }

  /**
   * Get Items Qty From Orders
   * @param $data
   * @param $orders
   */
  private function getItemsQtyFromOrders(&$data, $orders) {
      foreach ($orders as $order) {
        if ($order['status'] != 'need' && $order['shipmentStore'] != 'our-stock-zammler') {
          continue;
        }

        foreach ($order['items'] as $item) {
          if ($order['status'] == 'need' && $item['status'] != 'in-reserve') {
            continue;
          }
          if ($order['status'] != 'need' && $item['status'] != 'ready-to-assembly') {
            continue;
          }
          if (!isset($item['offer']['externalId'])) {
            continue;
          }
          $barCode = $item['offer']['externalId'];

          if (isset($data[$barCode])) {
            $data[$barCode] += $item['quantity'];
          } else {
            $data[$barCode] = $item['quantity'];
          }
        }
      }
  }


  /**
   * Add categories to icml
   * @param $categoriesXml
   * @param $categories
   */
  private function addCategoriesToIcml($categoriesXml, $categories) {
    foreach ($categories as $category) {
      $categoryXml = $categoriesXml->addChild('category', $category['name']);
      $categoryXml->addAttribute('id', $category['id']);
      if ($category['level'] > 0) {
        $categoryXml->addAttribute('parentId', $category['root_id']);
      }

      if (!empty($category['__children'])) {
        $this->addCategoriesToIcml($categoriesXml, $category['__children']);
      }
    }

  }

  /**
   * processCategoryLoop
   * @param array $categories
   * @return array
   */
  private function processCategoryLoop($categories) {
    if(!empty($categories)) {
      foreach ($categories as $k => $v) {
        if (!$v['published']) {
          unset($categories[$k]);
          continue;
        }
        if(!empty($v['__children'])) {
          $categories[$k]['__children'] = $this->processCategoryLoop($v['__children']);
        }
      }
    }
    return $categories;
  }

}