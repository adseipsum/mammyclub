<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_SettingsGroup
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_SettingsGroup extends Base_Admin_Controller {

  /** SearchParams. */
  protected $searchParams = array("name");

}