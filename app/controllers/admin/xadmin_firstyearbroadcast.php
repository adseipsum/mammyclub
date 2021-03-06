<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_FirstYearBroadcast
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_FirstYearBroadcast extends Base_Admin_Controller {

  /** Additional Actions. */
  protected $additionalItemActions = array('preview');

  /** Filters. */
  protected $filters = array("countries.id" => "");

  /** SearchParams. */
  protected $searchParams = array("name");

  /**
   * preview
   * @param int $id
   */
  public function preview($id) {

    $this->load->helpers(array('project_broadcast', 'common/itirra_date', 'project'));

    $entity = ManagerHolder::get($this->managerName)->getById($id, 'e.*, article.*, products.*');
    $tempMainText = !empty($entity['article']) ? $entity['article']['content'] : '';
    if(strpos($entity['email_main_text'], '{PRODUCTS}') !== FALSE) {
      $tempMainText .= $entity['email_main_text'];
    }
    $entity['email_main_text'] = $tempMainText;

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
   * preProcessFields
   * the method is called before passing
   * fields to the layout
   */
  protected function preProcessFields(&$entity) {
    parent::preProcessFields($entity);
    $ageOptions = range(1, 600);
    $this->fields['age_of_child']['options'] = array_combine($ageOptions, $ageOptions);
  }

  /**
   * Implementation of POST_SAVE event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function postSave(&$entity) {

    $productsEntityMap = array('FirstYearBroadcastProduct'      => 'products',
                               'FirstYearBroadcastProductBoys'  => 'products_boys',
                               'FirstYearBroadcastProductGirls' => 'products_girls');

    foreach ($productsEntityMap as $manager => $alias) {
      if(isset($_POST[$alias]) && !empty($_POST[$alias])) {
        ManagerHolder::get($manager)->deleteAllWhere(array('first_year_broadcast_id' => $entity['id']));
        foreach ($_POST[$alias] as $pId) {
          $data = array('first_year_broadcast_id' => $entity['id'],
                        'product_id' => $pId);
          ManagerHolder::get($manager)->insert($data);
        }
      }
    }
  }

}