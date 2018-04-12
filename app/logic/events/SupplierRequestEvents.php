<?php if (!defined("BASEPATH")) exit("No direct script access allowed");


require_once APPPATH . 'logic/events/base/BaseEvent.php';

class SupplierRequestEvents extends BaseEvent {

  protected $logging = TRUE;

  /**
   * Change status
   * @param $change
   * @return bool
   */
  public function changeStatus($change) {
    if (isset($change['from']) && isset($change['to'])) {
//      $siteOrder = ManagerHolder::get('SiteOrder')->getById($change['id'], 'email');

      ManagerHolder::get('SupplierRequest')->processStatusChange($change['id']);

      if (!empty($change['to']['alert_str_time'])) {
        ManagerHolder::get('TaskSchedule')->createOneTimeEvent('SupplierRequest.statusAlert', array('id' => $change['id'], 'status' => $change['to']['k']), $change['to']['alert_str_time'], 'on_not_success', $change['to']['admin_notification_template_id']);
      }

      return TRUE;
    }

    return FALSE;
  }

  /**
   * @param $data
   * @return bool
   */
  public function statusAlert($data) {
    $supplierRequest = ManagerHolder::get('SupplierRequest')->getById($data['id'], 'supplier_request_status.k');

    if ($data['status'] == $supplierRequest['supplier_request_status']['k']) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

}