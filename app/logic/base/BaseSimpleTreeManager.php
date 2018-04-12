<?php
require_once APPPATH . 'logic/base/BaseManager.php';

/**
 * BaseSimpleTreeManager.
 * @author Alexei Chizhmakov (Itirra - www.itirra.com)
 */
class BaseSimpleTreeManager extends BaseManager {

  /** Name field. */
  protected $nameField = "title";

  /** Default Hydration. */
  protected $defaultHydration = Doctrine_Core::HYDRATE_ARRAY_HIERARCHY;

  /**
   * Constructor.
   * @param $name
   * @param $mode
   * @return BaseTreeManager
   */
  public function BaseSimpleTreeManager($name = null, $mode = null) {
    parent::BaseManager($name, $mode);
  }

  /**
   * Get base query.
   * @return unknown
   */
  public function getBaseQuery(){
    return Doctrine_Query::create()->select("e.*")->from($this->entityName . " e")->setHydrationMode($this->defaultHydration);
  }

  /**
   * Doctrine_Table
   * @return Doctrine_Nested_Set
   */
  public function getFullById($id){
    return Doctrine::getTable($this->entityName)->findOneById($id);
  }

  /**
   * Doctrine_Table
   * @return Doctrine_Nested_Set
   */
  public function getTree(){
    return Doctrine::getTable($this->entityName)->getTree();
  }


  public function getRoot() {
    $treeObject = $this->getTree();
    $treeObject->setBaseQuery($this->getBaseQuery());
    $result = array();
    $root = $treeObject->fetchRoot();
    return $root;
  }


  /**
   * Get tree as array.
   * @return array
   */
  public function getAsArray() {
    $treeObject = $this->getTree();
    $treeObject->setBaseQuery($this->getBaseQuery());
    $result = array();
    $root = $treeObject->fetchTree();
    if ($root) {
      $result = $root;
    }
    return $result;
  }

  /**
   * Get all entities as view array.
   * @return array
   */
  public function getAsViewArray($whithoutIds = array()) {
    $treeObject = $this->getTree();
    $q = Doctrine_Query::create()->select("e.*")->from($this->entityName . " e")->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
    $treeObject->setBaseQuery($q);
    $tree = $treeObject->fetchTree();
    $result = array();
    if ($tree) {
      foreach ($tree as $node) {
        if (!in_array($node['id'], $whithoutIds)) {
          $result[$node['id']] = str_repeat('-', $node['level']) . $node['name'];
        }
      }
    }
    return $result;
  }

  public function getDescendantIds($entity) {
    $result = array();
    $treeObject = $this->getTree();
    $q = Doctrine_Query::create()->select("e.*")->from($this->entityName . " e")->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
    $treeObject->setBaseQuery($q);
    $decendants = $entity->getNode()->getDescendants();
    if ($decendants) {
      foreach ($decendants as $dec) {
        $result[] = $dec[$this->pk];
      }
    }
    return $result;
  }
  
  public function getAncestors($entityId) {
   if (is_array($entityId)) {
      $entityId = $entityId['id'];
    }
    $table = Doctrine::getTable($this->entityName);
    $q = Doctrine_Query::create()->select("e.*")->from($this->entityName . " e")->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
    $table->getTree()->setBaseQuery($q);
    $entity = $table->findOneById($entityId);
    if ($entity) {
      return $entity->getNode()->getAncestors();
    }
    return array(); 
  }  

  /**
   * Name field getter.
   * @return string
   */
  public function getNameField() {
    return $this->nameField;
  }

}
