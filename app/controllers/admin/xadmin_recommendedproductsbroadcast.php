<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_RecommendedProductsBroadcast
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_RecommendedProductsBroadcast extends Base_Admin_Controller {

  /** Additional Actions. Simple Array. Ex. array('view', 'print')*/
  protected $additionalItemActions = array('preview');

  /** SearchParams. */
  protected $searchParams = array("name");

  /**
   * preview
   * @param int $id
   */
  public function preview($id) {

    $this->load->helpers(array('project_broadcast', 'common/itirra_date', 'project'));

    $entity = ManagerHolder::get($this->managerName)->getById($id, 'e.*, products.*');

    $viewData = ManagerHolder::get('Common')->createViewDataPreview($entity);
    foreach ($viewData as $k => $v) {
      $this->layout->set($k, $v);
    }

    // Set subject
    $this->layout->set('subject', !empty($entity['subject']) ? $entity['subject'] : '');

    $this->layout->setLayout('email');
    $this->layout->setModule('email/product_broadcast');
    $this->layout->view('view');

    $html = $this->layout->renderLayout(TRUE);

    die($html);
  }

  /**
   * PreProcessPost.
   * htmlspecialchars all fields except TinyMCE
   * remove all empty fields.
   */
  protected function preProcessPost() {

    if (isset($_POST['products']) && !empty($_POST['products'])) {
      $this->session->set_userdata('savedPostProducts', $_POST['products']);
      unset($_POST['products']);
    }
    parent::preProcessPost();
  }

  /**
   * Implementation of POST_SAVE event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function postSave(&$entity) {
    $savedPostProducts = $this->session->userdata('savedPostProducts');
    if(!empty($savedPostProducts)) {
      ManagerHolder::get('RecommendedProductsBroadcastProduct')->deleteAllWhere(array('recommended_products_broadcast_id' => $entity['id']));
      foreach ($savedPostProducts as $pId) {
        $data = array('recommended_products_broadcast_id' => $entity['id'],
                      'product_id' => $pId);
        ManagerHolder::get('RecommendedProductsBroadcastProduct')->insert($data);
      }
    }
    $this->session->unset_userdata('savedPostProducts');
  }

}