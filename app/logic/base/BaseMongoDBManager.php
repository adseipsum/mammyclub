<?php

/**
 * BaseMongoDBManager
 * @author Itirra - www.itirra.com
 */
abstract class BaseMongoDBManager {

  const DUPLICATE_KEY_ON_INSERT_ERROR_CODE = 11000;
  const DUPLICATE_KEY_ON_UPDATE_ERROR_CODE = 11001;

  /** @var string */
  protected $configKey = 'default';

  /** @var Mongo */
  protected $mongo;

  /** @var MongoDB */
  protected $db;

  /** @var MongoCollection */
  protected $collection;

  /** @var String */
  protected $entityName;

  /** Primary Key Field. */
  protected $pk = 'id';

  /** @var String */
  protected $orderBy = '';

  /** Name field. */
  protected $nameField = "name";

  /** @var search. */
  protected $search;

  /** Shard Key */
  // TODO: move this to $fields (and yamls ?)
  protected $shardKey;

  /** @var bool */
  protected $strictMode = FALSE;

  /** Cache. */
  protected $cache;

  /** Language. */
  protected $language;

  /** Collection name */
  protected $collectionName;

  /** Default timeout (ms) */
  protected $defaultTimeout = 600000;

  /**
   * BaseMongoDBManager
   */
  public function BaseMongoDBManager($dbConfig = NULL) {
    $this->entityName = str_replace("Manager", "", get_class($this));

    if(!$dbConfig) {
      require APPPATH . 'config/database.php';
      $dbConfig = $db;
    }

    // Cache
    if (isset($this->CACHE_GROUP_KEY)) {
      $CI =& get_instance();
      $CI->load->library("common/cache");
      $this->cache = new Cache();
    }

    $table = $this->getDoctrineTable();
    $this->collectionName = $table->getTableName();

    $this->mongo = $this->getMongo($dbConfig);
    $this->db = $this->selectDB($dbConfig);
    $this->collection = $this->selectCollection($this->collectionName);

//    @MongoCursor::$timeout = $this->defaultTimeout;
//    MongoCursor::timeout (-1);
//    MongoCursor::$slaveOkay = TRUE;
  }


  //#################################### GET MANY METHODS ##############################################


  /**
   * getAll
   * @param $what
   * @param $count
   * @param $withoutIds
   */
  public function getAll($what = '*', $count = null, $withoutIds = array()) {
    $cursor = $this->getQueryCursor(array(), $what, $count);
    $entities = $this->getDataFromCursor($cursor, $what, TRUE);
    $entities = $this->fetchRelationsForEntities($entities, $what);
    return $entities;
  }


  /**
   * getAllWithPager
   * @param $page
   * @param $perPage
   * @param string $what
   * @param array $withoutIds
   * @return stdClass
   */
  public function getAllWithPager($page, $perPage, $what = '*', $withoutIds = array()) {
    require_once APPPATH . "/libraries/common/MyPager.php";
    $pager = new MyPager($page, $perPage);

    $cursor = $this->getQueryCursor(array(), $what, $perPage, $pager->getOffset());

    $pager->setTotalResults($cursor->count());

    $entities = $this->getDataFromCursor($cursor, $what, TRUE);
    $entities = $this->fetchRelationsForEntities($entities, $what);

    $result = new stdClass();
    $result->data = $entities;
    $result->pager = $pager;

    return $result;
  }


  /**
   * getAllWhere
   * @param $keyValueArray
   * @param $what
   * @param $limit
   * @param $withoutIds
   * @param $offset
   * @param $groupBy
   * @param $having
   */
  public function getAllWhere($keyValueArray, $what = '*', $limit = null, $withoutIds = array(), $offset = null, $groupBy = null, $having = null) {
    $cursor = $this->getQueryCursor($keyValueArray, $what, $limit, $offset);
    $entities = $this->getDataFromCursor($cursor, $what, TRUE);
    $entities = $this->fetchRelationsForEntities($entities, $what);
    $entities = $this->processRelationWhere($entities, $keyValueArray);
    $entities = $this->processRelationOrderBy($entities);
    return $entities;
  }


  /**
   *
   * getAllWhereWithPager
   * @param $keyValueArray
   * @param $page
   * @param $perPage
   * @param $what
   * @param $withoutIds
   * @param $groupBy
   * @param $having
   * @return stdClass
   */
  public function getAllWhereWithPager($keyValueArray, $page, $perPage, $what = '*', $withoutIds = array(), $groupBy = null, $having = null) {
    require_once APPPATH . "/libraries/common/MyPager.php";
    $pager = new MyPager($page, $perPage);

    $hasRelationWhere = FALSE;
    foreach ($keyValueArray as $k => $v) {
      if (strpos($k, '.') !== FALSE) {
        $hasRelationWhere = TRUE;
        break;
      }
    }

    if (strpos($this->orderBy, '.') !== FALSE) {
      $hasRelationWhere = TRUE;
    }

    if ($hasRelationWhere) {
      $cursor = $this->getQueryCursor($keyValueArray, $what);
      $entities = $this->getDataFromCursor($cursor, $what, TRUE);
      $entities = $this->fetchRelationsForEntities($entities, $what);
      $entities = $this->processRelationWhere($entities, $keyValueArray);
      $entities = $this->processRelationOrderBy($entities);
      $pager->setTotalResults(count($entities));
      $entities = array_slice($entities, $pager->getOffset(), $perPage);
    } else {
      $cursor = $this->getQueryCursor($keyValueArray, $what, $perPage, $pager->getOffset());
      $pager->setTotalResults($cursor->count());
      $entities = $this->getDataFromCursor($cursor, $what, TRUE);
      $entities = $this->fetchRelationsForEntities($entities, $what);
      $entities = $this->processRelationWhere($entities, $keyValueArray);
    }

    $result = new stdClass();
    $result->data = $entities;
    $result->pager = $pager;

    return $result;
  }


  /**
   * Method for getting array of IDs given $keyValueArray as condition.
   * Method  can return MongoIDs as well as IDs as a strings
   *
   * @param $keyValueArray - Conditions for rows to return
   * @param bool $returnStringIds - Method  can return MongoIDs as well as IDs as a strings
   * @param null $limit
   * @param null $offset
   * @return array
   */
  public function getAllIdsWhere($keyValueArray, $returnStringIds = FALSE, $limit = null, $offset = null) {
    // pre-processing conditions array
    $keyValueArray = $this->preProcessKeyValueArray($keyValueArray);
    if ($this->search) {
      $searchWhere = $this->processSearch();
      $keyValueArray = array_merge($keyValueArray, $searchWhere);
    }

    // iterating by $cursor
    $cursor = $this->collection->find($keyValueArray, array('id'))->limit($limit)->skip($offset);
    $data = array();
    $cursor->rewind();
    while ($cursor->hasNext()) {
      if ($returnStringIds) {
        $data[] = $cursor->key();
      } else {
        $data[] = new MongoId($cursor->key());
      }
      $cursor->next();
    }

    // $cursor reset and unset for free memory
    $cursor->reset();
    unset($cursor);
    return $data;
  }


  /**
   * Method for getting array of Distinct key values from a collection given $keyValueArray as condition.
   * Method  can return MongoIDs as well as IDs as a strings
   * @param $fieldName - name of Distinct key field
   * @param $keyValueArray - Conditions for rows to return
   * @param bool $returnStringIds - Method  can return MongoIDs as well as IDs as a strings
   * @return array
   */
  public function getFieldValuesDistinct($fieldName, $keyValueArray = array(), $returnStringIds = FALSE) {
    // pre-processing conditions array
    $keyValueArray = $this->preProcessKeyValueArray($keyValueArray);
    if ($this->search) {
      $searchWhere = $this->processSearch();
      $keyValueArray = array_merge($keyValueArray, $searchWhere);
    }

    // iterating by $cursor
    $data = $this->collection->distinct($fieldName, $keyValueArray);

    if (!$returnStringIds) {
      foreach ($data as &$v) {
        $v = new MongoId($v);
      }
    }
    return $data;
  }

  /**
   * Method for getting array of Distinct key values from a collection given $keyValueArray as condition.
   * Method  can return MongoIDs as well as IDs as a strings
   * @param $fieldName - name of Distinct key field
   * @param $keyValueArray - Conditions for rows to return
   * @param bool $returnStringIds - Method  can return MongoIDs as well as IDs as a strings
   * @return array
   */
  public function getFieldValuesDistinct2($fieldName, $keyValueArray = array(), $returnStringIds = FALSE) {
    // pre-processing conditions array
    $keyValueArray = $this->preProcessKeyValueArray($keyValueArray);
    if ($this->search) {
      $searchWhere = $this->processSearch();
      $keyValueArray = array_merge($keyValueArray, $searchWhere);
    }

    $data = array();
    $limit = 300000;
    for ($i = 0; $i >= 0; $i += $limit) {
      $skip = $i;
      $pipeline = array(
      array('$match' => $keyValueArray),
      array('$project' => array($fieldName => 1)),
      array('$group' => array('_id' => '$' . $fieldName)),
      array('$skip' => $skip),
      array('$limit' => $limit),
      );

      $result = $this->db->command(array("aggregate" => $this->collectionName, "pipeline" => $pipeline, "allowDiskUse" => TRUE));
      if (!empty($result)) {
        $result = $result['result'];
        $dataChunk = get_array_vals_by_second_key(array_values($result), '_id');
        foreach ($dataChunk as &$v) {
          $v = new MongoId($v);
        }
        $data = array_merge($data, $dataChunk);
      } else {
        break;
      }
      if (count($dataChunk) < $limit) {
        break;
      }
    }

    if (!$returnStringIds) {
      foreach ($data as &$v) {
        $v = new MongoId($v);
      }
    }
    return $data;
  }


  //#################################### GET ONE METHODS ##############################################

  /**
   * getById
   * @param $id
   * @param $what
   */
  public function getById($id, $what = '*') {
    try {
      $whereArray = array('_id' => new MongoId($id));
    } catch (Exception $e) {
      log_message('error', $e->getCode() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
      return array();
    }
    $whatArray = $this->getWhatArray($what);
    $entityArr = $this->collection->findOne($whereArray, $whatArray);

    if (!$entityArr) {
      return FALSE;
    }

    $entityArr = $this->postProcessResultSet($entityArr, $whatArray);

    $entity = new $this->entityName;
    $this->unsetEmptyRelations($entityArr);
    $entity->fromArray($entityArr);
    $entity->assignIdentifier($entityArr['id']);
    $entityArr = $entity->toArray();
    $entity->free(TRUE);
    unset($entity);
    $entityArr = $this->fetchRelationsForEntity($entityArr, $what);

    return $entityArr;
  }


  /**
   * getOneWhere
   * @param $keyValueArray
   * @param string $what
   * @return array
   */
  public function getOneWhere($keyValueArray, $what = '*') {
    $whatArray = $this->getWhatArray($what);
    $keyValueArray = $this->preProcessKeyValueArray($keyValueArray);
    if ($this->orderBy) {
      $orderBy = $this->preProcessOrderBy();
      $entityArr = $this->collection->find($keyValueArray, $whatArray)->limit(1)->sort($orderBy);
      $entityArr = iterator_to_array($entityArr);
      if (empty($entityArr)) {
        return FALSE;
      } else {
        $entityArr = array_pop($entityArr);
      }
    } else {
      $entityArr = $this->collection->findOne($keyValueArray, $whatArray);
    }
    if (!$entityArr) {
      return FALSE;
    }
    $entityArr = $this->postProcessResultSet($entityArr, $whatArray);
    $entity = new $this->entityName;
    $this->unsetEmptyRelations($entityArr);
    $entity->fromArray($entityArr);
    $entity->assignIdentifier($entityArr['id']);
    $entityArr = $entity->toArray();
    $entity->free(TRUE);
    unset($entity);
    $entityArr = $this->fetchRelationsForEntity($entityArr, $what);
    return $entityArr;
  }

  /**
   * Get by regexp
   * @param string $field - name of field
   * @param string $regexp - regex [example: "[А-Я]"]
   * @param array $addWhere - dont use $field
   * @param array $ignoreElements
   */
  public function getByRegexp($field, $regexp, $addWhere = array(), $ignoreElements = null) {
    $keyValueArray = array();

    if (!empty($addWhere)) {
      unset($addWhere[$field]);
      $keyValueArray = $this->preProcessKeyValueArray($addWhere);
    }
    if ($ignoreElements !== null) {
      $keyValueArray[$field]['$nin'] = $ignoreElements;
    }

    $keyValueArray[$field]['$regex'] = new MongoRegex("/" . $regexp . "/");
    $cursor = $this->collection->find($keyValueArray);
    $data = iterator_to_array($cursor);
    $data = $this->postProcessResultSet($data, array(), TRUE);
    return $data;
  }

  //#################################### COUNT METHODS ##############################################


  /**
   * getCount
   * @return int
   */
  public function getCount() {
    return $this->collection->count();
  }


  /**
   * getCountWhere
   * @param $keyValueArray
   * @return int
   */
  public function getCountWhere($keyValueArray, $limit = null, $splitKey = '_id') {
    $result = 0;
    $keyValueArray = $this->preProcessKeyValueArray($keyValueArray);
    $chunkSize = 400000;

    if (isset($keyValueArray[$splitKey]) && is_array($keyValueArray[$splitKey]) && isset($keyValueArray[$splitKey]['$in']) && count($keyValueArray[$splitKey]['$in']) > $chunkSize) {
      $newKeyValueArray = $keyValueArray;
      for($i = 0; $i <= count($keyValueArray[$splitKey]['$in']); $i = $i + $chunkSize) {
        $count = 0;
        $newKeyValueArray[$splitKey]['$in'] = array_slice($keyValueArray[$splitKey]['$in'], $i, $chunkSize);
//        $count = $this->collection->find($newKeyValueArray)->explain();
//        $count = isset($count['n']) ? $count['n'] : 0;
        $pipeline = array(
          array('$match' => $newKeyValueArray),
          array('$group' => array('_id' => NULL,
                                  'count' => array('$sum' => 1)))
        );
        $queryResult = $this->db->command(array("aggregate" => $this->collectionName, "pipeline" => $pipeline));
        if (isset($queryResult['result']) && isset($queryResult['result'][0]) && isset($queryResult['result'][0]['count'])) {
          $count = $queryResult['result'][0]['count'];
        }
        $result += $count;
      }
    } else {
//      $cursor = $this->collection->find($keyValueArray);
//      $queryResult = $cursor->explain();
//      $cursor = null;
//      unset($cursor);
//      if (isset($queryResult['n'])) {
//        $result = $queryResult['n'];
//      }
//      $queryResult = null;
//      unset($queryResult);
      $pipeline = array(
        array('$match' => $keyValueArray),
        array('$group' => array('_id' => NULL,
                                'count' => array('$sum' => 1)))
      );
      $queryResult = $this->db->command(array("aggregate" => $this->collectionName, "pipeline" => $pipeline));
      if (isset($queryResult['result']) && isset($queryResult['result'][0]) && isset($queryResult['result'][0]['count'])) {
        $result = $queryResult['result'][0]['count'];
      }
      $queryResult = null;
      unset($queryResult);
    }
    return $result;
  }


  //#################################### EXIST METHODS ############################################


  /**
   * ExistsWhere.
   * Check whether entity exists.
   * @param array $keyValueArray
   * @return bool
   */
  public function existsWhere($keyValueArray) {
    $count = $this->getCountWhere($keyValueArray, 1);
    return ($count > 0);
  }

  //#################################### INSERT METHODS ############################################

  /**
   * insert
   * @param entity|array $entity
   * @return mixed
   * @throws Exception
   */
  public function insert($entity) {
    if ($entity instanceof Doctrine_Record) {
      $entity = $entity->toArray();
    } else {
      $newEntity = new $this->entityName();
      $newEntity->fromArray($entity);
      $entity = $newEntity->toArray();
    }

    $entity = $this->preProcessInputData($entity);

    try {
      $this->preInsert($entity);
      $this->collection->insert($entity, array('w' => 1));
      $this->postInsert($entity);
    } catch (Exception $e) {
      if ($e->getCode() == self::DUPLICATE_KEY_ON_INSERT_ERROR_CODE) {
        // for compatibility with the mysql duplicate error
        log_message('error', $e->getCode() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        throw new Exception($e->getMessage(), DOCTRINE_DUPLICATE_ENTRY_EXCEPTION_CODE, $e);
      } else {
        log_message('error', $e->getCode() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        throw $e;
      }
    }
    return (string)$entity['_id'];
  }


  //#################################### UPDATE METHODS ############################################


  /**
   * update
   * @param $entity
   */
  public function update($entity) {
    if ($entity instanceof Doctrine_Record) {
      $entity = $entity->toArray();
    }
    $entity = $this->removeRelationsData($entity);
    $entity = $this->preProcessInputData($entity);
    $this->replaceIdWithMongoId($entity);

    $id = $entity['_id'];
    unset($entity['_id']);

    $this->do_update(array('_id' => $id), $entity);
  }


  /**
   * updateById
   * @param $id
   * @param $key
   * @param $value
   */
  public function updateById($id, $key, $value) {
    $entity = array($key => $value);
    $entity = $this->preProcessInputData($entity);
    $this->do_update(array('_id' => new MongoId($id)), $entity);
  }


  /**
   * UpdateWhere
   * @param array $keyValueArray
   * @param string $key
   * @param mixed $value
   */
  public function updateWhere($keyValueArray, $key, $value) {
    $keyValueArray = $this->preProcessKeyValueArray($keyValueArray);
    $entity = array($key => $value);
    $entity = $this->preProcessInputData($entity);
    $this->do_update($keyValueArray, $entity, TRUE);
  }


  /**
   * UpdateAllWhere
   * @param array $keyValueArray
   * @param array $entityArray
   */
  public function updateAllWhere($keyValueArray, $entityArray) {
    $keyValueArray = $this->preProcessKeyValueArray($keyValueArray);
    $entityArray = $this->preProcessInputData($entityArray);
    $this->do_update($keyValueArray, $entityArray, TRUE);
  }


  /**
   * do_update
   * Core update method
   * @param $keyValueArray
   * @param $entity
   */
  protected function do_update($keyValueArray, $entity, $multipleUpdate = FALSE) {
    if ($this->shardKey) {
      $entity = $this->removeShardKey($entity);
    }

    try {
      $this->collection->update($keyValueArray, array('$set' => $entity), array('w'=> 1, 'multiple' => $multipleUpdate));
      $this->postUpdate($keyValueArray, $entity);
    } catch (Exception $e) {
      if ($e->getCode() == self::DUPLICATE_KEY_ON_UPDATE_ERROR_CODE) {
        // for compatibility with the mysql duplicate error
        log_message('error', $e->getCode() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        throw new Exception($e->getMessage(), DOCTRINE_DUPLICATE_ENTRY_EXCEPTION_CODE, $e);
      } else {
        log_message('error', $e->getCode() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        throw $e;
      }
    }
  }

  //#################################### DELETE METHODS ############################################

  /**
   * deleteById
   * @param $id
   */
  public function deleteById($id) {
    $this->collection->remove(array('_id' => new MongoId($id)), array('w' => 1));
  }


  /**
   * deleteAllWhere
   * @param $keyValueArray
   * @param $pref
   */
  public function deleteAllWhere($keyValueArray, $pref = "e") {
    $keyValueArray = $this->preProcessKeyValueArray($keyValueArray);
    $this->collection->remove($keyValueArray, array('w' => 1));
  }


  //#################################### GETTERS & SETTERS ##############################################


  /**
   * setStrictMode
   * @param bool $enable
   */
  public function setStrictMode($enable) {
    $this->strictMode = $enable;
  }


  /**
   * Set Order By.
   * @param string $orderBy
   */
  public function setOrderBy($orderBy) {
    $this->orderBy = $orderBy;
  }


  /**
   * Get Order By.
   * @return string $orderBy
   */
  public function getOrderBy() {
    return $this->orderBy;
  }


  /**
   * getPreProcessedOrderBy
   * @return array
   */
  public function getPreProcessedOrderBy() {
    return $this->preProcessOrderBy();
  }


  /**
   * Get Name Field.
   * @return string nameField
   */
  public function getNameField() {
    return $this->nameField;
  }


  /**
   * Get primary key.
   * @return string $pk
   */
  public function getPk() {
    return $this->pk;
  }

  /**
   * Set search params.
   * @param string $searchStr
   * @param string $searchField
   * @param string $searchType
   */
  public function setSearch($searchStr, $searchField, $searchType = SEARCH_TYPE_CONTAINS) {
    if (empty($searchStr) || empty($searchField)) {
      return;
    }
    $this->search = array();
    $this->search['string'] = $searchStr;
    $this->search['field'] = $searchField;
    $this->search['type'] = $searchType;
  }


  //#################################### GETTERS ##############################################

  /**
   * getMongoDB
   * @return MongoDB
   */
  public function getMongoDB() {
    return $this->db;
  }


  /**
   * getMongoCollection
   * @return MongoCollection
   */
  public function getMongoCollection() {
    return $this->collection;
  }


  //#################################### MISC PUBLIC METHODS ############################################

  /**
   * createEntityFromArray
   * @param $array
   * @param string $mode
   * @return mixed
   */
  public function createEntityFromArray($array, $mode = '') {
    $entity = new $this->entityName;

    if (isset($array[$this->pk])) {
      $oldEntity = $this->getById($array[$this->pk], 'e.*');
      $array = array_merge($oldEntity, $array);
      $entity->fromArray($array);
      $entity->assignIdentifier($array[$this->pk]);
    } else {
      $entity->fromArray($array);
    }

    return $entity;
  }


  /**
   * Create Entity From POST
   * @return object
   */
  public function createEntityFromPOST() {
    return $this->createEntityFromArray($_POST);
  }


  /**
   * getFieldInfo
   */
  public function getFieldInfo($fieldName) {
    $result = Doctrine_Core::getTable($this->entityName)->getColumnDefinition($fieldName);
    return $result;
  }


  /**
   * GetForeignKeys.
   * Gets an array of all entity foreign keys
   * Ex. array('alias' => array('class' => 'ClassName',
   *                            'local' => 'local key field name',
   *                            'foreign' => 'foreign key field name',
   *                            'type' => '0,1 (one, many)'));
   * @return array
   */
  public function getForeignKeys() {
    $result = array();
    $rels = Doctrine_Core::getTable($this->entityName)->getRelations();
    foreach ($rels as $alias => $class) {
      $result[$alias] = array('class' => $class->getClass(),
                              'local' => $class->getLocal(),
                              'foreign' => $class->getForeign(),
                              'type' => $class->getType());
    }
    return $result;
  }


  /**
   * GetRelations.
   * Gets an array of all relations
   * Ex. array('alias' => 'ClassName');
   * @return array
   */
  public function getRelations() {
    $result = array();
    $rels = Doctrine_Core::getTable($this->entityName)->getRelations();
    foreach ($rels as $alias => $class) {
      $result[$alias] = $class->getClass();
    }
    return $result;
  }


  /**
   * Filter Values
   * @param string $filterName
   * @return array
   */
  public function getFilterValues($filterName) {
    // Check for field in field list
    if (isset($this->fields[$filterName])) {
      if ($this->fields[$filterName]['type'] == 'checkbox') {
        return array("1" => lang("admin.yes"), "0" => lang("admin.no"));
      }
      if ($this->fields[$filterName]['type'] == 'enum') {
        return $this->getEnumAsViewArray($filterName);
      }
      show_error('Filter field "' . $filterName . '" is not a bool or enum');
    } else {
      // If relation
      if (strpos($filterName, '.')) {
        $alias = strtok($filterName, '.');
        $relations = $this->getRelations();
        if (isset($relations[$alias])) {
          $values = array();
          if (isset($this->fields[$alias])) {
            if (!isset($this->fields[$alias]['class']) || (isset($this->fields[$alias]['class']) && strpos($this->fields[$alias]['class'], 'required') === FALSE)) {
              $values = array('NULL' => lang('admin.not_set'));
            }
          }
          $viewArray = ManagerHolder::get($relations[$alias])->getAsViewArray(array(), ManagerHolder::get($relations[$alias])->getNameField());
          if (!empty($values)) {
            $values = $values + $viewArray;
          } else {
            $values = $viewArray;
          }
          return $values;
        }
        show_error('Filter field "' . $filterName . '" is not a relation');
      }
    }
    show_error('Filter field "' . $filterName . '" is not a relation or bool or checkbox');
  }


  /**
   * GetEntityPageNum.
   * @param integer $id
   * @param integer $perPage
   * @return integer
   */
  public function getEntityPageNum($id, $perPage) {
    return 1;
    $entityPos = 0;

    $cursor = $this->getQueryCursor();

    if ($cursor) {
      $i = 0;
      foreach ($cursor as $entity) {
        if ($entity['_id']->{'$id'} == $id) {
          $entityPos = $i;
          break;
        }
      }
    }

    return floor($entityPos / $perPage) + 1;
  }


  //#################################### ENUM ##############################################

  /**
   * Get field enum values from the model.
   * @param string $field
   * @return array
   */
  public function getEnumValues($field) {
    $result = array();
    $fieldDef = Doctrine_Core::getTable($this->entityName)->getColumnDefinition($field);
    if ($fieldDef['type'] == 'enum') {
      if (!isset($fieldDef['notnull'])) {
        $result[''] = '';
      }
      $result = array_merge($result, $fieldDef['values']);
    }
    return $result;
  }

  /**
   * Get field enum values from the model.
   * @param string $field
   * @return view array
   */
  public function getEnumAsViewArray($field) {
    $values = $this->getEnumValues($field);
    $result = array();
    foreach ($values as $val) {
      $result[$val] = lang('enum.' . strtolower($this->entityName) . '.' . $field . '.' . $val);
    }
    return $result;
  }


  //#################################### SEARCH ##############################################

  /**
   * processSearch
   */
  private function processSearch() {
    if (!isset($this->search['field']) || empty($this->search['field']) &&
      !isset($this->search['type']) || empty($this->search['type']) &&
      !isset($this->search['string']) || empty($this->search['string'])
    ) {
      return array();
    }

    $fieldsArray = explode(',', $this->search['field']);

    switch ($this->search['type']) {
      case "starts_with":
      {
        $searchValue = new MongoRegex("/^" . preg_quote($this->search['string'], "/") . "/i");
        break;
      }
      case "contains":
      {
        $searchValue = new MongoRegex("/" . preg_quote($this->search['string'], "/") . "/i");
        break;
      }
      case "ends_with":
      {
        $searchValue = new MongoRegex("/" . preg_quote($this->search['string'], "/") . "$/i");
        break;
      }
    }

    // clear search params
    $this->search = array();

    if (count($fieldsArray) == 1) {
      $field = array_pop($fieldsArray);
      return array($field => $searchValue);
    }

    $orWhereArray = array();
    $searchWhere = array();
    foreach ($fieldsArray as $field) {
      $orWhereArray[] = array($field => $searchValue);
    }
    $searchWhere['$or'] = $orWhereArray;

    return $searchWhere;
  }


  //#################################### ORDER BY ##############################################

  /**
   * preProcessOrderBy
   */
  private function preProcessOrderBy() {
    $result = array();
    $sortArray = explode(',', $this->orderBy);
    foreach ($sortArray as $sortFieldStr) {
      $sortFieldStr = trim($sortFieldStr);
      $sortFieldArray = explode(' ', $sortFieldStr);

      if (isset($sortFieldArray[1]) && strtoupper($sortFieldArray[1]) == 'DESC') {
        $sortFieldArray[1] = -1;
      } else {
        $sortFieldArray[1] = 1;
      }
      $result[$sortFieldArray[0]] = $sortFieldArray[1];
    }
    return $result;
  }


  //#################################### RELATIONS METHODS ############################################

  /**
   * fetchRelationsForEntity
   * @param $entity
   * @param $what
   */
  protected function fetchRelationsForEntity($entity, $what) {
    $relations = $this->getForeignKeys();
    if ($relations) {
      foreach ($relations as $alias => $fkInfo) {
        if ($this->whatContainsAlias($alias, $what)) {
          // what contains alias
          $fkLocal = $fkInfo['local'];
          if ($fkInfo['type'] == 0) {
            // many-to-one
            if (isset($entity[$fkLocal]) && !empty($entity[$fkLocal])) {
              // entity contains local fk value
              // TODO: maybe check for type & implement many-to-many relations
              $relatedEntityName = $fkInfo['class'];
              $relatedEntity = ManagerHolder::get($relatedEntityName)->getById($entity[$fkLocal], 'e.*');
              $entity[$alias] = $relatedEntity;
            }
          }
        }
      }
    }
    return $entity;
  }


  /**
   * fetchRelationsForEntities
   * @param $entities
   * @param $what
   */
  protected function fetchRelationsForEntities($entities, $what) {
    $relations = $this->getForeignKeys();

    if ($relations) {
      foreach ($relations as $alias => $fkInfo) {
        if ($this->whatContainsAlias($alias, $what)) {
          // $what contains alias
          $fkLocal = $fkInfo['local'];
          $fkForeign = $fkInfo['foreign'];
          $ids = array();
          foreach ($entities as $e) {
            if (!empty($e[$fkLocal])) {
              $ids[] = $e[$fkLocal];
            }
          }

          if ($ids) {
            $relationWhat = $this->getRelationWhat($alias, $what);
            $relationWhat .= ',' . $fkForeign;
            $relatedEntityName = $fkInfo['class'];

            $manager = ManagerHolder::get($relatedEntityName);
            if($manager instanceof BaseTreeManager) {
              ManagerHolder::get($relatedEntityName)->setHydration(Doctrine_Core::HYDRATE_ARRAY);
            }

            $relatedEntities = ManagerHolder::get($relatedEntityName)->getAllWhere(array($fkForeign => $ids), $relationWhat);

            if ($relatedEntities) {
              foreach ($entities as &$e) {

                foreach ($relatedEntities as $relEntity) {
                  if (array_key_exists($fkLocal, $e) && array_key_exists($fkForeign, $relEntity)) {
                    if ($e[$fkLocal] == $relEntity[$fkForeign]) {
                      if ($fkInfo['type'] == 1) {
                        // one-to-many
                        $e[$alias][] = $relEntity;
                      } else {
                        $e[$alias] = $relEntity;
                      }
                    }
                  }
                }

              }
              // you should always unset foreach key used by reference
              // if you're going to use it again
              unset($e);
            }
          }
        }
      }
    }
    return $entities;
  }


  /**
   * whatContainsAlias
   * @param $alias
   * @param $what
   * @return bool
   */
  protected function whatContainsAlias($alias, $what) {
    if (is_array($what)) {
      $what = implode(',', $what);
    }
    $alias = preg_quote($alias, '@');
    return $what == '*' || preg_match("@(^|[,\s])$alias([\s.,]|$)@", $what);
  }


  /**
   * getRelationWhat
   * @param $alias
   * @param $what
   * @return string
   */
  protected function getRelationWhat($alias, $what) {
    $result = '';
    if (is_array($what)) {
      $what = implode(',', $what);
    }
    $alias = preg_quote($alias, '@');
    preg_match_all("@(?<=[\s,]|^)$alias\.([^,\s]+)@", $what, $matches);
    foreach ($matches[1] as $match) {
      if ($match == '*') {
        $result .= 'e.*,';
      } else {
        $result .= $match . ',';
      }
    }
    $result = rtrim($result, ',');
    return $result;
  }


  //#################################### CORE METHODS ##############################################


  /**
   * getQueryCursor
   * @param array $keyValueArray
   * @param string $what
   * @param null $limit
   * @param null $offset
   * @return MongoCursor
   */
  public function getQueryCursor($keyValueArray = array(), $what = '*', $limit = NULL, $offset = NULL) {
    $keyValueArray = $this->preProcessKeyValueArray($keyValueArray);

    if ($this->search) {
      $searchWhere = $this->processSearch();
      $keyValueArray = array_merge($keyValueArray, $searchWhere);
    }

    $relations = $this->getForeignKeys();
    $hasRelationWhere = FALSE;
    if ($relations) {
      foreach ($keyValueArray as $key => $value) {
        foreach ($relations as $alias => $fkInfo) {
          if (preg_match("/^{$alias}\./", $key)) {
            unset($keyValueArray[$key]);
            $hasRelationWhere = TRUE;
          }
        }
      }
    }

    $whatArray = $this->getWhatArray($what);
    $cursor = $this->collection->find($keyValueArray, $whatArray)->limit($limit)->skip($offset);
    if ($this->orderBy && !$hasRelationWhere) {
      $orderBy = $this->preProcessOrderBy();
      $cursor->sort($orderBy);
    }

    return $cursor;
  }


  /**
   * getWhatArray
   * @param $what
   * @return array
   */
  protected function getWhatArray($what) {
    if ($what == '*') {
      return array();
    }
    if (is_array($what)) {
      $what = implode(',', $what);
    }
    $whatContainsE = preg_match("@(^|[,\s])e\.\*([\s,]|$)@", $what);
    if ($whatContainsE) {
      // what contains 'e.*'
      return array();
    }
    $whatArray = explode(',', $what);
    $relations = $this->getForeignKeys();
    foreach ($whatArray as $key => &$whatValue) {
      $whatValue = trim($whatValue);
      // processing relation aliases
      if (strpos($whatValue, '.') !== FALSE || isset($relations[$whatValue])) { // user.* || user
        unset($whatArray[$key]);

        // adding 'local' field to $what
        // e.g. for "test.*" -> adding "test_id"
        $alias = preg_replace("/\.(.*)/", '', $whatValue);
        $whatArray[] = $relations[$alias]['local'];
      }
    }

    $whatArray = array_values($whatArray);

    return $whatArray;
  }


  /**
   * preProcessKeyValueArray
   * @param $keyValueArray
   */
  protected function preProcessKeyValueArray($keyValueArray) {
    if (empty($keyValueArray)) return array();

    $table = $this->getDoctrineTable();

    $newKeyValueArray = $keyValueArray;
    foreach ($keyValueArray as $key => $value) {
      $fieldType = $table->getTypeOf($key);
      $fieldDef = $table->getDefinitionOf($key);

      $doProcessValue = TRUE;
      $keyWasReplaced = FALSE;

      //============ SPECIAL QUERIES ===========

      // between
      if (strpos($key, 'BETWEEN') !== FALSE) {
        $newKey = trim(str_replace('BETWEEN', '', $key));
        $fieldType = $table->getTypeOf($newKey);
        $fieldDef = $table->getDefinitionOf($newKey);

        $bounds = explode('AND', $value);
        $minValue = trim($bounds[0]);
        $maxValue = trim($bounds[1]);

        if (!$fieldType) {
          if (is_numeric($minValue) && is_numeric($maxValue)) {
            $minValue = (int)$minValue;
            $maxValue = (int)$maxValue;
          } elseif (DateTime::createFromFormat(DOCTRINE_DATE_FORMAT, $minValue) !== FALSE && DateTime::createFromFormat(DOCTRINE_DATE_FORMAT, $maxValue) !== FALSE) {
            // it's a date
            $fieldType = 'datetime';
          }
        }

        $minValue = $this->preProcessValue($minValue, $fieldType, $fieldDef);
        $maxValue = $this->preProcessValue($maxValue, $fieldType, $fieldDef);

        $value = array('$lte' => $maxValue, '$gte' => $minValue);
        $newKeyValueArray = $this->setNewWhereKey($newKeyValueArray, $key, $newKey, $value);

        $keyWasReplaced = TRUE;
        $doProcessValue = FALSE;
      }

      // >, <
      $pattern = '/((>=?)|(<=?)|(<>)|(\$exists))$/';
      if (preg_match($pattern, $key, $matches)) {
        $newKey = trim(preg_replace($pattern, '', $key));
        $fieldType = $table->getTypeOf($newKey);
        $fieldDef = $table->getDefinitionOf($newKey);

        $sign = $matches[0];

        $operators = array('>' => '$gt',
                           '<' => '$lt',
                           '<=' => '$lte',
                           '>=' => '$gte',
                           '<>' => '$ne',
                           '$exists' => '$exists'
        );

        if (!isset($operators[$sign])) {
          $e = new Exception('Key is not implemented in preProcessKeyValueArray');
          log_message('error', $e->getCode() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
          throw $e;
        }
        $operator = $operators[$sign];

        if ($sign == '$exists') {
          $value = (bool)$value;
        } else {
          $value = $this->preProcessValue($value, $fieldType, $fieldDef);
        }
        $value = array($operator => $value);

        // $ne + $in = $nin
        if (is_array($value) && array_key_exists('$ne', $value) && is_array($value['$ne']) && array_key_exists('$in', $value['$ne'])) {
          $value = array('$nin' => $value['$ne']['$in']);
        }

        $newKeyValueArray = $this->setNewWhereKey($newKeyValueArray, $key, $newKey, $value);
        $keyWasReplaced = TRUE;
        $doProcessValue = FALSE;
      }

      // boolean

      if ($fieldType == 'boolean' && !$keyWasReplaced && !$newKeyValueArray[$key]) {
        unset($newKeyValueArray[$key]);
        $value = $this->preProcessValue($value, $fieldType, $fieldDef, $key);
        $newKeyValueArray = $this->addOrWhere($newKeyValueArray, $value);
        $doProcessValue = FALSE;
      }

      // OR
      if ($key == 'OR') {
        if (!is_array($value)) {
          $e = new Exception('Value should be an array when using OR');
          log_message('error', $e->getCode() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
          throw $e;
        }
        $orWhereArray = array();
        foreach ($value as $field => $val) {
          if(is_array($val)) {
            $where = $this->preProcessKeyValueArray($val);
          } else {
            $where = $this->preProcessKeyValueArray(array($field => $val));
          }
          $orWhereArray[] = $where;
        }

        unset($newKeyValueArray['OR']);
        $newKeyValueArray = $this->addOrWhere($newKeyValueArray, $orWhereArray);

        $doProcessValue = FALSE;
      }

      //========== KEY PROCESSING ===========

      // replacing id
      if ($key == 'id' || (isset($newKey) && $newKey == 'id')) {
        if (is_array($value)) {
          foreach ($value as &$id) {
            $id = new MongoId($id);
          }
        } else {
          $value = new MongoId($value);
        }
        unset($newKeyValueArray['id']);
        if (is_array($value) && !$keyWasReplaced) {
          $newKeyValueArray['_id'] = array('$in' => array_values($value));
        } else {
          $newKeyValueArray['_id'] = $value;
        }

        $key = '_id';
        $doProcessValue = FALSE;
      }


      //========== VALUE PROCESSING ===========

      if ($doProcessValue) {
        $newKeyValueArray[$key] = $this->preProcessValue($value, $fieldType, $fieldDef, $key);
      }

    }
    return $newKeyValueArray;
  }

  /**
   * Process where condition for entities relations
   * @param array $entities
   * @param array $keyValueArray
   * @return array
   */
  protected function processRelationWhere($entities, $keyValueArray) {
    $relations = $this->getForeignKeys();

    if(!empty($keyValueArray)) {
      foreach ($entities as $key => $value) {
        $value = array_make_plain_with_dots($value);

        foreach ($keyValueArray as $whereKey => $whereValue) {
          // ralation "where" must contain dot
          if (strpos($whereKey, '.') !== FALSE) {
            list($relation, $field) = explode('.', $whereKey);

            if (!isset($relations[$relation])) {
              continue;
            }

            if (isset($value[$relation]) && is_array($value[$relation])) {
              foreach ($value[$relation] as $v) {
                if (array_key_exists($field, $v)) {
                  if ($v[$field] == $whereValue) {
                    $whereTrue = true;
                    break;
                  }
                }
              }

              if (isset($whereTrue) && $whereTrue) {
                $whereTrue = false;
                continue;
              }
            }

            if (array_key_exists($whereKey, $value)) {
              if ($value[$whereKey] != $whereValue) {
                unset($entities[$key]);
              }
            } else {
              if ($whereValue !== null) {
                unset($entities[$key]);
              }
            }
          }
        }
      }
    }

    return $entities;
  }

  /**
   * Process relation order by
   * @param array $entities
   * @return array
   */
  protected function processRelationOrderBy($entities) {
    if($this->orderBy && strpos($this->orderBy, '.') !== FALSE) {
      $orderBy = $this->preProcessOrderBy();
      foreach ($entities as $key => $value) {
        $value = array_make_plain_with_dots($value);
        foreach ($orderBy as $ok => $ov) {
          if (array_key_exists($ok, $value)) {
            $entities[$key][$ok] = $value[$ok];
          }
        }
      }
      foreach ($orderBy as $ok => $ov) {
        if ($ov === -1) {
          $entities = array_sort($entities, $ok, SORT_DESC);
        }
        if ($ov === 1) {
          $entities = array_sort($entities, $ok, SORT_ASC);
        }
      }
      foreach ($entities as &$e) {
        foreach ($orderBy as $ok => $ov) {
          unset($e[$ok]);
        }
      }
    }

    return $entities;
  }

  /**
   * addOrWhere
   * @param $newKeyValueArray
   * @param $where
   * @return mixed
   */
  private function addOrWhere($newKeyValueArray, $where){
    if(isset($newKeyValueArray['$or'])) {
      $newKeyValueArray['$and'] = array(array('$or' => $newKeyValueArray['$or']), array('$or' => $where));
      unset($newKeyValueArray['$or']);
    } elseif(isset($newKeyValueArray['$and'])) {
      $newKeyValueArray['$and'][] = array('$or' => $where);
    } else {
      $newKeyValueArray['$or'] = $where;
    }
    return $newKeyValueArray;
  }


  /**
   * setNewWhereKey
   * @param $keyValueArray
   * @param $oldKey
   * @param $newKey
   * @param $value
   * @return mixed
   * @throws Exception
   */
  private function setNewWhereKey($keyValueArray, $oldKey, $newKey, $value) {
    unset($keyValueArray[$oldKey]);
    if (isset($keyValueArray[$newKey])) {
      $e = new Exception('Using same key twice. Use "AND" key instead.');
      log_message('error', $e->getCode() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
      throw $e;
    } else {
      $keyValueArray[$newKey] = $value;
    }
    return $keyValueArray;
  }


  /**
   * preProcessValue
   * @param $value
   * @param $fieldType
   * @param $fieldDef
   * @param null $fieldName
   * @return array|bool|MongoDate|MongoInt32|MongoInt64|null
   */
  private function preProcessValue($value, $fieldType, $fieldDef, $fieldName = NULL) {
    if($value === NULL) {
      return NULL;
    }

    if (is_array($value)) {

      // array
      $value = array('$in' => $value);

    } elseif ($fieldType == 'boolean') {

      // bool
      $value = (bool)$value;

      if (!$value) {
        // value does not exist OR equals FALSE
        $value = array(array($fieldName => array('$exists' => FALSE)), array($fieldName => FALSE));
      }

    } elseif ($fieldType == 'datetime' || $fieldType == 'date') {

      // datetime
      $value = new MongoDate(strtotime($value));

    } elseif ($fieldType == 'integer') {

      //integer
      if ($fieldDef['length'] <= 4) {
        //4 bytes = 32 bits
        $value = new MongoInt32($value);
      } else {
        $value = new MongoInt64($value);
      }

    }

    return $value;
  }


  /**
   * removeRelationsData
   * @param $entity
   */
  protected function removeRelationsData($entity) {
    $relations = $this->getRelations();
    foreach ($relations as $alias => $className) {
      if (isset($entity[$alias])) {
        unset($entity[$alias]);
      }
    }
    return $entity;
  }


  /**
   * preProcessInputData
   * @param $data
   * @param $multipleEntities
   */
  protected function preProcessInputData($entity) {
    $table = $this->getDoctrineTable();

    foreach ($entity as $field => &$value) {
      $fieldDefinition = $table->getDefinitionOf($field);
      if ($fieldDefinition) {
        // date
        if ($fieldDefinition['type'] == 'date' || $fieldDefinition['type'] == 'datetime') {
          if ($value) {
            $value = new MongoDate(strtotime($value));
          } else {
            $value = NULL;
          }
        }

        // boolean
        if ($fieldDefinition['type'] == 'boolean') {
          $value = (bool)$value;
        }

        //integer
        if ($fieldDefinition['type'] == 'integer' && $field != 'id') {
          if ($fieldDefinition['length'] <= 4) {
            //4 bytes = 32 bits
            $value = new MongoInt32($value);
          } else {
            $value = new MongoInt64($value);
          }
        }
      }
    }
    return $entity;
  }


  /**
   * postProcessResultSet
   * @param $entities
   */
  protected function postProcessResultSet($data, $whatArray = array(), $multipleEntities = FALSE) {
    if ($multipleEntities) {
      $entities = $data;
    } else {
      $entities = array($data);
    }

    $table = $this->getDoctrineTable();

    foreach ($entities as &$e) {
      $this->replaceMongoIdWithId($e);

      $this->setMissingFields($e, $table, $whatArray);

      foreach ($e as $field => &$value) {
        $fieldType = $table->getTypeOf($field);

        // date post processing
        if ($value instanceof MongoDate) {
          if ($fieldType == 'datetime') {
            $value = date(DOCTRINE_DATE_FORMAT, $value->sec);
          } else {
            $value = date('Y-m-d', $value->sec);
          }
        }

        // boolean
        if ($fieldType == 'boolean') {
          $value = (bool)$value;
        }

      }
    }

    if ($multipleEntities) {
      return $entities;
    }
    return $entities[0];
  }


  /**
   * setMissingFields
   * @param $entity
   * @param $table
   * @param $whatArray
   */
  protected function setMissingFields(&$entity, $table, $whatArray) {
    if ($this->strictMode) {
      foreach($whatArray as $field) {
        if(!isset($entity[$field]) && strpos($field, '.') === FALSE) {
          // native field, not a relation
          $entity[$field] = NULL;
        }
      }
    } else {
      $fields = $table->getFieldNames();
      foreach ($fields as $field) {
        if (!isset($entity[$field])) {
          $entity[$field] = NULL;
        }
      }
    }
  }


  /**
   *
   * replaceIdWithMongoId
   * @param $entity
   */
  protected function replaceIdWithMongoId(&$entity) {
    $entity['_id'] = new MongoId($entity['id']);
    unset($entity['id']);
  }

  /**
   *
   * replaceMongoIdWithId
   * @param $entity
   */
  protected function replaceMongoIdWithId(&$entity) {
    $entity['id'] = (string)$entity['_id'];
    unset($entity['_id']);
  }


  /**
   * getDataFromCursor
   * @param MongoCursor $cursor
   */
  private function getDataFromCursor(MongoCursor $cursor, $what = NULL, $multipleEntities = FALSE) {
    $data = iterator_to_array($cursor);
    $whatArray = $this->getWhatArray($what);
    return $this->postProcessResultSet($data, $whatArray, $multipleEntities);
  }


  /**
   * removeShardKey
   * @param $entity
   */
  private function removeShardKey($entity) {
    foreach ($entity as $field => $value) {
      if ($field == $this->shardKey) {
        unset($entity[$field]);
        break;
      }
    }
    return $entity;
  }


  /**
   * unsetEmptyRelations
   * @param $entity
   */
  private function unsetEmptyRelations(&$entity) {
    $relations = $this->getForeignKeys();
    if ($relations) {
      foreach ($relations as $alias => $fkInfo) {
        if (array_key_exists($alias, $entity) && empty($entity[$alias])) {
          unset($entity[$alias]);
        }
      }
    }
  }

  /**
   * Get required fields
   * @return array of requred fields
   */
  public function getRequiredFields() {
    $result = array('all' => array(),
                    'simple' => array(),
                    'thatDepend' => array(),
                    'thatTotallyDepend' => array());

    foreach ($this->fields as $k => $v) {
      if (isset($v['class']) && strpos($v['class'], 'required') !== FALSE) {
        $result['all'][] = $k;
        if (isset($v['attrs']['depends'])) {
          if (isset($v['attrs']['readonly'])) {
            $result['thatTotallyDepend'][] = $k;
          } else {
            $result['thatDepend'][] = $k;
          }
        } else {
          $result['simple'][] = $k;
        }
      }
    }
    return $result;
  }


 /**
   * Export
   * @param array $keyValueArray WHERE
   * @param array $what
   * @return array
   */
  public function export($keyValueArray = array(), $what = array()) {
    $result = array();
    if (!is_array($what)) {
      $what = explode(',', $what);
    }
    $sqlWhat = $what;

    // Create relations array to process
    $allRells = $this->getRelations();
    $rells = array();
    foreach ($what as $i => $k) {
      if (isset($allRells[$k])) {
        $rells[$k] = $allRells[$k];
        $sqlWhat[$i] = $k . '.*';
      }
    }

    // Get the entities from DB
    if (!empty($keyValueArray)) {
      $entities = $this->getAllWhere($keyValueArray, $sqlWhat);
    } else {
      $entities = $this->getAll($sqlWhat);
    }


    // Process each enitity
    if (!empty($entities)) {

      // Create Enum array for processing
      $enums = array();
      foreach ($what as $k) {
        if (isset($this->fields[$k]['enum'])) {
          $enums[] = $k;
        }
      }

      // Create Double array for processing
      $doubles = array();
      foreach ($what as $k) {
        if (isset($this->fields[$k]['type']) && $this->fields[$k]['type'] == 'input_double') {
          $doubles[] = $k;
        }
      }

      // Main FOREACH
      foreach ($entities as &$entity) {
        // - replace enumm values with corresponding values
        if (!empty($enums)) {
          foreach ($enums as $ek) {
            $entity[$ek] = lang('enum.' . strtolower($this->entityName) . '.' . $ek . '.' . $entity[$ek]);
          }
        }

        // - replace doubles values with corresponding values
        if (!empty($doubles)) {
          foreach ($doubles as $dk) {
            $entity[$dk] = str_replace('.', ',', strval($entity[$dk]));
          }
        }

        // - replace rells with names of entities
        if (!empty($rells)) {
          foreach ($rells as $alias => $class) {
            $ents = array();
            $nameField = ManagerHolder::get($class)->getNameField();
            if (is_array($nameField)) {
              foreach ($entity[$alias] as $entArr){
                $ent = "";
                foreach ($nameField as $nf) {
                  // Name field value can be an array: ex. array(array("Product" => "code"));
                  if (is_array($nf)) {
                    $nfKey1 = array_keys($nf);
                    $nfKey1 = $nfKey1[0];
                    $nfKey2 = array_values($nf);
                    $nfKey2 = $nfKey2[0];
                    $ent .= $entArr[$nfKey1][$nfKey2] . ':';
                  } else {
                    $ent .= $entArr[$nf] . ':';
                  }
                }
                $ent = rtrim($ent, ':');
                $ents[] = $ent;
              }
            } else {
              if (!empty($entity[$alias])) {
                if (isset($entity[$alias][0])) {
                  $ents = get_array_vals_by_second_key($entity[$alias], $nameField);
                } else {
                  $ents = array($entity[$alias][$nameField]);
                }
              }
            }
            $entity[$alias] = implode(',', $ents);
          }
        }


        $entity = array_make_plain_with_dots($entity);

        // - remove fields that are not in what array
        foreach (array_keys($entity) as $entKey) {
          if (!in_array($entKey, $what)) {
            unset($entity[$entKey]);
          }
        }

      }
      $result = $entities;
    }

    return $result;
  }

  //#################################### HOOKS ##############################################
  /**
   * postUpdate
   * @param $keyValueArray
   * @param $entity
   */
  protected function postUpdate($keyValueArray, $entity) {
  }

  /**
   * postInsert
   * @param $keyValueArray
   * @param $entity
   */
  protected function postInsert($entity) {
  }

  /**
   * preInsert
   * @param $entity
   */
  protected function preInsert(&$entity) {
  }


  //#################################### PRIVATE SERVICE METHODS ##############################################

  /**
   * getDoctrineTable
   * @return Doctrine_Table
   */
  private function getDoctrineTable() {
    return Doctrine_Core::getTable($this->entityName);
  }


  /**
   * getMongo
   * @param $dbConfig
   * @return Mongo
   */
  private function getMongo($dbConfig) {
    if (!isset($dbConfig[$this->configKey]['mongodb'])) {
      die('Please make sure you added "mongodb" config values to the database.php');
    }
    $hosts = $dbConfig[$this->configKey]['mongodb']['host'];
    if(is_array($hosts)) {
      $hostStr = implode(',', $hosts);
    } else {
      $hostStr = $hosts;
    }


    $dbConfig[$this->configKey]['mongodb']['options']['connectTimeoutMS'] = $this->defaultTimeout;
    $dbConfig[$this->configKey]['mongodb']['options']['socketTimeoutMS'] = $this->defaultTimeout;
    if(!empty($dbConfig[$this->configKey]['mongodb']['options'])) {
      return new MongoClient('mongodb://' . $hostStr, $dbConfig[$this->configKey]['mongodb']['options']);
    }  else {
      return new MongoClient ('mongodb://' . $hostStr);
    }
  }


  /**
   * selectDB
   * @param $dbConfig
   * @return MongoDB
   */
  private function selectDB($dbConfig) {
    if (!isset($dbConfig[$this->configKey]['mongodb'])) {
      die('Please make sure you added ["mongodb"]["database"] config value to the database.php');
    }
    $dbName = $dbConfig[$this->configKey]['mongodb']['database'];
    return $this->mongo->selectDB($dbName);
  }


  /**
   * selectCollection
   * @param $collectionName
   * @return MongoCollection
   */
  private function selectCollection($collectionName) {
    return $this->db->selectCollection($collectionName);
  }

  // -----------------------------------------------------------------------------------------
  // ---------------------------------- CACHE Operations -------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Clear cache group.
   * Clears cache group.
   */
  public function clearCacheGroup() {
    if (isset($this->CACHE_GROUP_KEY)) {
      $this->cache->remove_group($this->CACHE_GROUP_KEY);
    }
  }

  /**
   * GetCacheKey.
   * Generates CacheKey based on method name and arguments.
   * $this->CACHE_GROUP_KEY . $methodName . $arguments
   * @param string $methodName
   * @param array $arguments
   * @return string
   */
  protected function getCacheKey($methodName, $arguments) {
    $cacheKey = null;
    if (isset($this->CACHE_GROUP_KEY)) {
      $cacheKey = $this->CACHE_GROUP_KEY . $methodName;
      if (sizeof($arguments) > 0) {
        foreach ($arguments as $arg) {
          if ($arg instanceof Doctrine_Query) {
            $params = $arg->getParams();
            $cacheKey .= $arg->getSqlQuery() . implode(',', $params["where"]);
            continue;
          } else if (is_array($arg)) {
            if (isset($arg[0])) {
              $cacheKey .= implode(",", $arg);
            } else {
              $cacheKey .= implode(",", array_keys($arg)) . implode(",", array_values(flatten_array($arg)));
            }
            continue;
          }
          $cacheKey .= $arg;
        }
      }
      if ($this->language) {
        $cacheKey .= $this->language;
      }
    }
    return $cacheKey;
  }

  /**
   * GetFromCache.
   * Gets data from cache.
   * @param string $methodName
   * @param array $arguments
   * @return mixed
   */
  protected function getFromCache($methodName, $arguments) {
    $cacheKey = $this->getCacheKey($methodName, $arguments);
    if ($cacheKey) {
      if ($this->cache->is_cached($cacheKey, $this->CACHE_GROUP_KEY)) {
        return $this->cache->get($cacheKey, $this->CACHE_GROUP_KEY, TRUE);
      }
    }
    return null;
  }

  /**
   * SaveToCache.
   * Saves data to cache.
   * @param mixed $result
   * @param string $methodName
   * @param array $arguments
   */
  protected function saveToCache($result, $methodName, $arguments, $ttl = null) {
    $cacheKey = $this->getCacheKey($methodName, $arguments);
    if ($cacheKey) {
      $this->cache->save($cacheKey, $result, $this->CACHE_GROUP_KEY, $ttl);
    }
  }

}
