<?php


class SiteOrderEvents {

  protected $logging = TRUE;

  /**
   * Create
   * @param $data
   */
  public function create($data) {
    $status = ManagerHolder::get('SiteOrderStatus')->getById($data['siteorder_status_id'], 'e.*');

    if (!empty($status['alert_str_time'])) {
      ManagerHolder::get('TaskSchedule')->createOneTimeEvent('SiteOrder.statusAlert', array('id' => $data['id'], 'status' => $status['k']), $status['alert_str_time'], 'on_not_success', $status['admin_notification_template_id']);
    }
  }

  /**
   * @param $data
   * @return bool
   */
  public function itemAction($data) {

    return TRUE;
  }

	/**
	 * @param $data
	 * @return bool
	 */
	public function shipmentDateAction($data) {
	  if (!isset($data['code'])) {
      $siteOrderCode = ManagerHolder::get('SiteOrder')->getById($data['id'],'code');
      $data['code'] = $siteOrderCode['code'];
    }
		if (!empty($data) && isset($data['code'])) {
			ManagerHolder::get('SupplierRequestProductParameterGroup')->updateWhere(array('siteorder_code' => $data['code']), 'shipment_date', $data['shipment_date']);
		}
		return TRUE;

	}

  /**
   * Change status
   * @param $change
   * @return bool
   */
  public function changeStatus($change) {
//    echo 'triggered';
//    trace($change);

    if (isset($change['from']) && isset($change['to'])) {
//      $siteOrder = ManagerHolder::get('SiteOrder')->getById($change['id'], 'email');

      ManagerHolder::get('SiteOrder')->processStatusChange($change['id']);

      if (!empty($change['to']['alert_str_time'])) {
        ManagerHolder::get('TaskSchedule')->createOneTimeEvent('SiteOrder.statusAlert', array('id' => $change['id'], 'status' => $change['to']['k']), $change['to']['alert_str_time'], 'on_not_success', $change['to']['admin_notification_template_id']);
      }

      if ($change['to']['k'] == 'delivering') {
        ManagerHolder::get('SiteOrder')->updateById($change['id'], 'shipment_date', date(DOCTRINE_DATE_FORMAT));
      }
      if ($change['to']['k'] == 'complete') {
	      ManagerHolder::get('SiteOrder')->updateById($change['id'], 'complete_status_date', date(DOCTRINE_DATE_FORMAT));
      }

//      if ($change['to']['k'] == 'client-confirmed') {
//        ManagerHolder::get('OrderConfirmedBroadcast')->sendBySiteOrder($change['id']);
//      }

      return TRUE;
    }

    return FALSE;
  }

  /**
   * @param $data
   * @return bool
   */
  public function statusAlert($data) {
    $siteOrder = ManagerHolder::get('SiteOrder')->getById($data['id'], 'siteorder_status.k, paid');

    if ($data['status'] == $siteOrder['siteorder_status']['k']) {
      if ($siteOrder['siteorder_status']['k'] == 'complete' && $siteOrder['paid']) {
        return TRUE;
      }

      return FALSE;
    } else {
      return TRUE;
    }
  }

}