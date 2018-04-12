<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/base/base_controller.php';


/**
 * Facebook Webhook controller.
 * @author Itirra - http://itirra.com
 */
class Facebook_Webhook_Controller extends Base_Controller
{

  /** Libraries to load.*/
  protected $libraries = array('common/DoctrineLoader', 'Session', 'Auth');

  /** Helpers to load.*/
  protected $helpers = array('url',
    'common/itirra_language',
    'common/itirra_resources',
    'common/itirra_messages',
    'common/itirra_text',
    'common/itirra_ajax',
    'cookie',
    'project');

  public function index()
  {
    if (isset($_GET['hub_verify_token']) && isset($_GET['hub_challenge'])) {
      if ($_GET['hub_verify_token'] == 'abc123') {
        die($_GET['hub_challenge']);
      }
    }

    $this->load->library('FacebookSDK');
    $json_input = '{"entry": [{"changes": [{"field": "leadgen", "value": {"created_time": 1507641773, "page_id": "392626450914435", "form_id": "319781135097373", "leadgen_id": "320094578399362"}}], "id": "392626450914435", "time": 1507641773}], "object": "page"}';
    if (ENV == 'PROD') {
      $json_input = file_get_contents('php://input');
    }
    $json_input_decoded = json_decode($json_input, true);
    log_message("debug", "[Facebook_Webhook_Controller::index()] - input: " . print_r($json_input_decoded, true));

    if (!isset($json_input_decoded['entry'][0]['changes'][0]['value']['leadgen_id'])) {
      log_message("debug", "[Facebook_Webhook_Controller::index()] - No leadgen data found in input - exiting");
      die();
    }

    // Get request from Facebook API's
    $lead = $this->facebooksdk->getLeadData($json_input_decoded['entry'][0]['changes'][0]['value']['leadgen_id']);
    $form = $this->facebooksdk->getForms($json_input_decoded['entry'][0]['changes'][0]['value']['form_id']);
    $user = ManagerHolder::get('User')->getOneWhere(array('auth_info.email' => $lead['field_data'][0]['values'][0]), 'e.*, auth_info.*');

    foreach ($form['leads']['data'] as $item) {
      if ($item['id'] == $lead['id']) {
        if (!empty($user)) {
          log_message('debug', "[Facebook_Webhook_Controller::index()] User with" . $lead['field_data'][0]['values'][0] . "is already registered");
        } else {
          $data = array();
          $data['is_organic'] = $item['is_organic'];
          $data['email'] = $item['field_data'][0]['values'][0];
          $data['campaign_name'] = isset($item['campaign_name']) ? $item['campaign_name'] : null;
          $data['ad_name'] = isset($item['ad_name']) ? $item['ad_name'] : null;

          // Let's register this user
          $regiserData = array('email' => $data['email'], 'password' => $this->generatePassword());

          // Make new entity
          $entity = array(
            'newsletter_questions' => TRUE,
            'newsletter_comments' => TRUE,
            'newsletter_shop' => TRUE,
            'inv_channel_src' => 'facebook.com',
            'login_key' => md5(rand(0, 999999999) . time() . 'mammyclub'),
            'auth_info' => array('email' => $regiserData['email'],
              'password' => md5($regiserData['password'])));

          if ($data['is_organic'] == TRUE) {
            $entity['inv_channel_mdm'] = 'referral';
          } else {
            $entity['inv_channel_mdm'] = 'cpc';
          }
          if (isset($data['ad_name'])) {
            $entity['inv_channel_cnt'] = $data['ad_name'];
          } else {
            $entity['inv_channel_cnt'] = null;
          }
          if (isset($data['campaign_name'])) {
            $entity['inv_channel_cmp'] = $data['campaign_name'];
          } else {
            $entity['inv_channel_cmp'] = null;
          }
          $this->assignActivationKey($entity);

          // Insert the new Entity
          try {
            $entity['id'] = ManagerHolder::get('User')->insert($entity);
            // Send email about automatic registration
            $explodedEmail = explode('@', $entity['auth_info']['email']);
            $entity['name'] = $explodedEmail[0];
            $entity['auth_info']['password'] = $regiserData['password'];
            $CI =& get_instance();
            $CI->load->library("Auth");
            $CI->auth->sendEmailConfirmation($entity);
          } catch (Exception $e) {
            log_message('error', '[Facebook_Webhook_Controller::index()] - ' . $e->getMessage());
          }
        }
      }
    }
  }


  /**
   * Generate password.
   * @return string
   */
  private function generatePassword()
  {
    $this->load->helper('string');
    return random_string('alnum', 8);
  }

  /**
   * Assign activation key.
   * @param array $entity
   */
  private function assignActivationKey(&$entity)
  {
    $entity['auth_info']['activation_key'] = md5(rand() . microtime());
  }

}