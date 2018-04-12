<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * Xadmin controller.
 * @author Itirra - http://itirra.com
 */
class xAdmin_AdminLog extends Base_Admin_Controller {


  /** Filter. Row example: "column_name" => default_value. Default value may be null. */
  protected $filters = array('admin.id' => '', 'action' => '', 'entity_name' => '');

  /** Date Filters. Row example: array("created_at"). */
  protected $dateFilters = array("created_at");

  /**
   * @see Base_Admin_Controller::init()
   */
  protected function init() {
    parent::init();
    $this->actions = array();
  }

}