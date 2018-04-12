<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Alpha SMS manager
 */
class AlphaSMSManager {

  protected $ci;

  protected $numbersTo = array();

  protected $alphaSMSConfig = array();

  protected $defaultConfigs = array('alpha_sms');

	/**
	 * AlphaSMSManager constructor.
	 */
	public function AlphaSMSManager() {
    $this->ci =& get_instance();

    foreach ($this->defaultConfigs as $dc) {
      $this->ci->config->load($dc);
    }

    $this->alphaSMSConfig = $this->ci->config->item('alpha_sms');
  }

  /**
   * Send new order notice
   */
  public function sendNewOrderNoticeToAdmins($sId) {
    $siteOrder = ManagerHolder::get('SiteOrder')->getById($sId, 'e.*');
    $message = 'Новый заказ на имя ' . $siteOrder['fio'];
    $message = str_replace(' ', '+', $message);

    $recipientPhones = $this->alphaSMSConfig['new_order_notice_to'];
    if (ManagerHolder::get('Settings')->existsWhere(array('k' => 'new_order_notice_to'))) {
      $recipientPhones = ManagerHolder::get('Settings')->getValByKey('new_order_notice_to');
      $recipientPhones = explode(',', $recipientPhones);
    }
    foreach ($recipientPhones as $phone) {
      $this->sendMessage(trim($phone), $message);
    }
  }

  /**
   * Send message
   * @param string $to
   * @param string $message
   */
  public function sendMessage($to = NULL, $message = NULL) {

  	// Get limit for SMS from Settings
	  $limitSms = ManagerHolder::get('Settings')->getValByKey('action_limit_sms');
	  // Get ActionLimit entity on $to
	  $action = ManagerHolder::get('ActionLimit')->getOneWhere(array('value_field' => $to, 'action' => 'sms'), 'e.*');
	  $url = $this->processUrlPatternWithLogAndPass() .
		  '&command=send' .
		  '&from=' . $this->alphaSMSConfig['from_name'] .
		  '&to=' . $to .
		  '&message=' . $message;

	  $response = '';

		$update = array();

		if (!empty($action)) {
			$allowedTime = false;
			$allowedToSent = false;
			$allowedActionCount = false;
			$smsSent = false;

			$lastActionDate = strtotime($action['last_action_date']);
			$currentTime = time();

			if($currentTime - $lastActionDate >= 3600) {
				$allowedTime = true;
			}	else {
				if ($action['action_count'] < $limitSms) {
					$allowedActionCount = true;
				}
			}
			if ($allowedTime) {
				$update['action_count'] = 1;
				$update['last_action_date'] = date(DOCTRINE_DATE_FORMAT);
				$allowedToSent = true;
			}
			if (!$allowedTime && $allowedActionCount) {
				$update['action_count'] = $action['action_count'] + 1;
				$allowedToSent = true;
			}
			if ($allowedToSent) {
				$smsSent = true;
				ManagerHolder::get('ActionLimit')->updateAllWhere(array('id' => $action['id']), $update);
			}
		} else {
			$smsSent = true;
			$insert['action'] = 'sms';
			$insert['value_field'] = $to;
			$insert['action_count'] = 1;
			$insert['last_action_date'] = date(DOCTRINE_DATE_FORMAT);
      $action = array();
      $action['id'] = ManagerHolder::get('ActionLimit')->insert($insert);
		}
	  if ($smsSent) {
		  $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		  $response = curl_exec($ch);
		  curl_close($ch);
	  } else {
	    ManagerHolder::get('AdminNotification')->sendNotification('sms_limit_alert', 'ActionLimit', $action['id'], 'e.*');
    }
	  return $response;
  }

  /**
   * Process request url
   * @param string $url
   */
  protected function processUrlPatternWithLogAndPass() {
    $url = kprintf($this->alphaSMSConfig['http_query_pattern'], $this->alphaSMSConfig);
    return $url;
  }
}