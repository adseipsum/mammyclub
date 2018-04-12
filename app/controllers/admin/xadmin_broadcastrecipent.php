<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * xAdmin email broadcast controller.
 * @author Itirra - http://itirra.com
 */
class xAdmin_BroadcastRecipent extends Base_Admin_Controller {

  /** Search params. */
  protected $searchParams = array('email');

  /** Date Filters. Row example: array("created_at"). */
  protected $dateFilters = array('updated_at');

  /**
   * Constructor.
   * @return
   */
  public function xAdmin_BroadcastRecipent() {
    parent::Base_Admin_Controller();
  }

  /**
   * setViewParamsIndex
   * @param  $entities
   * @param  $pager
   * @param  $hasSidebar
   */
  protected function setViewParamsIndex(&$entities, &$pager, $hasSidebar) {
    $this->layout->set("processListUrl", $this->processListUrl);
    $this->layout->set("isDeleteAllAllowed", $this->isDeleteAllAllowed);
    $this->layout->set("isListSortable", $this->isListSortable);
    $this->layout->set("maxLines", $this->maxLines);
    unset($this->actions['add'], $this->actions['delete'], $this->actions['edit']);
    $this->layout->set("actions", $this->actions);
    $this->layout->set("hasSidebar", $hasSidebar);
    $this->layout->set("menuItems", $this->menuItems);
    $this->layout->set("defOrderBy", $this->defOrderBy);
    $this->layout->set("params", $this->listParams);
    $this->layout->set("fields", $this->fields);
    $this->layout->set("entities", $entities);
    $this->layout->set("pager", $pager);
    $this->layout->set("autocompleteEnabled", $this->autocompleteEnabled);
    $this->layout->set("listViewIgnoreLinks", $this->listViewIgnoreLinks);
    $this->layout->set("listViewLinksRewrite", $this->listViewLinksRewrite);
    $this->layout->set("listViewValuesRewrite", $this->listViewValuesRewrite);
    $this->layout->set("additionalPostParams", $this->additionalPostParams);
    $this->layout->set("additionalActions", $this->additionalActions);
    $this->layout->set("batchUpdateFields", $this->batchUpdateFields);
  }

}