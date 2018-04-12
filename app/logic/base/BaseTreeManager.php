<?php
require_once APPPATH . 'logic/base/BaseManager.php';

/**
 * BaseTreeManager.
 * @author Yuriy Manoylo (Itirra - www.itirra.com)
 */
class BaseTreeManager extends BaseManager {

  /** Default Hydration. */
  protected $defaultHydration = Doctrine_Core::HYDRATE_ARRAY_HIERARCHY;

  /**
   * Constructor.
   * @param $name
   * @param $mode
   * @return BaseTreeManager
   */
  public function BaseTreeManager($name = null, $mode = null) {
  	parent::BaseManager($name, $mode);
  }

  /**
   * Get base query.
   * @return unknown
   */
  public function getBaseQuery($what = 'e.*') {
    $what = $this->preProcessWhat($what);
    $q = Doctrine_Query::create()->select($what)->from($this->entityName . " e")->setHydrationMode($this->defaultHydration);
    $q->addOrderBy('priority');
    $q = $this->preProcessLanguageQuery($q, "e");
    $q = $this->preProcessWhereQuery($q, "e", $what);
    return $q;
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

  /**
   * Get Roots.
   * @return array roots
   */
  public function getRoots() {
    $result = array();
    $treeObject = $this->getTree();
    $treeObject->setBaseQuery($this->getBaseQuery());
    $roots = $treeObject->fetchRoots();
    foreach ($roots as $root) {
      $opts = array('root_id' => $root["root_id"]);
      $tree = $treeObject->fetchTree($opts, Doctrine_Core::HYDRATE_ARRAY);
      $result[] = $tree[0];
    }
    return $result;
  }

  /**
   * Get Root By Id.
   * @param integer $rootId
   */
  public function getRootById($rootId) {
    $result = null;
    $treeObject = $this->getTree();
    $q = $this->getBaseQuery();
    $q->addWhere("e.id = ?", $rootId);
    $treeObject->setBaseQuery($q);
    $roots = $treeObject->fetchRoots();
    if (count($roots) > 0) {
      $result = $roots[0];
      $treeObject->setBaseQuery($this->getBaseQuery());
      $opts = array('root_id' => $result["root_id"]);
      $tree = $treeObject->fetchTree($opts, Doctrine_Core::HYDRATE_ARRAY_HIERARCHY);
      $result = $tree[0];
    }
    return $result;
  }

  /**
   * Get Where.
   * @param array $keyValueArray
   */
  public function getWhere($keyValueArray, $what = '*') {
    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;

    $treeObject = $this->getTree();
    $q = $this->getBaseQuery($what);
    if ($keyValueArray) {
      $q = $this->processKeyValueArray($q, $keyValueArray);
    }
    $treeObject->setBaseQuery($q);
    $array = array();
    $roots = $treeObject->fetchRoots();
    if (count($roots) > 0) {
      foreach ($treeObject->fetchRoots() as $root) {
        $opts = array('root_id' => $root["root_id"]);
        $treeObject->setBaseQuery($this->getBaseQuery($what));
        $tree = $treeObject->fetchTree($opts, Doctrine_Core::HYDRATE_ARRAY_HIERARCHY);
        if (!empty($tree[0])) {
          $array[] = $tree[0];
        }
      }
    } else {
      $treeObject->setBaseQuery($this->getBaseQuery($what));
      foreach ($treeObject->fetchRoots() as $root) {
        $opts = array('root_id' => $root["root_id"]);
        $treeObject->setBaseQuery($q);
        $tree = $treeObject->fetchTree($opts, Doctrine_Core::HYDRATE_ARRAY_HIERARCHY);
        if (!empty($tree[0])) {
          $array[] = $tree[0];
        }
      }
    }

    $this->saveToCache($array, __METHOD__, $args);

    return $array;
  }

  /**
   * Get tree as plain array.
   * @return array
   */
  public function getAsPlainArray($what = '*') {
    $this->defaultHydration = Doctrine_Core::HYDRATE_ARRAY;
    $array = $this->getAll($what);
    $this->defaultHydration = Doctrine_Core::HYDRATE_ARRAY_HIERARCHY;
    return $array;
  }

  /**
   * Get tree as plain array where.
   * @param array $keyValueArray
   * @param string $what
   * @return array
   */
  public function getAsPlainArrayWhere($keyValueArray, $what = '*') {
    $this->defaultHydration = Doctrine_Core::HYDRATE_ARRAY;
    $array = $this->getAllWhere($keyValueArray, $what);
    $this->defaultHydration = Doctrine_Core::HYDRATE_ARRAY_HIERARCHY;
    return $array;
  }

  /**
   * Get tree as array.
   * @return array
   */
  public function getAsArray($what = 'e.*') {
    $treeObject = $this->getTree();
    $treeObject->setBaseQuery($this->getBaseQuery($what));
    $array = array();
    foreach ($treeObject->fetchRoots() as $root) {
      $opts = array('root_id' => $root["root_id"]);
      $tree = $treeObject->fetchTree($opts, Doctrine_Core::HYDRATE_ARRAY_HIERARCHY);
      $array[] = $tree[0];
    }
    if (!empty($this->i18nFields) && $this->simpleI18n) {
      $array = $this->postProcessLanguageResult($array);
    }
    return $array;
  }

  /**
   * Get roots of tree as view array.
   * @param array $withoutIds
   * @param String $field
   * @return array
   */
  public function getRootsTreeAsViewArray($withoutIds = array(), $field = 'name') {
    $result = array();
    $entities = $this->getAll('id, level, ' . $field, null, $withoutIds);
    if ($entities) {
      foreach($entities as $entity) {
        $result[$entity['id']] = $entity[$field];
      }
    }
    return $result;
  }

  /**
   * GetLastTreeAsViewArray.
   * @return array
   */
  public function getTreeAsViewArray($withoutIds = array(), $field = 'name', $concatField = null, $keyValueArray = array(), $sep = '-') {
    if (!empty($this->i18nFields) && $this->simpleI18n) {
      if (in_array($field, $this->i18nFields) && isset($this->fields[$field . '_' . $this->language])) {
        $field = $field . '_' . $this->language;
      }
    }
    $args = func_get_args();
    $options = array();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    $treeObject = $this->getTree();
    $q = $this->getBaseQuery();
    if ($keyValueArray) {
      $q = $this->processKeyValueArray($q, $keyValueArray);
    }
    $treeObject->setBaseQuery($q);
    $array = array();
    foreach ($treeObject->fetchRoots() as $root) {
      $opts = array('root_id' => $root["root_id"]);
      $tree = $treeObject->fetchTree($opts, Doctrine::HYDRATE_ARRAY_HIERARCHY);
      $tree = $tree[0];
      $options = $this->nested_array_loop($options, $tree, $sep, $field, $concatField);
    }
    if (!empty($withoutIds)) {
      foreach ($withoutIds as $wId) {
        if (isset($options[$wId])) {
          unset($options[$wId]);
        }
      }
    }
    $this->saveToCache($options, __METHOD__, $args);
    return $options;
  }

  /**
   * Get all entities as view array.
   * @param array $withoutIds
   * @param string | array $field
   * Examples: $field = 'title' will return an array like: (id => title);
   *           $field = array('url' => 'title') will return an array like (url => title);
   * @return array
   */
  public function getAsViewArray($withoutIds = array(), $field = 'name', $concatField = null, $keyValueArray = array(), $addWhat = '', $sep = '-') {
    return $this->getTreeAsViewArray($withoutIds, $field, $concatField, $keyValueArray, $sep);
  }

  /**
   * Get tree array recursivly.
   * @param array $options
   * @param integer $root
   * @return array
   */
  public function nested_array_loop(&$options, $root, $sep, $field, $concatField = NULL){
    if (is_array($root) && (isset($root["0"]) || isset($root["1"])) && !isset($root["id"])) { // if it is numeric array, not an entity
        foreach($root as $theOneOfRoots){
          $this->nested_array_loop($options, $theOneOfRoots, $sep, $field, $concatField);
        }
    } else {
      if (!empty($sep)) {
        $options[$root['id']] = str_repeat($sep, $root['level']) . '(' . ($root['level'] + 1) . ') ' . $root[$field];
      } else {
        $options[$root['id']] = $root[$field];
      }
      if (!empty($concatField)) {
        $options[$root['id']] .= ' ' . $sep . ' ' . $root[$concatField];
      }

      foreach($root["__children"] as $child){
        $this->nested_array_loop($options, $child, $sep, $field, $concatField);
      }
      return $options;
    }
  }



  public function nested_array_loop_rekey(&$tree, $rekeyMap = array()) {
    if (is_array($tree) && (isset($tree["0"]) || isset($tree["1"])) && !isset($tree["id"])) { // if it is numeric array, not an entity
      foreach($tree as $k => $theOneOfRoots){
        $tree[$k] = $this->nested_array_loop_rekey($theOneOfRoots, $rekeyMap);
      }
    } else {

      if (!empty($rekeyMap)) {
        foreach ($rekeyMap as $k => $v) {
          $tree[$v] = $tree[$k];
          unset($tree[$k]);
        }
      }

      if (isset($rekeyMap['__children'])) {
        foreach($tree[$rekeyMap['__children']] as $k => $child){
          $tree[$rekeyMap['__children']][$k] = $this->nested_array_loop_rekey($child, $rekeyMap);
        }
      } else {
        foreach($tree["__children"] as $k => $child){
          $tree["__children"][$k] = $this->nested_array_loop_rekey($child, $rekeyMap);
        }
      }
      return $tree;
    }
    return $tree;
  }


  /**
   * getTreeAsViewArrayWhithout.
   * @return array
   */
  public function getTreeAsViewArrayWhithout($entityId) {
    $options = array();
    $treeObject = $this->getTree();
    $treeObject->setBaseQuery($this->getBaseQuery());
    foreach ($treeObject->fetchRoots() as $root) {
      $opts = array('root_id' => $root["root_id"]);
      $tree = $treeObject->fetchTree($opts, Doctrine::HYDRATE_ARRAY_HIERARCHY);
      $tree = $tree[0];
      $options = $this->nested_array_loop_without($options, $tree, $entityId);
    }
    return $options;
  }

  /**
   * Nested array loop
   * @param array $options
   * @param array $root
   * @param integer $entityId
   */
  public function nested_array_loop_without(&$options, $root, $entityId){
    if (is_array($root) && (isset($root["0"]) || isset($root["1"])) && !isset($root["id"])) { // if it is numeric array, not an entity
        foreach($root as $theOneOfRoots){
          $this->nested_array_loop_without($options, $theOneOfRoots, $entityId);
        }
    } else {
      if ($root['id'] != $entityId) {
        $options[$root['id']] = str_repeat('-', $root['level']) . '(' . ($root['level'] + 1) . ') ' . $root[$this->nameField];
        if ($root["__children"]) {
          foreach($root["__children"] as $child){
            $this->nested_array_loop_without($options, $child, $entityId);
          }
        }
      }
      return $options;
    }
  }


  /**
   * Get Ansesstors.
   * @param integer $entityId
   * @param integer $depth
   * @param bool $includeNode
   * @param integer $hydration
   */
  public function getAncestors($entityId, $depth = null, $includeNode = FALSE, $hydration = Doctrine_Core::HYDRATE_ARRAY_HIERARCHY) {
   if (is_array($entityId)) {
      $entityId = $entityId['id'];
    }
    $table = Doctrine::getTable($this->entityName);
    $q = $this->getBaseQuery();
    $q->setHydrationMode($hydration);
    $table->getTree()->setBaseQuery($q);
    $entity = $table->findOneById($entityId);
    if ($entity) {
      return $entity->getNode()->getAncestors($depth, $includeNode);
    }
    return array();
  }

  /**
   * Get Descendants.
   * @param integer $entityId
   * @param integer $depth
   * @param bool $includeNode
   * @param integer $hydration
   */
  public function getDescendants($entityId, $depth = null, $includeNode = FALSE, $hydration = Doctrine_Core::HYDRATE_ARRAY_HIERARCHY) {
    if (is_array($entityId)) {
      $entityId = $entityId['id'];
    }
    $table = Doctrine::getTable($this->entityName);
    $q = $this->getBaseQuery();
    $q->setHydrationMode($hydration);
    $table->getTree()->setBaseQuery($q);
    $entity = $table->findOneById($entityId);
    if ($entity) {
      return $entity->getNode()->getDescendants($depth, $includeNode);
    }
    return array();
  }

  /**
   * GetLastDescendants
   * @param integer $entityId
   * @param integer $hydration
   * @return array of Entity
   */
  public function getLastDescendants($entityId, $what = 'e.*', $hydration = Doctrine_Core::HYDRATE_ARRAY) {
    if (is_array($entityId)) {
      $entityId = $entityId['id'];
    }
    $table = Doctrine::getTable($this->entityName);
    $q = $this->getBaseQuery($what);
    $q->setHydrationMode($hydration);
    $entity = $table->findOneById($entityId);
    $q->addWhere('lft + 1 = rgt');
    $table->getTree()->setBaseQuery($q);
    if ($entity) {
      return $entity->getNode()->getDescendants(null, FALSE);
    }
    return array();
  }


  /**
   * Get Children.
   * @param integer $entityId
   * @param integer $hydration
   */
  public function getChildren($entityId, $hydration = Doctrine_Core::HYDRATE_ARRAY_HIERARCHY) {
    if (is_array($entityId)) {
      $entityId = $entityId['id'];
    }
    $table = Doctrine::getTable($this->entityName);
    $q = $this->getBaseQuery();
    $q->setHydrationMode($hydration);
    $table->getTree()->setBaseQuery($q);
    $entity = $table->findOneById($entityId);
    if ($entity) {
      return $entity->getNode()->getChildren();
    }
    return array();
  }

  /**
   * Get MAX priority.
   */
  public function getMaxPriority() {
    $con = Doctrine_Manager::getInstance()->connection();
    $result = $con->fetchAssoc('SELECT MAX(priority) AS pr FROM ' . Doctrine::getTable($this->entityName)->getOption('tableName'));
    return $result[0]['pr'];
  }

  /**
   * Get parent.
   * @param array $entity
   */
  public function getParent($entity) {
    if (is_array($entity)) {
      $this->getTree()->setBaseQuery($this->getBaseQuery());
      $table = Doctrine::getTable($this->entityName);
      $entity = $table->findOneById($entity['id']);
    }
    return $entity->getNode()->getParent();
  }

  /**
   * Insert last child
   * @param array $entity
   * @param integer $parentId
   */
  public function insertLastChild($entity, $parentId) {
    $this->getTree()->setBaseQuery($this->getBaseQuery());
    $table = Doctrine::getTable($this->entityName);
    $parent = $table->findOneById($parentId);
    try {
      $entity->getNode()->insertAsLastChildOf($parent);
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->insertLastChild(" . print_r($entity->toArray(), TRUE). ',' . $parentId . ") - " . $e->getMessage());
      }
    }
  }

  /**
   * Move to last child
   * @param array $entity
   * @param integer $parentId
   */
  public function moveToLastChild($entity, $parentId) {
    $this->getTree()->setBaseQuery($this->getBaseQuery());
    $table = Doctrine::getTable($this->entityName);
    $parent = $table->findOneById($parentId);
    try {
      if (!$parent) {
        throw new Exception('No parent node found with ID = ' . $parentId, 0);
      }
      $entity->getNode()->moveAsLastChildOf($parent);
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->moveToLastChild(" . print_r($entity->toArray(), TRUE). ',' . $parentId . ") - " . $e->getMessage());
      }
    }
  }

  /**
   * Move as next sibling of
   * @param integer $entityId
   * @param integer $otherEntityId
   */
  public function moveAsNextSiblingOf($entityId, $otherEntityId) {
    $this->getTree()->setBaseQuery($this->getBaseQuery());
    $table = Doctrine::getTable($this->entityName);
    $entity = $table->findOneById($entityId);
    $otherEntity = $table->findOneById($otherEntityId);
    $entity->getNode()->moveAsNextSiblingOf($otherEntity);
  }

  /**
   * DeleteById
   * @param integer $entityId
   */
  public function deleteById($entityId) {
    $table = Doctrine::getTable($this->entityName);
    $entity = $table->findOneById($entityId);
    $entity->getNode()->delete();
  }


  /**
   * Gets categories with parentId
   */
  public function getWithParentId() {
    $treeObject = $this->getTree();
    $treeObject->setBaseQuery($this->getBaseQuery());

    $options = array();
    foreach ($treeObject->fetchRoots() as $root) {
      $opts = array('root_id' => $root["root_id"]);
      $tree = $treeObject->fetchTree($opts, Doctrine_Core::HYDRATE_ARRAY_HIERARCHY);
      $tree = $tree[0];
      $options = $this->parent_id_nested_array_loop($array, $tree);
    }
    return $options;
  }

  /**
   * Parent Id Nested array loop
   * @param array $options
   * @param array $root
   * @param integer $entityId
   */
  public function parent_id_nested_array_loop(&$options, $root, $parentId = null){
    if (is_array($root) && (isset($root["0"]) || isset($root["1"])) && !isset($root["id"])) {
        foreach($root as $theOneOfRoots){
          $this->parent_id_nested_array_loop($options, $theOneOfRoots);
        }
    } else {
      $sroot = $root;
      unset($sroot["__children"]);
      $sroot['parent_id'] = $parentId;
      $options[] = $sroot;
      if ($root["__children"]) {
        foreach($root["__children"] as $child){
          $this->parent_id_nested_array_loop($options, $child, $root['id']);
        }
      }
      return $options;
    }
  }




	/**
   * PostProcessLanguageResult.
   * Processes the result of the DQL query and sets the apropriate i18n fields
   * @param array $array
   * @return $array
   */
  public function postProcessLanguageResult($array) {
    if (!empty($this->i18nFields) && !$this->simpleI18n) {
      if ($array && $this->language) {
        if (isset($array[0])) {
          // Array is an array of entities
          foreach ($array as &$ent) {
            $ent = $this->postProcessLanguageResult($ent);
          }
        } else {
          // Array is a single entity
          // Remove the "translations" entity and copy i18fields to entity
          if (isset($array[$this->translationTableAlias])) {
            foreach ($this->i18nFields as $i18Field) {
              $languagesInResult = get_array_vals_by_second_key($array[$this->translationTableAlias], 'language');
              $langKey = array_search($this->language, $languagesInResult);
              if (isset($array[$this->translationTableAlias][$langKey][$i18Field])) {
                $array[$i18Field] = $array[$this->translationTableAlias][$langKey][$i18Field];
              }
            }
            unset($array[$this->translationTableAlias]);
          }

          // Get relations and recursivly process them
          $rels = $this->getRelations();
          if ($rels) {
            foreach ($rels as $alias => $className) {
              if (isset($array[$alias])) {
                $array[$alias] = ManagerHolder::get($className)->postProcessLanguageResult($array[$alias]);
              }
            }
          }
        }
      }
      if ($this->mode == ManagerHolder::MODE_ADMIN) {
        // Get relations and recursivly process them
        $rels = $this->getRelations();
        $defLang = array_search(config_item('language'), config_item('languages'));
        if ($rels) {
          foreach ($rels as $alias => $className) {
            if (isset($array[$alias]) && isset($this->fields[$alias]) && (in_array($this->fields[$alias]['type'], array('select', 'multipleselect', 'multipleselect_chosen')))) {
              ManagerHolder::get($className)->setLanguage($defLang);
              $array[$alias] = ManagerHolder::get($className)->postProcessLanguageResult($array[$alias]);
            }
          }
        }
      }
    }

    if (!empty($this->i18nFields) && $this->simpleI18n) {
      if ($array && $this->language) {
        if (isset($array[0])) {
          // Array is an array of entities
          foreach ($array as &$ent) {
            $ent = $this->postProcessLanguageResult($ent);
          }
        } else {
          if ($this->mode != ManagerHolder::MODE_ADMIN) {
            foreach ($this->i18nFields as $i18Field) {
              if (isset($array[$i18Field]) && isset($array[$i18Field . '_' . $this->language]) && !empty($array[$i18Field . '_' . $this->language])) {
                $array[$i18Field] = $array[$i18Field . '_' . $this->language];
              } else {
                if (strpos($i18Field, '.') !== FALSE) {
                  $alieses = $this->getForeignKeys();
                  if (isset($alieses[strtok($i18Field, '.')]) && isset($array[strtok($i18Field, '.')])) {
                    $array[strtok($i18Field, '.')] = ManagerHolder::get($alieses[strtok($i18Field, '.')]['class'])->postProcessLanguageResult($array[strtok($i18Field, '.')]);
                  }
                }
              }
            }
            if (isset($array['__children']) && !empty($array['__children'])) {
              $array['__children'] = $this->postProcessLanguageResult($array['__children']);
            }
          }
        }
      }
    }
    return $array;
  }




  /**
   * Preocess dependencies
   * @param Entity $entity
   */
  protected function processDependencies($entity) {

    // FUCK IT. NO Dependencies for multy-lingual websites.
    if (!empty($this->i18nFields) && !$this->simpleI18n) {
      return $entity;
    }

    // NO server dependencies for can_be_deleted = FALSE
    if (isset($entity['can_be_deleted']) && $entity['can_be_deleted'] == FALSE) {
      return $entity;
    }

    $requiredFields = $this->getRequiredFields();
    $dependantFields = array_merge($requiredFields['thatDepend'], $requiredFields['thatTotallyDepend']);
    if (!empty($dependantFields)) {
      foreach ($dependantFields as $k => $depField) {
        if (in_array($depField, $this->i18nFields)) {
          unset($dependantFields[$k]);
        }
      }
    }

    if ($entity && !empty($dependantFields)) {
      if (!is_array($entity)) {
        $entityArr = $entity->toArray();
      } else {
        $entityArr = array_make_nested($entity);
      }

      // Dependand fields
      foreach ($dependantFields as $depField) {

        $totally = in_array($depField, $requiredFields['thatTotallyDepend']);

        // Get depend field(s) into an array
        $dependsOnFields = $this->fields[$depField]['attrs']['depends'];
        $sep = isset($this->fields[$depField]['attrs']['dependsSeparator']) ? $this->fields[$depField]['attrs']['dependsSeparator'] : ' ';
        if (strpos($dependsOnFields, ',') !== FALSE) {
          $dependsOnFields = explode(',', $dependsOnFields);
          foreach ($dependsOnFields as &$dof) {
            $dof = trim(str_replace(strtolower($this->entityName) . '_', '', $dof));
          }
        } else {
          $dependsOnFields = array(trim(str_replace(strtolower($this->entityName) . '_', '', $dependsOnFields)));
        }

        // Concatenate value
        $value = '';
        foreach ($dependsOnFields as $dependField) {
          if ((isset($entityArr[$dependField]) && !empty($entityArr[$dependField])) || isset($this->fields[$dependField]['attrs']['default'])) {
            $val = (isset($entityArr[$dependField]) && !empty($entityArr[$dependField])) ? $entityArr[$dependField] : $this->fields[$dependField]['attrs']['default'];
            $value .= $val . $sep;
          }
        }
        $value = rtrim($value, $sep);

        // Set dependant values
        if (isset($this->fields[$depField]['attrs']['startwith'])) {
          if ($totally || (empty($entityArr[$depField]) || !isset($entityArr[$depField]) || $entityArr[$depField] == $this->fields[$depField]['attrs']['startwith'])) {
            if (isset($this->fields[$depField]['attrs']['translit_ignore'])) {
              $thisUrl = lang_url(trim($value), $this->fields[$depField]['attrs']['startwith'], TRUE);
            } else {
              $thisUrl = lang_url(trim($value), $this->fields[$depField]['attrs']['startwith']);
            }
            if (!is_array($entity)) {
              $parent = $entity->getNode()->getParent();
              if (isset($parent[$depField])) {
                $thisUrl = rtrim($parent[$depField], '/');
                if (isset($this->fields[$depField]['attrs']['translit_ignore'])) {
                  $thisUrl .= lang_url(trim($value), null, TRUE);;
                } else {
                  $thisUrl .= lang_url(trim($value));
                }

              }
            }
            $entityArr[$depField] = $thisUrl;
          }
        } else {
          if (strpos($depField, '.') !== FALSE) {
            $val = get_nested_array_value_by_key_with_dots($entityArr, $depField);
            if ($totally || $val === null || empty($val)) {
              $array[$depField] = trim($value);
              $array = array_make_nested($array);
              $entityArr = array_merge_recursive_distinct($entityArr, $array);
            }
          } else {
            if ($totally || (empty($entityArr[$depField]) || !isset($entityArr[$depField]))) {
              $entityArr[$depField] = trim($value);
            }
          }
        }
      }

      if (is_array($entity)) {
        $entity = $entityArr;
      } else {
        $entity->synchronizeWithArray($entityArr);
      }
    }

    return $entity;
  }

}
