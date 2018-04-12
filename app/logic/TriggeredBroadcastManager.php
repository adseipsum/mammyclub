<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * TriggeredBroadcastManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class TriggeredBroadcastManager extends BaseManager {

  /** Fields. */
  public $fields = array("name" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "subject" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "email_main_text" => array("type" => "tinymce", "class" => "required", "attrs" => array("maxlength" => 65536)),
                         "utm_source" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "utm_campaign" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)));

  /** List params. */
  public $listParams = array("name");

  /**
   * sendSingleTriggeredLetter
   * @param string $key
   * @param array $userData
   */
  public function sendSingleTriggeredLetter($key, $user) {
    $entity = $this->getOneWhere(array('utm_source' => $key), 'e.*');
    if (empty($entity)) {
      return FALSE;
    }

    $emailHtml = kprintf($entity['email_main_text'], $this->getTemplateVars($user));

    $googleAnalData = array('utm_source'   => $entity['utm_source'],
                            'utm_medium'   => 'email',
                            'utm_campaign' => $entity['utm_campaign']);
    ManagerHolder::get('MandrillBroadcast')->addLoginKeyToLink($emailHtml, $user['login_key'], $googleAnalData);

    ManagerHolder::get('MandrillBroadcast')->processServiceEmailData($user, $entity['subject'], $emailHtml);

    return ManagerHolder::get('EmailMandrill')->sendTemplate($user['auth_info']['email'], 'empty', array('message' => $emailHtml), $entity['subject']);
  }

  /**
   * getTemplateVars
   * @param array $user
   */
  private function getTemplateVars($user) {

    $ci =& get_instance();
    $ci->load->config('auth');
    $authConfig = $ci->config->item('auth');

    $keysToProcess = array('name' => 'name',
                           'email' => 'auth_info.email',
                           'password' => 'auth_info.password',
                           'activation_key' => 'auth_info.activation_key');

    $data = array();

    foreach ($keysToProcess as $k => $v) {
      $data[$k] = null;
      if (strpos($v, '.') !== FALSE) {
        $vArr = explode('.', $v);
        if (isset($user[$vArr[0]][$vArr[1]])) {
          $data[$k] = $user[$vArr[0]][$vArr[1]];
        }
      } else {
        if (isset($user[$v])) {
          $data[$k] = $user[$v];
        }
      }
    }

    $data['email_confirmation_url'] = site_url($authConfig['url_email_confirm_process'] . '/' . $data['activation_key']);

    return $data;
  }

}