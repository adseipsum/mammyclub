<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_StoreInventoryParserSetting
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_StoreInventoryParserSetting extends Base_Admin_Controller {

  /** Filters. */
  protected $filters = array("product.id" => "", "product_group.id" => "", "store.id" => "");

  /** DateFilters. */
  protected $dateFilters = array("last_parse_at");

  /** Import. */
  protected $import = TRUE;

  /** Export. */
  protected $export = TRUE;




}