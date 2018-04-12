<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * BroadcastApp library
 */
class BroadcastApp {

  /** Config */
  private $config;

  /**
   * Constructor.
   */
  public function __construct() {
    $ci =& get_instance();
    $ci->load->config('broadcast_app');
    $this->config = $ci->config->item('broadcast_app');
  }

  /**
   * Publish Single
   * @param integer $userId
   * @param integer $templateId
   */
  public function publishSingle($userId, $templateId) {
    $params = array(
        'template_id' => $templateId,
        'user_id' => $userId
    );
    $this->sendRequest('publish/single', $params);
  }

  /**
   * getTemplateVariantHtml
   * @param int $templateId
   * @param int $variant
   */
  public function getTemplateVariantHtml($templateId, $variant) {
    $params = array(
        'template_id' => $templateId,
        'variant' => $variant
    );
    return $this->sendRequest('api/email-preview', $params);
  }

  /**
   * getRecipientHtml
   * @param int $recipientId
   */
  public function getRecipientHtml($recipientId) {
    return $this->sendRequest('api/view-recipient-email/' . $recipientId);
  }


  /**
   * Send request
   * @param string $methodName
   * @param array $params
   * @return string
   */
  private function sendRequest($methodName, $params = array()) {
    $url = $this->config['api_endpoint'] . '/' . trim($methodName, '/') ;
    if (!empty($params)) {
      $url .= '?'  . http_build_query($params);
    }
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $result = curl_exec($ch);
    if($result === false)	{
      throw new Exception('Curl error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
  }

}
?>