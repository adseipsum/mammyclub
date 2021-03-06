<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_ReturningBroadcast
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_ReturningBroadcast extends Base_Admin_Controller {

  /** Additional Actions. */
  protected $additionalItemActions = array('preview');

  /** Is delete all action allowed */
  protected $isDeleteAllAllowed = FALSE;

  /**
   * preview
   * @param int $id
   */
  public function preview($id) {

    $this->load->helpers(array('project_broadcast', 'common/itirra_date', 'project'));

    $entity = ManagerHolder::get($this->managerName)->getById($id, 'e.*');

    $viewData = ManagerHolder::get('Common')->createViewDataPreview($entity);
    foreach ($viewData as $k => $v) {
      $this->layout->set($k, $v);
    }

    $this->layout->setLayout('email');
    $this->layout->setModule('email/product_broadcast');
    $this->layout->view('view');

    $html = $this->layout->renderLayout(TRUE);

    die($html);
  }

  /**
   * setViewParamsIndex
   * @param  $entities
   * @param  $pager
   * @param  $hasSidebar
   */
  protected function setViewParamsIndex(&$entities, &$pager, $hasSidebar) {
    unset($this->actions['add']);
    unset($this->actions['delete']);
    parent::setViewParamsIndex($entities, $pager, $hasSidebar);
  }

}