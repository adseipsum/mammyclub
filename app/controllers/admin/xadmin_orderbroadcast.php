<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_OrderBroadcast
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_OrderBroadcast extends Base_Admin_Controller {

  /**
   * setViewParamsIndex
   * @param  $entities
   * @param  $pager
   * @param  $hasSidebar
   */
  protected function setViewParamsIndex(&$entities, &$pager, $hasSidebar) {
    unset($this->actions['add'], $this->actions['delete']);
    parent::setViewParamsIndex($entities, $pager, $hasSidebar);
  }

}