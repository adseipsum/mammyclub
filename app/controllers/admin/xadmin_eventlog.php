<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_EventLog
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_EventLog extends Base_Admin_Controller {

  /** Filters. */
  protected $filters = array("change_by" => "");


  /**
   * Site order
   */
  public function siteorder() {
    $where = array('event_model' => 'SiteOrder', 'event_method' => 'changeStatus');
    if (isset($_GET['siteorder_id']) && !empty($_GET['siteorder_id'])) {
      $where['entity_id'] = $_GET['siteorder_id'];
    }

    $eventLogs = ManagerHolder::get('EventLog')->getAllWhere($where, 'e.*, admin.*');
    $siteOrderIds = get_array_vals_by_second_key($eventLogs, 'entity_id');
    $siteOrders = ManagerHolder::get('SiteOrder')->getAsViewArray(array(), array('id' => 'code'), NULL, array('id' => $siteOrderIds));

    foreach ($eventLogs as $k => $v) {
      $eventLogs[$k]['data'] = json_decode($v['data'], TRUE);
    }

    $this->layout->setLayout('ajax');
    $this->layout->set('siteOrders', $siteOrders);
    $this->layout->set('eventLogs', $eventLogs);
    $this->layout->view('eventlog/siteorder');
  }


  /**
   * Supplier request
   */
  public function supplier_request() {
    $where = array('event_model' => 'SupplierRequest');
    if (isset($_GET['supplier_request_id']) && !empty($_GET['supplier_request_id'])) {
      $where['entity_id'] = $_GET['supplier_request_id'];
    }

    $eventLogs = ManagerHolder::get('EventLog')->getAllWhere($where, 'e.*, admin.*');
    $supplierRequestIds = get_array_vals_by_second_key($eventLogs, 'entity_id');
    $supplierRequests = ManagerHolder::get('SupplierRequest')->getAsViewArray(array(), array('id' => 'code'), NULL, array('id' => $supplierRequestIds));

    foreach ($eventLogs as $k => $v) {
      $eventLogs[$k]['data'] = json_decode($v['data'], TRUE);
    }

    $this->layout->setLayout('ajax');
    $this->layout->set('supplierRequests', $supplierRequests);
    $this->layout->set('eventLogs', $eventLogs);
    $this->layout->view('eventlog/supplier_request');
  }

  /**
   * SMS
   */
  public function sms() {
    $where = array('event_model' => 'SMS', 'event_method' => 'afterSend');

    if (isset($_GET['phone']) && !empty($_GET['phone'])) {
      $where['search_field_value'] = '+' . trim($_GET['phone']);
    }

    $eventLogs = ManagerHolder::get('EventLog')->getAllWhere($where, 'e.*');
    foreach ($eventLogs as $k => $v) {
      $eventLogs[$k]['data'] = json_decode($v['data'], TRUE);
    }

    $this->layout->setLayout('ajax');
    $this->layout->set('eventLogs', $eventLogs);
    $this->layout->view('eventlog/sms');
  }

	/**
	 * Insert and delete items from SiteOrder
	 */
	public function itemAction() {
		$where = array('event_model' => 'SiteOrder', 'event_method' => 'itemAction');
		if (isset($_GET['siteorder_id']) && !empty($_GET['siteorder_id'])) {
			$where['entity_id'] = $_GET['siteorder_id'];
		}

		$eventLogs = ManagerHolder::get('EventLog')->getAllWhere($where, 'e.*, admin.*');
		$siteOrders = ManagerHolder::get('SiteOrder')->getAsViewArray(array(), array('id' => 'code'), NULL, array('id' => $_GET['siteorder_id']));

		foreach ($eventLogs as $k => $v) {
			$eventLogs[$k]['data'] = json_decode($v['data'], TRUE);
		}

		$this->layout->setLayout('ajax');
		$this->layout->set('siteOrders', $siteOrders);
		$this->layout->set('eventLogs', $eventLogs);
		$this->layout->view('eventlog/item_action');
	}

	public function shipmentDateAction() {

		$where = array('event_model' => 'SiteOrder', 'event_method' => 'shipmentDateAction');
		if (isset($_GET['siteorder_id']) && !empty($_GET['siteorder_id'])) {
			$where['entity_id'] = $_GET['siteorder_id'];
		}

		ManagerHolder::get('EventLog')->setOrderBy('created_at DESC');
		$eventLogs = ManagerHolder::get('EventLog')->getAllWhere($where, 'e.*, admin.*');
		$siteOrders = ManagerHolder::get('SiteOrder')->getAsViewArray(array(), array('id' => 'code'), NULL, array('id' => $_GET['siteorder_id']));

		foreach ($eventLogs as $k => $v) {
			$eventLogs[$k]['data'] = json_decode($v['data'], TRUE);
		}

		$this->layout->setLayout('ajax');
		$this->layout->set('siteOrders', $siteOrders);
		$this->layout->set('eventLogs', $eventLogs);
		$this->layout->view('eventlog/shipment_date_action');

	}

}