<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Recommended products broadcast controller.
 * @author Itirra - http://itirra.com
 */
class Sales_Broadcast_Controller extends Base_Project_Controller {

  /* Security code. Ensures that nobody runs this controller, but cron */
  const PROTECTION_CODE = '30ad3a3a1d2c7c63102e09e6fe4bb253';

  const INVITE_BROADCAST_TYPE = 'sales_in_shop_broadcast';

  const BROADCAST_TYPE = 'sales_in_shop_broadcast';

  /**
   * Constructor.
   */
  public function Sales_Broadcast_Controller() {
    parent::Base_Project_Controller();
  }

  /**
   * Subscribe process
   */
  public function recommended_products_broadcast_subscribe_process() {
    if ($this->isLoggedIn == FALSE) {
      uni_redirect('вход');
    }

    ManagerHolder::get('User')->updateById($this->authEntity['id'], 'newsletter_recommended_products', TRUE);
    $this->send_single_letter_of_broadcast(1);
//     redirect();
  }

  /**
   * Send single letter of recommended products broadcast
   * @param int $pregnancy_week
   */
  private function send_single_letter_of_broadcast($pregnancy_week = null) {

  }

  /**
   * Send invite to subscribe broadcast
   * @param string $protection_code
   */
  public function send_invite_to_subscribe($protection_code) {
    if ($protection_code != self::PROTECTION_CODE) {
      show_404();
    }

    $q = 'select id from auth_info where (date(now()) - date(created_at)) >= 22';
    $authInfos = ManagerHolder::get('AuthInfo')->executeNativeSQL();
    $authInfoIds = get_array_vals_by_second_key($authInfos, 'id');

    $usersWhere = array('newsletter_shop' => TRUE, 'auth_info_id' => $authInfoIds);
    $usersWhat = 'e.*, MandrillBroadcastRecipient.*, auth_info.*, MandrillBroadcastOpen.*';
    $users = ManagerHolder::get('User')->getAllWhere($usersWhere, $usersWhat);
    unset($authInfoIds, $authInfos, $usersWhat, $usersWhere);

    
  }

  public function send_recommended_product_broadcast($protection_code) {
    if ($protection_code != self::PROTECTION_CODE) {
      show_404();
    }
  }

  /**
   * Make view data for letter
   * @param array $user
   * @param array $broadcast
   */
  private function make_view_data_for_letter($user, $broadcast) {
    $this->load->helper('project_broadcast');

    $viewData = array();
    $viewData['subject'] = !empty($broadcast['subject']) ? $broadcast['subject'] : '';
    $viewData['email_appeal'] = !empty($broadcast['email_appeal']) ? $broadcast['email_appeal'] : '';
    $viewData['email_intro'] = !empty($broadcast['email_intro']) ? $broadcast['email_intro'] : '';
    $viewData['email_main_text'] = !empty($broadcast['email_main_text']) ? $broadcast['email_main_text'] : '';
    $viewData['email_outro'] = !empty($broadcast['email_outro']) ? $broadcast['email_outro'] : '';

    kprintfLettersContent($user, $viewData);
    return $viewData;    
  }
}