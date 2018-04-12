<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

require_once APPPATH . 'logic/events/base/BaseEvent.php';

class RetailCRMEvents extends BaseEvent {

  protected $logging = TRUE;

  /**
   * @param $data
   * @return bool
   */
  public function syncStatuses($data) {
    $CI = &get_instance();
    $CI->load->library('RetailCrmApi');

    $ourStatuses = ManagerHolder::get('SiteOrderStatus')->getAsViewArray(array(), array('k' => 'name'), NULL, array('published' => TRUE));

    $response = $CI->retailcrmapi->getClient()->request->statusesList();
    $statusesToEdit = array();
    foreach($response->statuses as $status) {
      $code = $status['code'];

      if ($code == 'deleted') {
        continue;
      }

      if (!isset($ourStatuses[$code])) {
        $data = $status;
        $data['active'] = FALSE;
      } elseif ($ourStatuses[$code] != $status['name']) {
        $data = $status;
        $data['name'] = $ourStatuses[$code];
        $data['active'] = TRUE;
        unset($ourStatuses[$code]);
      } elseif (!$status['active']) {
        $data = $status;
        $data['active'] = TRUE;
        unset($ourStatuses[$code]);
      } else {
        unset($ourStatuses[$code]);
        continue;
      }
      $statusesToEdit[] = $data;
    }

    foreach ($statusesToEdit as $data) {
      $response = $CI->retailcrmapi->getClient()->request->statusesEdit($data);
    }
    foreach ($ourStatuses as $code => $name) {
      $data = array();
      $data['code'] = $code;
      $data['name'] = $name;
      $data['active'] = TRUE;
      $data['ordering'] = 99;
      $data['group'] = 'in-work';
      $response = $CI->retailcrmapi->getClient()->request->statusesEdit($data);
    }

    return TRUE;
  }
}