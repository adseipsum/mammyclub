<?php if (!defined("BASEPATH")) exit("No direct script access allowed");


require_once APPPATH . 'logic/events/base/BaseEvent.php';


class SMSEvents extends BaseEvent {

  protected $logging = TRUE;

  /**
   * Send
   * @param $data
   * @return mixed
   */
  public function afterSend($data) {
    //TODO is_success logic
    $result = $data['response'];

    return array('is_success' => TRUE, 'result' => $result, 'search_field_value' => $data['to']);
  }

  /**
   * @param $data
   * @return bool
   */
  public function sendMessage($data) {
    ManagerHolder::get('SMS')->sendMessage($data['to'], $data['message'], TRUE);

    return TRUE;
  }


}