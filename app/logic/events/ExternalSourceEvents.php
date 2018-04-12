<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

require_once APPPATH . 'logic/events/base/BaseEvent.php';

use NovaPoshta\ApiModels\TrackingDocument;

class ExternalSourceEvents extends BaseEvent {

  protected $logging = TRUE;


  public function processMkarapuzPriceFile($data) {
    $xml = file_get_contents('http://www.mkarapuz.com.ua/price/quantity.xml');
    $xml = new SimpleXMLElement($xml);
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
      $productUpdate['cost_price'] = str_replace('.',',', $item->price);
      ManagerHolder::get('Product')->updateAllWhere(array('id' => $product['id']), $productUpdate);
    }
    ManagerHolder::get('StoreInventory')->updateProductStatuses();

    return TRUE;
  }

  /**
   * newpost_sync_statuses
   */
  public function newpostSyncOrderStatuses($values) {
    $CI = &get_instance();
    $CI->load->library('NewPostSdk');

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
    $updatedOrders = 0;

    foreach($ttns as $ttn) {
      $refs = $ttn;

      $data = new \NovaPoshta\MethodParameters\InternetDocument_documentsTracking();
      $data->setDocuments($refs);

      $result = TrackingDocument::getStatusDocuments($data);

      ManagerHolder::get('ExternalSourceResponseLog')->trackingNpResponse('SiteOrder', $result);

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
          if ($newPostStatusCode == 102 || $newPostStatusCode == 103 || $newPostStatusCode == 108) {
            $newStatusCode = 'dont-pick-up';
          }
          if ($newPostStatusCode == 2) {
            $newStatusCode = 'ttn-deleted';
          }
          if ($newPostStatusCode == 104) {
            ManagerHolder::get('SiteOrder')->updateWhere(array('ttn_code' => $newPostTtn), 'ttn_code', $data->LastCreatedOnTheBasisNumber);
            continue;
          }


          if (!empty($newStatusCode)) {
            $newStatusId = $statusMap[$newStatusCode];
            $siteOrder = ManagerHolder::get('SiteOrder')->getOneWhere(array('ttn_code' => $newPostTtn), 'e.*');

            if ($siteOrder['siteorder_status_id'] != $newStatusId) {
              ManagerHolder::get('SiteOrder')->updateById($siteOrder['id'], 'siteorder_status_id', $newStatusId);
              $updatedOrders++;
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

    return array('is_success' => TRUE, 'result' => $updatedOrders);
  }

  /**
   * newpost_sync_statuses
   */
  public function newpostSyncOrderSupplierRequests($values) {
    $CI = &get_instance();
    $CI->load->library('NewPostSdk');

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
    $updatedCount = 0;

    foreach($ttns as $ttn) {
      $refs = $ttn;

      $data = new \NovaPoshta\MethodParameters\InternetDocument_documentsTracking();
      $data->setDocuments($refs);

      $result = TrackingDocument::getStatusDocuments($data);

	    ManagerHolder::get('ExternalSourceResponseLog')->trackingNpResponse('SupplierRequest', $result);

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
              $updatedCount++;
            }
          }
        }
      }
    }

    return array('is_success' => TRUE, 'result' => $updatedCount);
  }

  /**
   * newpostSync
   * @param $data
   * @return bool
   */
  public function newpostSync($data) {

	  ManagerHolder::get('City')->sync();
	  ManagerHolder::get('WarehouseType')->sync();
	  ManagerHolder::get('Warehouse')->sync();
	  ManagerHolder::get('CounterpartyContactPerson')->sync();
	  ManagerHolder::get('CounterpartyAddress')->sync();
	  ManagerHolder::get('Counterparty')->sync();


	  return TRUE;
  }

  /**
   * updateFacebookAudiences
   */
  public function updateFacebookAudiences()
  {
    log_message('debug', '[updateFacebookAudiences] - Started');

    $audiences = ManagerHolder::get('FacebookAudience')->getAll('e.*');
    $update = array();
    foreach ($audiences as $audience) {
      $update['id'] = $audience['id'];
      if (!empty($audience['filter_type'])) {
        $update['filter_type'] = $audience['filter_type'];
      } else {
        $update['filter_type'] = 'user';
      }
      if (!empty($audience['communication_type'])) {
        $update['communication_type'] = $audience['communication_type'];
      } else {
        $update['communication_type'] = 'email';
      }

      try {
        log_message('debug', '[updateFacebookAudiences] - Processing audience: ' . print_r($update, true));
        ManagerHolder::get('FacebookAudience')->updateUsersFromFacebookAudiences($update['id'], null, null, $update['filter_type'], $update['communication_type']);
      } catch (Exception $e) {
        log_message('error', '[updateFacebookAudiences] - exception: ' . $e->getMessage());
      }
    }

    log_message('debug', '[updateFacebookAudiences] - Finished');
    return TRUE;
  }

}