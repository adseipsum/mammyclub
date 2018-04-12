<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_Settings
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_Settings extends Base_Admin_Controller {

  /** Filters. */
  protected $filters = array("group.id" => "");

  /** SearchParams. */
  protected $searchParams = array("name", "k");

  /** Extra where. */
  protected $extraWhere = null;


  /**
   * Init.
   */
  protected function init() {
    parent::init();
    if (!$this->loggedInAdmin['is_itirra'] && file_exists(APPPATH . "logic/PageManager.php")) {
      $this->extraWhere = array("page_id" => null);
    }
  }


  /**
   * SetAddEditDataAndShowView.
   * Set all needed view data and show add_edit form.
   * @param object $entity
   */
  protected function setAddEditDataAndShowView($entity) {
    $this->preProcessFields($entity);

    if (isset($entity['id']) && isset($entity['type'])) {
      if (!isset($this->loggedInAdmin['is_itirra']) || ($this->loggedInAdmin['is_itirra'] != TRUE) ) {
        unset($this->fields['k']);
        unset($this->fields['type']);
        unset($this->fields['group']);
        if (isset($this->fields['page_id'])) {
          unset($this->fields['page_id']);
        }
        $this->fields['name']['class'] .= ' readonly';
        $this->fields['name']['attrs']['readonly'] = ' readonly';
      }
      $this->fields['v']['type'] = $entity['type'];
      if($entity['type'] == 'image') {
        $entity['v'] = ManagerHolder::get('Image')->getById($entity['v'], 'e.*');
      }
      unset($this->actions['add']);
      unset($this->actions['delete']);
    }

    $this->layout->set("fields", $this->fields);
    $this->layout->set("backUrl", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName . get_get_params()));
    if (!empty($entity['id'])) {
      $this->layout->set("nextUrl", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/next/' . $entity['id'] . '/' . get_get_params());
      $this->layout->set("prevUrl", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/prev/' . $entity['id'] . '/' . get_get_params());
    }
    $this->layout->set("processLink", $this->adminBaseRoute . '/' .  strtolower($this->entityUrlName) . '/add_edit_process');
    $this->preAddEditView($entity);
    $this->layout->set("entity", $entity);
    $this->layout->set("actions", $this->actions);
    $this->layout->set("print", $this->print);
    $this->layout->view($this->itemView);
  }

  /**
   * Add Edit process.
   */
  public function add_edit_process() {
    if(isset($_POST['type']) && $_POST['type'] == 'image') {
      $this->fields['v']['type'] = 'image';
    }
    parent::add_edit_process();
  }

}