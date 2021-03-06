<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_QuestionComment
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_QuestionComment extends Base_Admin_Controller {

  /** Filters. */
  protected $filters = array("question.id" => "", "user.id" => "");

  /** DateFilters. */
  protected $dateFilters = array("date");

  /** Additional Actions. Simple Array. Ex. array('view', 'print')*/
  protected $additionalItemActions = array('view');

  /**
   * view.
   */
  public function view($id) {
    $comment = ManagerHolder::get('QuestionComment')->getById($id, 'e.*, question.*');
    redirect(site_url($comment['question']['page_url'] . '#commentid_' . $comment['id']));
  }

  /**
   * setViewParamsIndex
   * @param  $entities
   * @param  $pager
   * @param  $hasSidebar
   */
  protected function setViewParamsIndex(&$entities, &$pager, $hasSidebar) {
    unset($this->actions['delete']);
    parent::setViewParamsIndex($entities, $pager, $hasSidebar);
  }

}