<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Alpha SMS manager
 */
class SMSManager {


  /**
   * Send message
   * @param string $to
   * @param string $message
   * @param bool $sendNow
   * @return bool
   */
  public function sendMessage($to = NULL, $message = NULL, $sendNow = FALSE) {
    $currentHour = date('G');

    $startSending = ManagerHolder::get('Settings')->getValByKey('sms_sending_hour_from');
    $endSending = ManagerHolder::get('Settings')->getValByKey('sms_sending_hour_to');

    if (($currentHour >= $startSending && $currentHour < $endSending) || $sendNow) {
      $response = ManagerHolder::get('AlphaSMS')->sendMessage($to, $message);

      Events::trigger('SMS.afterSend', array('to' => $to, 'message' => $message, 'response' => $response));
    } else {
      $task = array();
      $task['is_active'] = TRUE;
      $task['event'] = 'SMS.sendMessage';
      $task['task_type'] = 'one_time';

      if ($currentHour > $endSending) {
        $task['execution_date'] = date('Y-m-d', time() + 3600 * 24);
      } else {
        $task['execution_date'] = date('Y-m-d');
      }

      if ($startSending < 10) {
        $startSending = '0' . $startSending;
      }
      $task['execution_time'] = $startSending . ':00:00';
      $task['task_data'] = json_encode(array('to' => $to, 'message' => $message));
      ManagerHolder::get('TaskSchedule')->insert($task);
    }

    return FALSE;
  }

}