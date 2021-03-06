<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_ArticleComment
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_ArticleComment extends Base_Admin_Controller {

  /** Filters. */
  protected $filters = array("article.id" => "", "user.id" => "");

  /** DateFilters. */
  protected $dateFilters = array("date");

  /** Additional Actions. Simple Array. Ex. array('view', 'print')*/
  protected $additionalItemActions = array('view');

  /**
   * Pre process params.
   * @return string
   */
  protected function preProcessParams($addParams = null) {
    return parent::preProcessParams('can_be_deleted');
  }

  /**
   * view.
   */
  public function view($id) {
    $comment = ManagerHolder::get('ArticleComment')->getById($id, 'e.*, article.*');
    redirect(site_url($comment['article']['page_url'] . '#commentid_' . $comment['id']));
  }

}