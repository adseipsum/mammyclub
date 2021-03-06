<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_Counterparty
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_Counterparty extends Base_Admin_Controller {

  /** Filters. */
  protected $filters = array("city.id" => "", "newpostacccount.id" => "");

  /** SearchParams. */
  protected $searchParams = array("name", "newpostacccount.name");

  /** Additional Actions. */
  protected $additionalActions = array('sync');

  /**
   * preProcessFields
   * the method is called before passing
   * fields to the layout
   */
  protected function preProcessFields(&$entity) {
    if (isset($this->fields['default_sender_id'])) {
      $this->fields['default_sender_id']['relation']['where_array'] = array('counterparty_id' => $entity['id']);
    }

    parent::preProcessFields($entity);
  }


  /**
   * setViewParamsIndex
   * @param  $entities
   * @param  $pager
   * @param  $hasSidebar
   */
  protected function setViewParamsIndex(&$entities, &$pager, $hasSidebar) {
//    unset($this->actions['add'], $this->actions['delete'], $this->actions['edit']);
    unset($this->actions['add'], $this->actions['delete']);
    parent::setViewParamsIndex($entities, $pager, $hasSidebar);
  }


  /**
   * Sync
   */
  public function sync() {

	  ManagerHolder::get('Counterparty')->sync();

    set_flash_notice('Синхронизированно');
    redirect_to_referral();
  }

}