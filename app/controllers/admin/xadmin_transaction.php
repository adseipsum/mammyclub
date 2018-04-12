<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_Transaction
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_Transaction extends Base_Admin_Controller {

  /** Filters. */
  protected $filters = array("status" => "");

  /** DateFilters. */
  protected $dateFilters = array("created_at");

  /** Is delete all action allowed */
  protected $isDeleteAllAllowed = FALSE;

  /**
   * setViewParamsIndex
   * @param  $entities
   * @param  $pager
   * @param  $hasSidebar
   */
  protected function setViewParamsIndex(&$entities, &$pager, $hasSidebar) {
    unset($this->actions['add']);
    unset($this->actions['delete']);
    parent::setViewParamsIndex(&$entities, &$pager, $hasSidebar);
  }

}