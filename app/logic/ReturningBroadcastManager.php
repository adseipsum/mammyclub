<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * ReturningBroadcastManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class ReturningBroadcastManager extends BaseManager {

  /** Fields. */
  public $fields = array("name" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "subject" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "email_appeal" => array("type" => "tinymce", "attrs" => array("maxlength" => 255)),
                         "email_intro" => array("type" => "tinymce", "class" => "charCounter", "attrs" => array("maxlength" => 5000)),
                         "email_main_text" => array("type" => "tinymce", "attrs" => array("maxlength" => 65536)),
                         "email_outro" => array("type" => "tinymce", "class" => "charCounter", "attrs" => array("maxlength" => 5000)),
                         "sent_datetime" => array("type" => "datetime", "class" => "required"));

  /** List params. */
  public $listParams = array("name");

  /**
   * Create broadcast content
   * @param array $broadcast
   * @param array $user
   * @return array
   */
  public function createBroadcastContent($broadcast, $user) {

    $viewData = array();
    $viewData['subject'] = !empty($broadcast['subject']) ? $broadcast['subject'] : '';
    $viewData['email_appeal'] = !empty($broadcast['email_appeal']) ? $broadcast['email_appeal'] : '';
    $viewData['email_intro'] = !empty($broadcast['email_intro']) ? $broadcast['email_intro'] : '';
    $viewData['email_outro'] = !empty($broadcast['email_outro']) ? $broadcast['email_outro'] : '';
    $viewData['email_main_text'] = !empty($broadcast['email_main_text']) ? $broadcast['email_main_text'] : '';

    $ci = &get_instance();
    $ci->load->helper('project_broadcast');

    // Proccess data
    foreach ($viewData as &$data) {
      if (!empty($data)) {

        ManagerHolder::get('MandrillBroadcast')->addLoginKeyToLink($data, $user['login_key']);
        kprintfLettersContent($user, $data);

        // Search for short tag and replace it with link
        if (strpos($data, '{UNSUBSCRIBE_LINK}') !== FALSE) {
          $unsubscribeLink = site_url('отписаться-от-рассылки' . '?' . LOGIN_KEY . '=' . $user['login_key']);
          $data = str_replace('{UNSUBSCRIBE_LINK}', $unsubscribeLink, $data);
        }
        if (strpos($data, '{RETURNING_SUCCESS_PAGE}') !== FALSE) {
          $successLink = site_url(RETURNING_SUCCESS_PAGE . '?' . LOGIN_KEY . '=' . $user['login_key']);
          $data = str_replace('{RETURNING_SUCCESS_PAGE}', $successLink, $data);
        }
        if (strpos($data, '{DATE_AFTER_7_DAYS}') !== FALSE) {
          $weekAfterDate = date("Y-m-d", strtotime("+1 week"));
          $data = str_replace('{DATE_AFTER_7_DAYS}', $weekAfterDate, $data);
        }

      }
    }

    return $viewData;
  }

}