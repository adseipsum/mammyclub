<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_XBroadcastOpen
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_XBroadcastOpen extends Base_Admin_Controller {

  /** Filters. */
  protected $filters = array("recipient.id" => "", "broadcast.id" => "");

  /** DateFilters. */
  protected $dateFilters = array("created_at");

  /** Import. */
  protected $import = TRUE;

  /** Export. */
  protected $export = TRUE;




}