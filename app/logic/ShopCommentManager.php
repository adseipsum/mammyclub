<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * ShopCommentManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/BaseCommentManager.php';
class ShopCommentManager extends BaseCommentManager {

  /** Name field. */
  protected $nameField = "id";

  /** Order by */
  protected $orderBy = "sortorder DESC";

  /** Fields. */
  public $fields = array("content" => array("type" => "tinymce", "class" => "required", "attrs" => array("maxlength" => 65536)),
                         "date" => array("type" => "datetime", "class" => "required"),
                         "published" => array("type" => "checkbox"),
                         "user" => array("type" => "select", "relation" => array("entity_name" => "User")));

  /** List params. */
  public $listParams = array("user.name", "content", "published", "date");

  /**
   * PreProcessWhereQuery.
   * OVERRIDE THIS METHOD IN A SUBCLASS TO ADD joins and other stuff.
   * @param Doctrine_Query $query
   * @return Doctrine_Query
   */
  protected function preProcessWhereQuery($query, $pref, $what = '*') {
    $query = parent::preProcessWhereQuery($query, $pref, $what);
    if (strpos($what, 'user.') !== FALSE || $what == '*') {
      $query->addSelect("user_img.*")->leftJoin("user.image user_img");
    }

    return $query;
  }

  /**
   * getLastCommentForEntity
   * @param BaseCommentManager $entity
   */
  protected function getLastCommentForEntity($entity) {
    $baseOrder = $this->orderBy;
    $reverseOrder = 'sortorder ASC';
    if ($this->isSortorderAsc()) {
      $reverseOrder = 'sortorder DESC';
    }
    $this->setOrderBy($reverseOrder);
    $result = $this->getOneWhere(array('parent_id' => $entity['parent_id']), 'id, sortorder, parent_id, level');
    $this->setOrderBy($baseOrder);
    return $result;
  }

  /**
   * incrementSortorderForEntity
   * @param BaseCommentManager $entity
   */
  protected function incrementSortorderForEntity(&$entity) {
    if ($this->isSortorderAsc()) {
      $entity['sortorder'] = $entity['sortorder'] + 1;
    }
    $this->getDQLUpdateAllWhereQuery(array('sortorder>' => $entity['sortorder'] - 1))->set('sortorder', 'sortorder + 1')->execute();
  }

  /**
   * getMaxSortorderForEntity
   * @param BaseCommentManager $entity
   */
  protected function getMaxSortorderForEntity($entity) {
    return $this->getMax('sortorder');
  }

  /**
   * getLastChild
   * @param BaseCommentManager $entity
   */
  protected function getLastChild($entity) {
    $operator = '<';
    if ($this->isSortorderAsc()) {
      $operator = '>';
    }
    $comments = $this->getAllWhere(array('sortorder' . $operator => $entity['sortorder']), 'id, sortorder, parent_id, level');
    $result = $entity;
    foreach ($comments as $c) {
      if ($c['level'] < $entity['level']) {
        break;
      }
      $result = $c;
    }
    return $result;
  }

  /**
   * validateEntity
   * @param BaseCommentManager $entity
   */
  protected function validateEntity($entity) {}

  /**
   * commentsRecount
   * @param BaseCommentManager $entity
   */
  protected function commentsRecount($entity) {}

}