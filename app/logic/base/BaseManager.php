<?php
/**
 * The New BaseManager
 * Class to manage Logic.
 * @author Alexei Chizhmakov (Itirra - www.itirra.com)
 * @author Yuriy Manoylo (Itirra - www.itirra.com)
 */
abstract class BaseManager {

  /** Throw exceptions */
  protected $throwExceptions = TRUE;

  /** Entity name */
  protected $entityName;

  /** Language. */
  protected $language;

  /** i18nFields. */
  public $i18nFields = array();

  /** TranslationTableAlias . */
  public $translationTableAlias = 'translations';

  /** Primary Key Field. */
  protected $pk = 'id';

	/** Name field. */
  protected $nameField = "name";

  /** Cache. */
  protected $cache;

  /** Order By. */
  protected $orderBy = null;

  /** Search Str. */
  protected $searchStr = null;

  /** Search Field. */
  protected $searchField = null;

  /** Search Type. */
  protected $searchTypes = array("starts_with", "contains", "ends_with");

  /** Search Type. */
  protected $searchType = null;

  /** Search word orders. */
  protected $searchWordOrders = FALSE;

  /** Simple i18n. If TRUE - i18n logic will just get "_{LANGUAGE}" fields for i18n fields */
  protected $simpleI18n = FALSE;

  /** Default hydration. */
  protected $defaultHydration = Doctrine_Core::HYDRATE_ARRAY;

  /** Mode. */
  protected $mode;

  /**
   * Constructor.
   * @name entity name
   */
  public function BaseManager($name = null, $mode = null) {
    // Name
    if ($name) {
      $this->entityName = $name;
    } else {
      $this->entityName = str_replace("Manager", "", get_class($this));
    }

    // Mode
    if (isset($mode)) {
      $this->setMode($mode);
    }

    // Cache
    if (isset($this->CACHE_GROUP_KEY)) {
      $CI =& get_instance();
      $CI->load->library("common/cache");
      $this->cache = new Cache();
    }

    // Add PK to order by
    if (!($this instanceof BaseTreeManager) && $this->orderBy && !is_array($this->pk) && strpos($this->orderBy, $this->pk) === FALSE) {
      $this->orderBy = $this->orderBy . ',' . $this->pk . ' DESC';
    }
  }

  /**
   * Create Entity From POST
   * @return object
   */
  public function createEntityFromPOST() {
    return $this->createEntityFromArray($_POST);
  }

  /**
   * Create entity from array.
   * @param array $array
   * @return entity
   */
  public function createEntityFromArray($array, $mode = '') {
  	$array = array_make_nested($array);
  	$entity = new $this->entityName;

  	if (is_array($this->pk)) {
  	  if ($mode != 'insert') {
    	  // This is for enitities with multiple primary keys. Like many-to-many tables.
    	  $pkArr = array();
    	  foreach ($this->pk as $onePk) {
    	    if (is_not_empty($array[$onePk])) {
    	      $pkArr[$onePk] = $array[$onePk];
    	      unset($array[$onePk]);
    	    }
    	  }
    	  if (count($pkArr) == count($this->pk)) {
    	    $entity->assignIdentifier($pkArr);
    	  }
  	  }
  	} else {
      if (is_not_empty($array[$this->pk])) {
        if ($mode !== 'insert') {
          $entity->assignIdentifier($array[$this->pk]);
          unset($array[$this->pk]);
        }
      }
  	}

  	if ($mode == 'update' || $mode == '') {
  	  if ($entity[$this->pk]) {
        try {
          $entity->refresh();
        } catch (Exception $e) {
          log_message('error', $e->getMessage());
          if ($this->throwExceptions) {
            throw $e;
          } else {
            show_error(get_class($this) . "->createEntityFromArray[refresh](" . print_r($array, TRUE) . ") - " . $e->getMessage());
          }
        }
  	  }
  	}

    try {
      $entity->synchronizeWithArray($array);
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->createEntityFromArray[synchronizeWithArray](" . print_r($array, TRUE) . ") - " . $e->getMessage());
      }
    }
    return $entity;
  }

  //#################################### GET METHODS ############################################

  /**
   * Call Magic Method for issues like
   *
   * $manager->getOneByName('name');
   * $manager->getAllByName('name');
   *
   * @param string $name
   * @param array $arguments
   * @return mixed
   */
  public function __call($name, $arguments) {
    switch ($name) {
      case strpos($name, 'getOneBy') === 0: {
        $key = strtolower(str_replace('getOneBy', '', $name));
        $keyValueArray = array($key => $arguments[0]);
        return $this->getOneWhere($keyValueArray, count($arguments)>1?$arguments[1]:'*');
        break;
      } case strpos($name, 'getAllBy') === 0: {
        $key = strtolower(str_replace('getAllBy', '', $name));
        $keyValueArray = array($key => $arguments[0]);
        return $this->getAllWhere($keyValueArray, isset($arguments[1])?$arguments[1]:'*', isset($arguments[2])?$arguments[2]:null, isset($arguments[3])?$arguments[3]:array());
        break;
      } default: {
       trigger_error('Call to undefined method ' . get_class($this) .  '::' . $name , E_USER_ERROR);
       break;
      }
    }
  }


  /**
   * GetById.
   * @param integer $id
   * @param string $what
   * @return object
   */
  public function getById($id, $what = '*') {
    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    try {
      $tempOrederBy = $this->getOrderBy();
      $this->orderBy = NULL;
      $result = &$this->getOneWhere(array($this->pk => $id), $what);
      $this->orderBy = $tempOrederBy;
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "getById($id, $what) - " . $e->getMessage());
      }
    }
    $this->saveToCache($result, __METHOD__,$args);
    return $result;
  }

  /**
   * Get by id array.
   * @param array $idArray
   * @param string $what
   * @param int $count
   * @param array $withoutIds
   * @return array
   */
  public function getByIdArray($idArray, $what = '*', $count = null, $withoutIds = array()) {
		$args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    try {
      $q = $this->getDQLSelectQuery($what);
      if ($count) {
        $q->limit($count);
      }
      if (!empty($withoutIds)) {
        $q->whereNotIn('e.' . $this->pk, $withoutIds);
      }
      $q->whereIn('e.' . $this->pk, $idArray);
      $result = &$q->execute();
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . 'getAll(' . $what . ', $count) - ' . $e->getMessage());
      }
    }
    $this->saveToCache($result, __METHOD__, $args);
    return $result;
  }

  /**
   * GetOneWhere.
   * @param array $keyValueArray
   * @param string $what
   * @return object
   */
  public function getOneWhere($keyValueArray, $what = '*') {
    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    try {
      $result = &$this->getDQLSelectAllWhereQuery($keyValueArray, $what)->fetchOne();
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->getOneWhere(" . print_r($keyValueArray, TRUE) . ", $what) - " . $e->getMessage());
      }
    }

    $result = $this->postProcessLanguageResult($result);

    $this->saveToCache($result, __METHOD__, $args);
    return $result;
  }

  /**
   * getOneRandom
   * @param string $what
   * @param array $withoutIds
   * @return array
   */
  public function getOneRandom($what = '*', $withoutIds = array()) {
    try {
      $q = $this->addRandomOrder($this->getDQLSelectQuery($what));
      $q->limit(1);
      if (!empty($withoutIds)) {
        $q->whereNotIn("e." . $this->pk, $withoutIds);
      }
      $result = &$q->fetchOne();
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "getOneRandom(" . $what . ") - " . $e->getMessage());
      }
    }
    return $result;
  }

	/**
   * getOneRandomWhere
   * @param string $what
   * @param array $withoutIds
   * @return array
   */
  public function getOneRandomWhere($keyValueArray, $what = '*', $withoutIds = array()) {
    try {
      $q = $this->addRandomOrder($this->getDQLSelectAllWhereQuery($keyValueArray, $what));
      $q->limit(1);
      if (!empty($withoutIds)) {
        $q->whereNotIn("e." . $this->pk, $withoutIds);
      }
      $result = &$q->fetchOne();
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "getOneRandomWhere(" . $what . ") - " . $e->getMessage());
      }
    }
    return $result;
  }

  /**
   * Get next\prev id according to key-value array
   * @param  $currId id of current entity
   * @param  $keyValueArray params (order, joins, criterias, etc)
   * @param bool $next return next or previous id
   * @return mixed id if exists or false if not
   */
  private function getNextPrevId($currId, $keyValueArray = array(), $next = TRUE) {
    $q = &$this->getDQLSelectAllWhereQuery($keyValueArray, "e.id", "e");
    $params = $q->getFlattenedParams();
    $nativeQ = $q->getSqlQuery(array_values($params));
    if (!empty($params)) {
      $nativeQ = str_replace('?', "'%s'", $nativeQ);
      $nativeQ = vsprintf($nativeQ, array_values($params));
    }

    $nativeQ = str_replace('SELECT', 'SELECT @num := @num + 1 AS position,', $nativeQ);

    $alias = $q->getSqlTableAlias('e');
    $idField = $alias . '__id';

    $mark = '-';
    if ($next) {
      $mark = '+';
    }

    $tableName = Doctrine::getTable($this->entityName)->getTableName();

    $positionResult = $this->executeNativeSQL('SET @num = 0; SELECT position
                                               FROM
                                               (' . $nativeQ . ') AS subselect
                                               WHERE ' . $idField . ' = ' . $currId . '
                                               ORDER BY position
                                               LIMIT 1', TRUE);

    $count = $this->getCount('e.id');

    if (empty($positionResult)) {
      return FALSE;
    } else {
      if ($next && $positionResult[0]['position'] == $count) {
        return FALSE;
      }
      if (!$next && $positionResult[0]['position'] == 1) {
        return FALSE;
      }
    }

    $position = $positionResult[0]['position'];
    if ($next) {
      $position = $position + 1;
    } else {
      $position = $position - 1;
    }

    $nativeQ = 'SET @num = 0; SELECT ' . $idField . '
                FROM
                (' . $nativeQ . ') AS subselect
                WHERE position = ' . $position . '
                ORDER BY position';

    $result = $this->executeNativeSQL($nativeQ, TRUE);

    if (empty($result)) {
      return FALSE;
    } else {
      return $result[0][$idField];
    }
  }

  /**
   * Execute native SQL Query
   * @param string $nativeQ
   * @throws Exception
   */
  public function executeNativeSQL($nativeQ, $allowMultipleQueries = FALSE) {
    $resultRows = array();
    $con = Doctrine_Manager::getInstance()->connection();
    $options = $con->getOptions();
    $dsn = $options['dsn'];
    $dsnArr = explode(';', $dsn);
    $hostName = '';
    $dbName = '';
    foreach ($dsnArr as $dsnParam) {
      $dsnParam = explode('=', $dsnParam);
      if (!empty($dsnParam) && $dsnParam[0] == 'mysql:host') {
        $hostName = $dsnParam[1];
      }
      if (!empty($dsnParam) && $dsnParam[0] == 'dbname') {
        $dbName = $dsnParam[1];
      }
      if (!empty($dsnParam) && $dsnParam[0] == 'port') {
        $dbPort = $dsnParam[1];
      }
    }

    if (empty($hostName)) {
      throw new Exception('No valid Mysql hostname in DSN', 100);
    }

    if (empty($dbName)) {
      throw new Exception('No valid Mysql db name in DSN', 100);
    }
//    if (isset($dbPort)) {
//     $hostName .= ":".$dbPort;
//    }

    if ((double)phpversion() < 5.4) {
      $link = mysql_connect($hostName, $options['username'], $options['password']);

      if (!$link) {
        throw new Exception('Could not connect: ' . mysql_error(), 100);
      }

      $dbSelected = mysql_select_db($dbName, $link);

      if (!$dbSelected) {
        throw new Exception('Could not find dababase: ' . mysql_error(), 100);
      }

      mysql_set_charset('utf8', $link);
      if ($allowMultipleQueries && strpos($nativeQ, ';') !== FALSE) {
        $nativeQs = explode(';', $nativeQ);
        foreach ($nativeQs as $nativeQ) {
          $result = mysql_query($nativeQ);
        }
      } else {
        $result = mysql_query($nativeQ);
      }

      if ($result === TRUE) {
        mysql_close($link);
        return $resultRows;
      }

      if (!$result) {
        throw new Exception('Error executing query: ' . $nativeQ . ' : ' . mysql_error(), 100);
      }

      while ($row = mysql_fetch_assoc($result)) {
        $resultRows[] = $row;
      }

      mysql_free_result($result);
      mysql_close($link);
    } else {
      $link = mysqli_connect($hostName, $options['username'], $options['password'], $dbName, $dbPort);

      if (!$link) {
        throw new Exception('Could not connect: ' . mysqli_error($link), 100);
      }

      $dbSelected = mysqli_select_db($link, $dbName);

      if (!$dbSelected) {
        throw new Exception('Could not find dababase: ' . mysqli_error($link), 100);
      }

      mysqli_set_charset($link, 'utf8');
      if ($allowMultipleQueries && strpos($nativeQ, ';') !== FALSE) {
        $nativeQs = explode(';', $nativeQ);
        foreach ($nativeQs as $nativeQ) {
          $result = mysqli_query($link, $nativeQ);
        }
      } else {
        $result = mysqli_query($link, $nativeQ);
      }

      if ($result === TRUE) {
        mysqli_close($link);
        return $resultRows;
      }

      if (!$result) {
        throw new Exception('Error executing query: ' . $nativeQ . ' : ' . mysqli_error($link), 100);
      }

      while ($row = mysqli_fetch_assoc($result)) {
        $resultRows[] = $row;
      }

      mysqli_free_result($result);
      mysqli_close($link);
    }
    return $resultRows;
  }


  /**
   *
   * Get next id
   * @param  $currId
   * @param  $keyValueArray
   * @return mixed
   */
  public function getNextWhereId($currId, $keyValueArray) {
    return $this->getNextPrevId($currId, $keyValueArray);
  }


  /**
   * get previous id
   * @param  $currId
   * @param  $keyValueArray
   * @return mixed
   */
  public function getPrevWhereId($currId, $keyValueArray) {
    return $this->getNextPrevId($currId, $keyValueArray, FALSE);
  }


  /**
   * GetAll
   * @param string $what
   * @param integers $count
   * @param array $withoutIds
   * @return array
   */
  public function getAll($what = '*', $count = null, $withoutIds = array()) {
    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    try {
      $q = $this->getDQLSelectQuery($what);
      if ($count) {
        $q->limit($count);
      }
      if (!empty($withoutIds)) {
        $q->whereNotIn("e." . $this->pk, $withoutIds);
      }
      $result = &$q->execute();
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "getAll(" . $what . ", $count) - " . $e->getMessage());
      }
    }
    $result = $this->postProcessLanguageResult($result);
    $this->saveToCache($result, __METHOD__, $args);
    return $result;
  }


  /**
   * getAllRandom
   * @param string $what
   * @param integers $count
   * @param array $withoutIds
   * @return array
   */
  public function getAllRandom($what = '*', $count = null, $withoutIds = array()) {
    try {
      $q = $this->addRandomOrder($this->getDQLSelectQuery($what));
      if ($count) {
        $q->limit($count);
      }
      if (!empty($withoutIds)) {
        $q->whereNotIn("e." . $this->pk, $withoutIds);
      }
      $result = &$q->execute();
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "getAllRandom(" . $what . ", $count) - " . $e->getMessage());
      }
    }
    return $result;
  }


  /**
   * getAllRandomWhere
   * @param array $keyValueArray
   * @param string $what
   * @param integers $count
   * @param array $withoutIds
   * @return array
   */
  public function getAllRandomWhere($keyValueArray, $what = '*', $count = null, $withoutIds = null) {
    try {
      $q = $this->addRandomOrder($this->getDQLSelectAllWhereQuery($keyValueArray, $what));
      if ($count) {
        $q->limit($count);
      }
      if (!empty($withoutIds)) {
        $q->whereNotIn("e." . $this->pk, $withoutIds);
      }
      $result = &$q->execute();
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "getAllRandomWhere(" . $what . ", $count) - " . $e->getMessage());
      }
    }
    $result = $this->postProcessLanguageResult($result);
    return $result;
  }


  /**
   * GetAllLike
   * @param string $fields
   * @param string $what
   * @param integer $page
   * @param integer $perPage
   * @param string $what
   * @return array
   */
  public function getAllLike($fields, $like, $page = null, $perPage = null, $what = '*') {
    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    try {
      $q = $this->getDQLSelectQuery($what);
      if (is_array($fields)) {
        $whereStatement = array();
        foreach ($fields as $f) {
          $whereStatement[] = $f . " LIKE  '%" . $like . "%' ";
        }
        $q->addWhere(implode(" OR ", $whereStatement));
      } else {
        $q->addWhere($fields . " LIKE ?", '%' . $like . '%');
      }
      $result = &$this->getWithPager($q, $page, $perPage);
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "getAllLike(" . $like . ", $page) - " . $e->getMessage());
      }
    }
    $this->saveToCache($result, __METHOD__, $args);
    return $result;
  }


  /**
   * @param array $keyValueArray
   * GetAllWhere
   * @param string $what
   * @return array
   */
  public function getAllWhere($keyValueArray, $what = '*', $limit = null, $withoutIds = array(), $offset = null, $groupBy = null, $having = null) {
    $args = func_get_args();
    $orederBy = $this->getOrderBy();
    if(!empty($orederBy)) {
      $args[] = $orederBy;
    }
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    try {
      $q = &$this->getDQLSelectAllWhereQuery($keyValueArray, $what);
      if ($limit) {
        $q->limit($limit);
      }
      if ($groupBy) {
        $q->addGroupBy($groupBy);
      }
      if ($having) {
        $q->addHaving($having);
      }
      if ($offset) {
        $q->offset($offset);
      }
      if (!empty($withoutIds)) {
        $q->whereNotIn("e." . $this->pk, $withoutIds);
      }
      $result = $q->execute();
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->getAllWhere(" . print_r($keyValueArray, TRUE) . ", $what) - " . $e->getMessage());
      }
    }
    $result = $this->postProcessLanguageResult($result);
    $this->saveToCache($result, __METHOD__, $args);
    return $result;
  }


  /**
   * GetAllWhereWithPager.
   * @param array $keyValueArray
   * @param integer $page
   * @param integer $perPage
   * @param string $what
   * @return array
   */
  public function getAllWhereWithPager($keyValueArray, $page, $perPage, $what = '*', $withoutIds = array(), $groupBy = null, $having = null) {
    $args = func_get_args();
    try {
      $q = $this->getDQLSelectAllWhereQuery($keyValueArray, $what);
      if (!empty($withoutIds)) {
        $q->whereNotIn("e." . $this->pk, $withoutIds);
      }
      if ($groupBy) {
        $q->addGroupBy($groupBy);
      }
      if ($having) {
        $q->addHaving($having);
      }
      $result = &$this->getWithPager($q, $page, $perPage);
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "getAllWhereWithPager(" . print_r($keyValueArray, TRUE) . ", $page, $perPage, $what) - " . $e->getMessage());
      }
    }

    $result->data = $this->postProcessLanguageResult($result->data);
    return $result;
  }

  /**
   *
   * getAllWhereWithMyPager
   * @param $keyValueArray
   * @param $page
   * @param $perPage
   * @param $what
   * @param $withoutIds
   * @param $groupBy
   * @param $having
   * @return stdClass
   */
  public function getAllWhereWithMyPager($keyValueArray, $page, $perPage, $what = '*', $withoutIds = array(), $groupBy = null, $having = null) {
    require_once APPPATH . "/libraries/common/MyPager.php";

    $args = func_get_args();
    $orederBy = $this->getOrderBy();
    $search = $this->getSearch();
    if(!empty($orederBy)) {
      $args[] = $orederBy;
    }
    if(!empty($search)) {
      $args[] = $search;
    }
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;

    $pager = new MyPager($page, $perPage);

    try {
      $q = &$this->getDQLSelectAllWhereQuery($keyValueArray, $what);
      if ($perPage) {
        $q->limit($perPage);
      }
      if ($groupBy) {
        $q->addGroupBy($groupBy);
      }
      if ($having) {
        $q->addHaving($having);
      }
      if ($page && $perPage) {
        $q->offset(($page-1)*$perPage);
      }
      if (!empty($withoutIds)) {
        $q->whereNotIn("e." . $this->pk, $withoutIds);
      }
      $entities = $q->execute();

      $tempCGK = NULL;
      if(isset($this->CACHE_GROUP_KEY)) {
        $tempCGK = $this->CACHE_GROUP_KEY;
      }
      $this->CACHE_GROUP_KEY = NULL;
      $count = $this->getCountWhere($keyValueArray, 'id');
      $this->CACHE_GROUP_KEY = $tempCGK;

      $pager->setTotalResults($count);
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->getAllWhere(" . print_r($keyValueArray, TRUE) . ", $what) - " . $e->getMessage());
      }
    }

    $result = new stdClass();
    $result->data = $entities;
    $result->pager = $pager;

    $result = $this->postProcessLanguageResult($result);
    $this->saveToCache($result, __METHOD__, $args);

    return $result;
  }

  /**
   * GetAllWith Pager.
   * Function to get entities for 1 page.
   * Returns object with fields:
   *  1) $result->pager - an instance of Doctrine_Pager
   *  2) $result->data - an instance of Doctrine_Collection
   * @param integer $page
   * @param integer $perPage
   * @param string $what
   * @param array $withoutIds
   * @return array
   */
  public function getAllWithPager($page, $perPage, $what = '*', $withoutIds = array()) {
    $args = func_get_args();
    try {
      $q = $this->getDQLSelectQuery($what);
      if (!empty($withoutIds)) {
        $q->whereNotIn("e." . $this->pk, $withoutIds);
      }
      $result = &$this->getWithPager($q, $page, $perPage);
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "getAllWithPager($page, $perPage, $what) - " . $e->getMessage());
      }
    }
    $result->data = $this->postProcessLanguageResult($result->data);
    return $result;
  }

  /**
   * Get all entities as view array.
   * @param array $withoutIds
   * @param string | array $field
   * Examples: $field = 'title' will return an array like: (id => title);
   *           $field = array('url' => 'title') will return an array like (url => title);
   * @return array
   */
  public function getAsViewArray($withoutIds = array(), $field = 'name', $concatField = null, $keyValueArray = array(), $addWhat = '', $concatSeparator = ',') {
    $result = array();

    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    try {
      if(is_array($field)){
        // getting first (and the only) key
        $key = key($field);
        // getting the value
        $field = current($field);
      } else {
        $key = $this->pk;
      }

      if (!empty($this->i18nFields) && $this->simpleI18n) {
        if (in_array($field, $this->i18nFields) && isset($this->fields[$field . '_' . $this->language])) {
          $field = $field . '_' . $this->language;
        }
      }

      $what = $key . ', ' . $field . ', ' . $concatField;

      if ($addWhat) {
        $what .= ', ' . $addWhat;
      }
      if (!empty($keyValueArray)) {
        foreach ($keyValueArray as $k => $v) {
          $what .= ', ' . $k;
        }
        $q = $this->getDQLSelectAllWhereQuery($keyValueArray, $what);
      } else {
        $q = $this->getDQLSelectQuery($what);
      }
      if (!empty($withoutIds)) {
        $q->whereNotIn("e." . $this->pk, $withoutIds);
      }
      if ($concatField) {
        $q->addSelect("CONCAT_WS('" . $concatSeparator . "', e." . $field . ", e." . $concatField . ") AS " . $field);
      }
      $entities = $q->execute();

      foreach($entities as $entity) {
        if (!isset($entity[$field])) {
          $entity = array_make_plain_with_dots($entity);
        }
        if (!isset($entity[$field])) continue;
        $val = $entity[$field];
        $result[$entity[$key]] = $val;
      }

    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->getAsViewArray(". print_r($withoutIds, TRUE) . ", $field, $concatField) - " . $e->getMessage());
      }
    }
    $this->saveToCache($result, __METHOD__, $args);
    return $result;
  }

  //#################################### PAGER WRAPER METHODS ############################################

  /**
   * Get Pager.
   * Factory method for creating a Doctrine_Pager.
   * @param Doctrine_Query $query
   * @param int $page
   * @param int $perPage
   * @return Doctrine_Pager
   */
  protected function getPager($query, $page, $perPage) {
    return new Doctrine_Pager($query, $page, $perPage);
  }

  /**
   * GetWithPager.
   * @param Doctrine_Query $query
   * @param integer $page
   * @param integer $perPage
   * @return array
   */
  protected function getWithPager($query, $page, $perPage) {
    $result = new stdClass();
    $args = func_get_args();
    try {
      if ($this->orderBy) {
        $query->orderBy($this->orderBy);
      }
      $result->pager = $this->getPager($query, $page, $perPage);
      $result->data = &$result->pager->execute();
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->getWithPager($query, $page, $perPage) - " . $e->getMessage());
      }
    }
    return $result;
  }

  //#################################### COUNT METHODS ############################################

  /**
   * GetCount.
   * @return integer
   */
  public function getCount($what = '*') {
    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    try {
      $tempOrederBy = $this->getOrderBy();
      $this->orderBy = NULL;
      $result = $this->getDQLSelectQuery($what)->count();
      $this->orderBy = $tempOrederBy;
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->getCount($what) - " . $e->getMessage());
      }
    }
    $this->saveToCache($result, __METHOD__, $args);
    return $result;
  }

  /**
   * GetCountWhere.
   * @param string $key
   * @param mixed $value
   * @return integer
   */
  public function getCountWhere($keyValueArray, $what = '*') {
    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    try {
      $tempOrederBy = $this->getOrderBy();
      $this->orderBy = NULL;
      $result =  &$this->getDQLSelectAllWhereQuery($keyValueArray, $what)->count();
      $this->orderBy = $tempOrederBy;
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "getCountWhere(" . print_r($keyValueArray, TRUE) . ") - " . $e->getMessage());
      }
    }
    $this->saveToCache($result, __METHOD__, $args);
    return $result;
  }

  /**
   * GetCountGroupBy.
   * @param string $groupBy
   * @return array
   */
  public function getCountGroupBy($groupBy, $keyValueArray = array(), $what = '*') {
    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    try {
      $q = $this->getDQLSelectAllWhereQuery($keyValueArray, $what);
      $q->select($groupBy . ',COUNT(e.' . $this->pk . ') as count');
      $q->groupBy('e.' . $groupBy);
      $q->orderBy('count DESC');
      $result = $q->execute();
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "getCountGroupBy() - " . $e->getMessage());
      }
    }
    $this->saveToCache($result, __METHOD__, $args);
    return $result;
  }

  /**
   * GetLastPageNum.
   * @param integer $perPage
   * @return integer
   */
  public function getLastPageNum($perPage, $keyValueArray = null) {
    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    if ($keyValueArray) {
      $total = $this->getCountWhere($keyValueArray);
    } else {
      $total = $this->getCount();
    }
    $result = $this->totalPages($total, $perPage);
    $this->saveToCache($result, __METHOD__, $args);
    return $result;
  }

  /**
   * GetEntityPageNum.
   * @param integer $id
   * @param integer $perPage
   * @return integer
   */
  public function getEntityPageNum($id, $perPage, $whereArray = array()) {
    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;

    $result = 1;
    $entities = $this->getAllWhere($whereArray, "id");
    if (!empty($entities) && is_numeric($perPage) && $perPage > 0) {
      $entityPos = 0;
      foreach($entities as $key => $e) {
        if($e['id'] == $id) {
          $entityPos = $key;
          break;
        }
      }
      $result = floor($entityPos / $perPage) + 1;
    }

    $this->saveToCache($result, __METHOD__, $args);
    return $result;
  }

  /**
   * Count Total Pages.
   * @param integer $totalCount
   * @param integer $perPage
   * @return integer
   */
  protected function totalPages($totalCount, $perPage) {
    $dif = $totalCount / $perPage;
    $totalPages = floor($totalCount / $perPage);
    if ($totalPages != $dif || $totalPages == 0) {
      $totalPages += 1;
    }
    return $totalPages;
  }

  /**
   * GetTotalPageCount.
   * @param integer $perPage
   * @return integer
   */
  public function getTotalPageCount($perPage) {
    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    $result = $this->getCount('id');
    $result = $this->totalPages($result, $perPage);
    $this->saveToCache($result, __METHOD__, $args);
    return $result;
  }


  //#################################### EXIST METHODS ############################################

  /**
   * Exists.
   * Check whether entity exists.
   * @param integer $id
   * @return bool
   */
  public function exists($id) {
    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    $result = TRUE;
    if (!is_numeric($id)) {
      $result = FALSE;
    }
    if ((int)$id <= 0) {
      $result = FALSE;
    }
    $where = array($this->pk => $id);
    $count = $this->getCountWhere($where, $this->pk);
    if ($count == 0) {
      $result = FALSE;
    }
    $this->saveToCache($result, __METHOD__, $args);
    return $result;
  }

  /**
   * ExistsWhere.
   * Check whether entity exists.
   * @param array $keyValueArray
   * @return bool
   */
  public function existsWhere($keyValueArray) {
    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    $result = TRUE;
    $count = $this->getCountWhere($keyValueArray, $this->pk);
    if ($count == 0) {
      $result = FALSE;
    }
    return $result;
  }

  //#################################### UPDATE METHODS ############################################

  /**
   * UpdateById
   * @param integer $id
   * @param string $key
   * @param mixed $value
   */
  public function updateById($id, $key, $value) {
    $result = FALSE;
    try {
    	$this->preUpdateWhere(array($this->pk => $id), $key, $value);
    	if ($value === null) {
        $result = $this->getDQLUpdateWhereQuery($this->pk, $id)->set($key, 'NULL')->execute();
      } elseif(is_array($value)) {
        $result = $this->getDQLUpdateWhereQuery($this->pk, $id)->set($key, '?', serialize($value))->execute();
      } else {
        $result = $this->getDQLUpdateWhereQuery($this->pk, $id)->set($key, '?', $value)->execute();
    	}
      if ($result) {
        $keyValueArray = array();
        $keyValueArray[$this->pk] = $id;
        $keyValueArray[$key] = $value;
        $this->postUpdate($keyValueArray);
      }
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->updateById($id, $key, $value) - " . $e->getMessage());
      }
    }
    $this->clearCacheGroup();
    return $result;
  }

  /**
   * UpdateWhere
   * @param array $keyValueArray
   * @param string $key
   * @param mixed $value
   */
  public function updateWhere($keyValueArray, $key, $value) {
    $result = FALSE;
    try {
    	$this->preUpdateWhere($keyValueArray, $key, $value);
    	if ($value === null) {
	      $result = $this->getDQLUpdateAllWhereQuery($keyValueArray)->set($key, 'NULL')->execute();
      } else {
        $result = $this->getDQLUpdateAllWhereQuery($keyValueArray)->set($key, '?', $value)->execute();
      }
      if ($result) {
        $keyValueArray[$key] = $value;
        $this->postUpdate($keyValueArray);
      }
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->updateWhere(" . print_r($keyValueArray, TRUE) . ", $key, $value) - " . $e->getMessage());
      }
    }
    $this->clearCacheGroup();
    return $result;
  }


  /**
   * Update
   * @param entity $entity
   * @param bool $processDependencies
   * @return etnity the updated entity
   */
  public function update($entity, $processDependencies = TRUE) {
    $result = FALSE;
    if (is_array($entity)) {
      if ($processDependencies == TRUE) {
        $entity = $this->processDependencies($entity);
      }
      $entity = $this->createEntityFromArray($entity, 'update');
    } else {
      if ($processDependencies == TRUE) {
        $entity = $this->processDependencies($entity);
      }
    }
    try {
      $this->preUpdate($entity);
      $entity->save();
      $this->postUpdate($entity);
    } catch (Exception $e) {
      $this->onUpdateException($entity->toArray(), $e);
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->update(" . print_r($entity->toArray(), TRUE) . ") - " . $e->getMessage());
      }
    }
    $this->clearCacheGroup();
    return $result;
  }


  /**
  * UpdateAllWhere
  * @param array $keyValueArray
  * @param array $entityArray
  */
  public function updateAllWhere($keyValueArray, $entityArray) {
    $result = FALSE;
    try {
      $q = $this->getDQLUpdateAllWhereQuery($keyValueArray);
      foreach($entityArray as $key => $value){
        $q->set($key, '?', $value);
      }
      $result = $q->execute();
      if ($result) {
        $this->postUpdate($keyValueArray);
      }
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->updateAllWhere(" . print_r($keyValueArray, TRUE) . ", " . print_r($entityArray, TRUE) . ") - " . $e->getMessage());
      }
    }
    $this->clearCacheGroup();
    return $result;
  }

  /**
   * Increment field
   * @param integer $id
   * @param string $key
   */
  public function increment($id, $key, $howMuchToInc = 1) {
    $result = FALSE;
    try {
      $result = $this->getDQLUpdateWhereQuery($this->pk, $id)->set($key, $key . " + " . $howMuchToInc)->execute();
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->increment($id, $key) - " . $e->getMessage());
      }
    }
    $this->clearCacheGroup();
    return $result;
  }

  /**
   * Increment where field
   * @param array $keyValueArray
   * @param string $key
   */
  public function incrementWhere($keyValueArray, $key, $howMuchToInc = 1) {
    $result = FALSE;
    try {
      $result = $this->getDQLUpdateAllWhereQuery($keyValueArray)->andWhere($key . " IS NOT NULL")->set($key, $key . " + " . $howMuchToInc)->execute();
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->incrementWhere(" . print_r($keyValueArray, TRUE) . ", " . $key .") - " . $e->getMessage());
      }
    }
    $this->clearCacheGroup();
    return $result;
  }

  /**
   * Decrement field
   * @param integer $id
   * @param string $key
   */
  public function decrement($id, $key, $howMuchToDec = 1) {
    $result = FALSE;
    try {
      $result = $this->getDQLUpdateWhereQuery($this->pk, $id)->andWhere($key . " > 0")->set($key, $key . " - " . $howMuchToDec)->execute();
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->decrement($id, $key) - " . $e->getMessage());
      }
    }
    $this->clearCacheGroup();
    return $result;
  }

  /**
   * Decrement where field
   * @param array $keyValueArray
   * @param string $key
   */
  public function decrementWhere($keyValueArray, $key, $howMuchToDec = 1) {
    $result = FALSE;
    try {
      $result = $this->getDQLUpdateAllWhereQuery($keyValueArray)->andWhere($key . " > 0")->set($key, $key . " - " . $howMuchToDec)->execute();
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->decrementWhere(" . print_r($keyValueArray, TRUE) . ", " . $key . ") - " . $e->getMessage());
      }
    }
    $this->clearCacheGroup();
    return $result;
  }


  /**
   * PreUpdateWhere.
   * OVERRIDE THIS METHOD IN A SUBCLASS.
   * @param entity $entity
   */
  protected function preUpdate(&$entity) {}

  /**
   * PreUpdateWhere.
   * OVERRIDE THIS METHOD IN A SUBCLASS.
   * @param array $keyValueArray
   * @param string $key
   * @param mixed $value
   */
  protected function preUpdateWhere($keyValueArray, $key, $value) {}

  /**
   * PostUpdate.
   * OVERRIDE THIS METHOD IN A SUBCLASS.
   * @param array $entity
   */
  protected function postUpdate($entity) {}

  /**
   * OnUpdateException.
   * OVERRIDE THIS METHOD IN A SUBCLASS.
   * @param array $entity
   * @param object $exception
   */
  protected function onUpdateException($entity, Exception $exception) {}


  //#################################### INSERT METHODS ############################################

  /**
   * Insert
   * @param entity $entity
   */
  public function insert($entity, $processDependencies = TRUE) {
    $id = null;
    if (is_array($entity)) {
      if ($processDependencies) {
        $entity = $this->processDependencies($entity);
      }
      $entity = $this->createEntityFromArray($entity, 'insert');
    } else {
      if ($processDependencies) {
        $entity = $this->processDependencies($entity);
      }
    }
    $this->preInsert($entity);
    try {
      $entity->save();
      if (!is_array($this->pk) && isset($entity[$this->pk])) {
        $id = $entity[$this->pk];
      } else {
        $id = $entity;
      }
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->insert(" . print_r($entity->toArray(), TRUE) . ") - " . $e->getMessage());
      }
    }
    $this->postInsert($entity);
    $this->clearCacheGroup();
    return $id;
  }

  /**
   * PreInsert.
   * OVERRIDE THIS METHOD IN A SUBCLASS.
   * @param array $entity
   */
  protected function preInsert(&$entity) {}

  /**
   * PostInsert.
   * OVERRIDE THIS METHOD IN A SUBCLASS.
   * @param array $entity
   */
  protected function postInsert($entity) {}

  //#################################### DELETE METHODS ############################################

  /**
   * DeleteById.
   * @param className
   * @param int $entityId
   */
  public function deleteById($id) {
    return $this->deleteWhere($this->pk, $id);
  }

  /**
   * DeleteWhere
   * @param string $key
   * @param mixed $value
   */
  public function deleteWhere($key, $value) {
    $result = TRUE;
    try {
      $this->preDelete(array($key => $value));
      $this->preDeleteFiles(array($key => $value));
      $result = $this->getDQLDeleteWhereQuery($key, $value)->execute();
      if ($result) {
        $this->postDelete(array($key => $value));
      }
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->deleteWhere($key, $value) - " . $e->getMessage());
      }
    }
    $this->clearCacheGroup();
    return $result;
  }

  /**
   * DeleteAll.
   * @return bool
   */
  public function deleteAll() {
    $result = FALSE;
    try {
      $query = $this->getDQLDeleteQuery();
      $this->preDelete(array());
      $result = $query->execute();
      if($result) {
        $this->postDelete(array());
      }
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->deleteAll() - " . $e->getMessage());
      }
    }
    $this->clearCacheGroup();
    return $result;
  }

  /**
   * deleteAllWhere
   * @param array $keyValueArray
   */
  public function deleteAllWhere($keyValueArray, $pref = "e") {
    $result = FALSE;
    try {
      $query = $this->getDQLDeleteQuery($pref);
      $query = $this->processKeyValueArray($query, $keyValueArray);
      $this->preDelete($keyValueArray);
      $this->preDeleteFiles($keyValueArray);
      $result = $query->execute();
      if ($result) {
        $this->postDelete($keyValueArray);
      }
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->deleteAllWhere() - " . $e->getMessage());
      }
    }
    $this->clearCacheGroup();
    return $result;
  }


  /**
   * PostDelete.
   * OVERRIDE THIS METHOD IN A SUBCLASS.
   * @param array $keyValueArray
   */
  protected function postDelete($keyValueArray) {}

  /**
   * PreDelete.
   * OVERRIDE THIS METHOD IN A SUBCLASS.
   * @param array $keyValueArray
   */
  protected function preDelete($keyValueArray) {}


  /**
   * PreDeleteFiles.
   * @param array $keyValueArray
   */
  protected function preDeleteFiles($keyValueArray) {
    if (isset($this->fields)) {

      // Headers
      if (isset($this->fields['header.title'])) {
        $e = $this->getOneWhere($keyValueArray, 'id, header_id');
        ManagerHolder::get('Header')->deleteById($e['header_id']);
      }

      // Files
      $fileKeys = array();
      foreach ($this->fields as $k => $v) {
        if ($v['type'] == 'image') {
          $fileKeys[] = $k;
        }
      }
      if (!empty($fileKeys)) {
        $CI =& get_instance();
        $e = $this->getOneWhere($keyValueArray, 'id, ' . implode('.*, ', $fileKeys) . '.*');
        if (!empty($e)) {
          foreach ($fileKeys as $k) {
            if (!empty($e[$k]) && isset($e[$k]['id'])) {
              $CI->config->load('thumbs');
              $path = realpath($e[$k]['file_path'] . '/' . $e[$k]['file_name']);
              @unlink($path);
              ManagerHolder::get('Resource')->deleteById($e[$k]['id']);
              $thumbs = $CI->config->item(strtolower($this->entityName . '_' . $k), 'thumbs');
              if(!$thumbs){
                // for backward compatibility
                $thumbs = $CI->config->item(strtolower($this->entityName), 'thumbs');
              }
              $thumbs['_admin'] = $CI->config->item('_admin', 'all');
              if ($thumbs) {
                foreach ($thumbs as $thumbName => $wh) {
                  $fName = str_replace($e[$k]['extension'], $thumbName . $e[$k]['extension'], $e[$k]['file_name']);
                  $path = realpath($e[$k]['file_path'] . '/' . $fName);
                  if (@file_exists($path)) {
                    @unlink($path);
                  }
                }
              }
            }
          }
        }
      }
    }
  }


  // -----------------------------------------------------------------------------------------
  // ----------------------------- DQL Query Factory Methods ---------------------------------
  // -----------------------------------------------------------------------------------------


  //###################################### SELECT #############################################

  /**
   * GetDQLSelectQuery.
   * @param string $what
   * @param string $pref
   * @return Doctrine_Query
   */
  protected function getDQLSelectQuery($what = '*', $pref = 'e') {
    $what = $this->preProcessWhat($what);
    $query = Doctrine_Query::create()->select($what)->from($this->entityName . ' ' . $pref)->setHydrationMode($this->defaultHydration);
    if ($this->orderBy) {
      $query->orderBy($this->orderBy);
    }

    if (!empty($this->i18nFields) && !$this->simpleI18n) {
      $query = $this->preProcessLanguageQuery($query, $pref, $what);
    }

    if ($what == '*' || strstr($what, ".") !== FALSE || strstr($this->orderBy, ".") !== FALSE) {
      $query = $this->preProcessWhereQuery($query, $pref, $what);
    }
    $query = $this->processSearch($query, $pref);
    return $query;
  }

  /**
   * PreProcessWhat
   * Process what to find possible relation aliass
   * If found add ".*" to them
   * @param string $what
   * @return string
   */
  public function preProcessWhat($what) {
    if (is_array($what)) {
      if (empty($what)) {
        $what = '*';
      } else {
        $what = implode(',', $what);
      }
    }
    $what = trim($what);
    $what = trim($what, ',');
    if ($what != '*') {
      $whatArr = explode(',', $what);
      foreach ($whatArr as &$wht) {
        if (Doctrine_Core::getTable($this->entityName)->hasRelation($wht)) {
          $wht = $wht . '.*';
        }
      }
      if (strpos($this->orderBy, '.') !== FALSE) {
        if (!isset($whatArr[$this->orderBy])) {
          $whatArr[] = $this->orderBy;
        }
      } else {
        if (!isset($whatArr[$this->orderBy])) {
          $whatArr[] = preg_replace("/\s(asc|desc)/i", '', $this->orderBy);
        }
      }
      $what = implode(',', $whatArr);
    }
    $what = trim($what, ',');
    return $what;
  }

  /**
   * GetDQLSelectWhereQuery.
   * @param string $key
   * @param mixed $value
   * @param string $what
   * @param string $pref
   * @return Doctrine_Query
   */
  protected function getDQLSelectWhereQuery($key, $value, $what = '*', $pref = 'e') {
    return $this->getDQLSelectAllWhereQuery(array($key => $value), $what, $pref);
  }


  /**
   * GetDQLSelectAllWhereQuery.
   * @param array $keyValueArray
   * @param string $what
   * @param string $pref
   * @return Doctrine_Query
   */
  protected function getDQLSelectAllWhereQuery($keyValueArray, $what = '*', $pref = 'e') {
    if (is_array($what)) {
      if (empty($what)) {
        $what = '*';
      } else {
        $what = implode(',', $what);
      }
    }
    if (is_array($keyValueArray) && !empty($keyValueArray) && $what != '*') {
      foreach ($keyValueArray as $k => $v) {
        if (strpos($what, strtok($k, '.')) === FALSE && strpos($k, '(') === FALSE) {
          $what .= ', ' . $k;
        }
      }
    }

    $query = $this->getDQLSelectQuery($what, $pref);

    $query = $this->processKeyValueArray($query, $keyValueArray, $pref);

    return $query;
  }


  /**
   * ProcessKeyValueArray.
   * Method to turn KEY=VALUE array into WHERE parts of a Doctrtine ActiveRecord
   * @param Doctrine_Query $query
   * @param array $keyValueArray
   * @param string $pref
   * @return Doctrine_Query
   */
  protected function processKeyValueArray($query, $keyValueArray, $pref = 'e') {
    if (!is_array($keyValueArray) || empty($keyValueArray)) return $query;
    $defPref = $pref . '.';
    foreach ($keyValueArray as $key => $value) {
      $pref = $defPref;
      if (strstr($key, ".") !== FALSE) {
        $pref = '';
      }
      if (strpos($key, 'OR') !== FALSE) {
        $keysArr = explode('OR', str_replace(' ', '', $key));
        $orQueryPart = '';
        foreach ($keysArr as $i => $k) {
          if (empty($value)) {
            $orQueryPart .= $k . ' IS NULL';
          } else {
            $orQueryPart .= $k . ' = ' . $value;
          }
          if ($i != count($keysArr)-1) {
            $orQueryPart .= ' OR ';
          }
        }
        $query->andWhere($orQueryPart);
      } else if ($value === NULL) {
        $query->andWhere($pref . $key . ' IS NULL');
      } else if (is_array($value)) {
        $query->andWhereIn($pref . $key, $value);
      } else if (strstr($key, "BETWEEN") !== FALSE) {
        $oldKey = $key;
        $key = str_replace("BETWEEN", "", $key);
        $key = trim($key);
        $this->replaceSelectField($query, $oldKey, $key);
        $value = explode("AND", $value);
        $query->andWhere($pref . $key . ' BETWEEN ? AND ?', $value);
      } else if (strstr($key, "<=") !== FALSE) {
        $oldKey = $key;
        $key = str_replace("<=", "", $key);
        $key = trim($key);
        $this->replaceSelectField($query, $oldKey, $key);
        $query->andWhere($pref . $key . ' <= ?', $value);
      } else if (strstr($key, "<>") !== FALSE) {
        $oldKey = $key;
        $key = str_replace("<>", "", $key);
        $key = trim($key);
        $this->replaceSelectField($query, $oldKey, $key);
        if (strcasecmp(trim($value), "NULL") == 0) {
          $query->andWhere($pref . $key . ' IS NOT NULL');
        } else {
          $query->andWhere($pref . $key . ' <> ?', $value);
        }
      } else if (strstr($key, "<") !== FALSE) {
        $oldKey = $key;
        $key = str_replace("<", "", $key);
        $key = trim($key);
        $this->replaceSelectField($query, $oldKey, $key);
        $query->andWhere($pref . $key . ' < ?', $value);
      } else if (strstr($key, ">=") !== FALSE) {
        $oldKey = $key;
        $key = str_replace(">=", "", $key);
        $key = trim($key);
        $this->replaceSelectField($query, $oldKey, $key);
        $query->andWhere($pref . $key . ' >= ?', $value);
      } else if (strstr($key, ">") !== FALSE) {
        $oldKey = $key;
        $key = str_replace(">", "", $key);
        $key = trim($key);
        $this->replaceSelectField($query, $oldKey, $key);
        $query->andWhere($pref . $key . ' > ?', $value);
      } else if (strstr($key, "LIKE") !== FALSE) {
        $oldKey = $key;
        $key = str_replace("LIKE", "", $key);
        $key = trim($key);
        $this->replaceSelectField($query, $oldKey, $key);
        $query->andWhere($pref . $key . ' LIKE ?', $value);
      } else if (strstr($key, "(") !== FALSE) {
        $value = trim($value);
        $query->andWhere($key . ' = ?', $value);
      } else {
        $query->andWhere($pref . $key . ' = ?', $value);
      }
    }
    return $query;
  }


  /**
   * replaceSelectField
   * @param Doctrine_Query $query
   * @param unknown_type $oldField
   * @param unknown_type $newField
   */
  protected function replaceSelectField($query, $oldField, $newField) {
    if ($query->getType() != Doctrine_Query_Abstract::SELECT) {
      return;
    }
    $select = $query->getDqlPart('select');
    if(is_array($select)) {
      $query->removeDqlQueryPart('select');
      foreach($select as &$s) {
        $s = str_replace($oldField, $newField, $s);
      }
      $query->select(implode(', ', $select));
    } else {
      log_message('error', 'select part is not an array. select: ' . $select);
    }
  }



  /**
   * PreProcessWhereQuery.
   * OVERRIDE THIS METHOD IN A SUBCLASS TO ADD joins and other stuff.
   * @param Doctrine_Query $query
   * @return Doctrine_Query
   */
  protected function preProcessWhereQuery($query, $pref, $what = "*") {
    $relations = $this->getRelations();
    foreach ($relations as $alias => $class) {
      // No joins with Mongo!
      if (class_exists($class)) {
        $refl = new ReflectionClass($class);
        $constants = $refl->getConstants();
        if (isset($constants['TYPE']) && strtolower($constants['TYPE']) == 'mongodb') {
          continue;
        }
      }
      if (((strpos($what, $alias . '.') !== FALSE) || $what == '*') && $alias != 'translations') {
        if ($what == '*') {
          $query->addSelect($alias . ".*")->leftJoin($pref . "." . $alias . " " . $alias);
        } else {
          $query->leftJoin($pref . "." . $alias . " " . $alias);
        }
        if (isset($this->fields[$alias]) && $this->fields[$alias]['type'] == 'image_list') {
          $query->addOrderBy($alias . ".priority DESC");
          $query->addSelect($alias . "_img.*")->leftJoin($alias . ".image " . $alias . "_img");
        }
      }
    }

    // Triple "." for admin.
    // Example "drug.corporation.name"
    if ($this->mode == ManagerHolder::MODE_ADMIN) {
      $whatArr = explode(', ', $what);
      foreach ($whatArr as $whatVal) {
        if (substr_count($whatVal, '.') == 2 && strpos($whatVal, ',') === FALSE) {
          $whatValArr = explode('.', $whatVal);
          if ($whatValArr[2] !== '*') {
            $query->addSelect($whatValArr[1] . '.' . $whatValArr[2])->leftJoin($whatValArr[0] . '.' . $whatValArr[1] . ' ' . $whatValArr[1]);
            $parts = $query->getDqlPart('select');
            foreach ($parts as $k => $v) {
              if (strpos($v, $whatVal . ', ') !== FALSE) {
                $v = str_replace($whatVal . ', ', '', $v);
              }
              $parts[$k] = $v;
            }
            $query->removeDqlQueryPart('select');
            foreach ($parts as $k => $v) {
              if ($k == 0) {
                $query->select($v);
              } else {
                $query->addSelect($v);
              }
            }
          }
        }
      }
    }
    return $query;
  }

  /**
   * PreProcessLanguageQuery.
   * @param Doctrine_Query $query
   * @return Doctrine_Query
   */
  protected function preProcessLanguageQuery($query, $pref, $what = "*") {
    if(!empty($this->i18nFields) && !$this->simpleI18n) {
      $ta = $this->translationTableAlias;
      if ($this->language) {
        $select = "";
        $newWhat = $what;
        foreach ($this->i18nFields as $f) {
          if(strpos($f, '.') === FALSE){
            $select .= $pref . "_" . $ta . "." . $f . " AS " . $f . ", ";
          }
          if ($newWhat != '*' && strpos($newWhat, $f) !== FALSE) {
            $newWhat = str_replace($f, '', $newWhat);
          }
        }
        if ($newWhat != $what) {
          $query->removeDqlQueryPart('select');
          $query->select($newWhat);
        }
        $select .= $pref . "_" . $ta . ".language AS language";
        $query->addSelect($select)->leftJoin($pref . "." . $ta . " " . $pref . "_" . $ta);
        $query->addSelect($pref . "_" . $ta . ".*");
        $query->andWhere($pref . "_" . $ta . ".language = ?", $this->language);
      } else {
        if ($this->mode == ManagerHolder::MODE_ADMIN) {
          $query->addSelect("$ta.*")->leftJoin($pref . ".$ta $ta");
        }
      }
    }
    return $query;
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
          }
        }
      }
    }

    return $array;
  }


  //###################################### UPDATE #############################################

  /**
   * GetDQLUpdateQuery.
   * @param string $pref
   * @return Doctrine_Query
   */
  protected function getDQLUpdateQuery($pref = 'e') {
    return Doctrine_Query::create()->update($this->entityName . " " . $pref);
  }

  /**
   * GetDQLUpdateWhereQuery.
   * @param string $key
   * @param mixed $value
   * @param string $pref
   * @return Doctrine_Query
   */
  protected function getDQLUpdateWhereQuery($key, $value, $pref = 'e') {
    return $this->getDQLUpdateQuery($pref)->where($pref . '.' . $key .  ' = ?', $value);
  }

  /**
   * GetDQLSelectAllWhereQuery.
   * @param array $keyValueArray
   * @param string $pref
   * @return Doctrine_Query
   */
  protected function getDQLUpdateAllWhereQuery($keyValueArray, $pref = 'e') {
    $query = $this->getDQLUpdateQuery($pref);
    $query = $this->processKeyValueArray($query, $keyValueArray, $pref);
    return $query;
  }


  //###################################### DELETE #############################################

  /**
   * GetDQLDeleteQuery.
   * @param string $pref
   * @return Doctrine_Query
   */
  protected function getDQLDeleteQuery($pref = 'e') {
    return Doctrine_Query::create()->delete($this->entityName . " " . $pref);
  }

  /**
   * GetDQLDeleteWhereQuery.
   * @param string $key
   * @param mixed $value
   * @param string $pref
   * @return Doctrine_Query
   */
  protected function getDQLDeleteWhereQuery($key, $value, $pref = 'e') {
    return $this->getDQLDeleteQuery($pref)->where($pref . '.' . $key .  ' = ?', $value);
  }

  // -----------------------------------------------------------------------------------------
  // ---------------------------------- UTILITY Operations -------------------------------------
  // -----------------------------------------------------------------------------------------


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
      if (strpos($filterName, '.') !== FALSE) {
        $alias = strtok($filterName, '.');
        $filterName = substr($filterName, strpos($filterName, '.') + 1, strlen($filterName));

        $relations = $this->getRelations();
        if (isset($relations[$alias])) {
          if (strpos($filterName, '.') !== FALSE) {
            return ManagerHolder::get($relations[$alias])->getFilterValues($filterName);
          }
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
   * GetAllFirstLettersWhere
   * @param string $field
   * @param array $keyValueArray
   * @param string $what
   * @return array
   */
  public function getAllFirstLettersWhere($field, $keyValueArray = array(), $what = '*', $toLower = TRUE) {
    $args = func_get_args();
    if ($r = $this->getFromCache(__METHOD__, $args)) return $r;
    try {
      $q = $this->getDQLSelectAllWhereQuery($keyValueArray, $what);
      $q->addSelect('SUBSTRING(e.' . $field . ', 1, 1) AS letter');
      $result = $q->execute();
      $newRes = array();
      foreach ($result as $res) {
        if ($toLower) {
          $res['letter'] = mb_strtolower($res['letter'], 'utf-8');
        }
        $newRes[] = $res['letter'];
      }
      $newRes = array_unique($newRes);
      $result = $newRes;
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      if ($this->throwExceptions) {
        throw $e;
      } else {
        show_error(get_class($this) . "->getAllFirstLettersWhere(" . print_r($keyValueArray, TRUE) . ", $what) - " . $e->getMessage());
      }
    }
    $this->saveToCache($result, __METHOD__, $args);
    return $result;
  }

  /**
   * Set primary key.
   * @param string $pk
   */
  public function setPk($pk) {
    $this->pk = $pk;
  }

  /**
   * Get primary key.
   * @return string $pk
   */
  public function getPk() {
    return $this->pk;
  }

  /**
   * Get Name Field.
   * @return string nameField
   */
  public function getNameField() {
    return $this->nameField;
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
            if (isset($this->fields[$dependField]) && $this->fields[$dependField]['type'] == 'tinymce') {
              $val =  html_entity_decode(strip_tags($val), ENT_QUOTES, 'UTF-8');
            }
            $value .= $val . $sep;
          }

        }
        $value = rtrim($value, $sep);
        $value = trim($value);

        // Set dependant values
        if (isset($this->fields[$depField]['attrs']['startwith'])) {
          if ($totally || (empty($entityArr[$depField]) || !isset($entityArr[$depField]) || $entityArr[$depField] == $this->fields[$depField]['attrs']['startwith']) ) {
            if (isset($this->fields[$depField]['attrs']['translit_ignore'])) {
              $entityArr[$depField] = lang_url($value, $this->fields[$depField]['attrs']['startwith'], TRUE);
            } else {
              $entityArr[$depField] = lang_url($value, $this->fields[$depField]['attrs']['startwith']);
            }

          }
        } else {
          if (isset($this->fields[$depField]['attrs']['maxlength'])) {
            $value = mb_substr($value, 0, $this->fields[$depField]['attrs']['maxlength'], 'UTF-8');
          }
          $value = trim($value);
          if (strpos($depField, '.') !== FALSE) {
            $val = get_nested_array_value_by_key_with_dots($entityArr, $depField);
            if ($totally || $val === null || empty($val)) {
              $array[$depField] = $value;
              $array = array_make_nested($array);
              $entityArr = array_merge_recursive_distinct($entityArr, $array);
            }
          } else {
            if ($totally || (empty($entityArr[$depField]) || !isset($entityArr[$depField]))) {
              $entityArr[$depField] = $value;
            }
          }
        }
      }

      if (is_array($entity)) {
        $entity = $entityArr;
      } else {
        $diff = array_compare($entityArr, $entity->toArray());
        if ($diff) {
          $entity = merge_entity_with_array($entity, $diff[0]);
        }
      }
    }
    return $entity;
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
      if (empty($this->searchStr)) {
        if ($this->cache->is_cached($cacheKey, $this->CACHE_GROUP_KEY)) {
          return $this->cache->get($cacheKey, $this->CACHE_GROUP_KEY, TRUE);
        }
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
  protected function saveToCache($result, $methodName, $arguments) {
    if (ENV == 'DEV') {
      return;
    }
    $cacheKey = $this->getCacheKey($methodName, $arguments);
    if ($cacheKey) {
      if (empty($this->searchStr)) {
        $this->cache->save($cacheKey, $result, $this->CACHE_GROUP_KEY);
      }
    }
  }

  /**
   * Function to enable cache
   * @param string $cacheKey
   */
  public function enableCache($cacheKey) {
    if (!isset($this->CACHE_GROUP_KEY)) {
      $this->CACHE_GROUP_KEY = $cacheKey;
      $CI =& get_instance();
      $CI->load->library("common/cache");
      $this->cache = new Cache();
    }
  }

  /**
   * Function to disable cache
   */
  public function disableCache() {
    if (isset($this->CACHE_GROUP_KEY)) {
      unset($this->CACHE_GROUP_KEY);
    }
    if (isset($this->cache)) {
      unset($this->cache);
    }
  }


  // -----------------------------------------------------------------------------------------
  // ---------------------------------- Order By Operations ----------------------------------
  // -----------------------------------------------------------------------------------------

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

  // -----------------------------------------------------------------------------------------
  // ---------------------------------- Search Operations ------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Set search params.
   * @param string $searchStr
   * @param string $searchField
   * @param string $searchType
   */
  public function setSearch($searchStr, $searchField, $searchType, $wordOrders = FALSE) {
    if (!in_array($searchType, $this->searchTypes)) {
      return;
    }
    if (empty($searchStr) || empty($searchField)) {
      return;
    }
    $this->searchStr = $searchStr;
    $this->searchField = $searchField;
    $this->searchType = $searchType;
    $this->searchWordOrders = $wordOrders;
  }

  /**
   * ProcessSearch
   * @param Doctrine_Query $query
   * @param string $pref
   * @return Doctrine_Query
   */
  public function processSearch($query, $pref = 'e') {
    if (!$this->searchStr
    || !$this->searchField
    || !$this->searchType) {
      return $query;
    }

    if (strstr($this->searchField, ',') !== FALSE) {
      $sField = 'LOWER(CONVERT(CONCAT_WS(\' \'';
      $arr = explode(',', $this->searchField);
      foreach ($arr as $ari) {
        $ari = trim($ari);
        if(strpos($ari, '.') !== FALSE) {
          $sField .= ', ' . $ari;
        } else {
          $sField .= ', ' . $pref . '.' . $ari;
        }
      }
      $sField .= ') USING utf8))';
      $searchField = $sField;
    } else {
      if(strpos($this->searchField, '.') !== FALSE) {
        $searchField = $this->searchField;
      } else {
        $searchField = $pref . "." . $this->searchField;
      }
    }
    // in a case when search params don't contain any fields from a root entity
    // we should add an id to select part
    $query->addSelect('id');
    $this->searchStr = addslashes($this->searchStr);
    switch ($this->searchType) {
      case "starts_with": {
        $query->andWhere($searchField . " LIKE ?",  $this->searchStr . '%');
        break;
      }
      case "contains": {
        if (!$this->searchWordOrders) {
          $query->andWhere($searchField . " LIKE ?", '%' . $this->searchStr . '%');
        } else {
          $words = explode(' ', $this->searchStr);
          foreach ($words as $word) {
            $query->andWhere($searchField . " LIKE ?", '%' . $word . '%');
//             $query->andWhere($searchField . " RLIKE ?", '[[:<:]]' . trim($word) . '[[:>:]]');
          }
        }
        break;
      }
      case "ends_with": {
        $query->andWhere($searchField . " LIKE ?",  '%' . $this->searchStr);
        break;
      }
    }
    return $query;
  }

  /**
   * Clear search params.
   */
  public function clearSearch() {
    $this->searchStr = null;
    $this->searchField = null;
    $this->searchType = null;
    $this->searchWordOrders = FALSE;
  }

  /**
   * Get search params.
   */
  public function getSearch() {
    if (!$this->searchStr || !$this->searchField || !$this->searchType) {
      return NULL;
    }
    return $this->searchStr . '-' . $this->searchField . '-' . $this->searchType;
  }

  // -----------------------------------------------------------------------------------------
  // ---------------------------------- Random Operations ------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * addRandomOrder.
   * @param $query Doctrine_Query
   * @return Doctrine_Query
   */
  protected function addRandomOrder($query) {
    $query->addSelect('RANDOM() AS rand');
    $query->orderBy('rand');
    return $query;
  }

  // -----------------------------------------------------------------------------------------
  // ----------------------------- MIN and MAX Operations ------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * GetMin.
   * @param string Name of field
   * @return integer
   */
  public function getMin($field) {
    $con = Doctrine_Manager::getInstance()->connection();
    $result = $con->fetchAssoc('SELECT MIN(' . $field . ') AS m FROM ' . Doctrine::getTable($this->entityName)->getOption('tableName'));
    return $result[0]['m'];
  }

  /**
   * GetMax.
   * @param string Name of field
   * @param arry $keyValueArray
   * @return integer
   */
  public function getMax($field, $keyValueArray = array(), $pref = 'e') {
    $result = null;
    $query = Doctrine_Query::create()->select('MAX(' . $field . ')')->from($this->entityName . ' ' . $pref)->setHydrationMode($this->defaultHydration);
    if (!empty($keyValueArray)) {
      $query = $this->processKeyValueArray($query, $keyValueArray, $pref = 'e');
    }
    $result = $query->execute();
    if (!empty($result)) {
      $result = $result[0]['MAX'];
      if ($result == '') {
        $result = 0;
      }
    }
    return $result;
  }


  // -----------------------------------------------------------------------------------------
  // ------------------------------- Transaction Operations ----------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Start Transaction
   */
  public function startTransaction() {
    $con = Doctrine_Manager::getInstance()->connection();
    $con->beginTransaction();
  }

  /**
   * Commit transaction
   */
  public function commitTransaction() {
    $con = Doctrine_Manager::getInstance()->connection();
    $con->commit();
  }

  /**
   * Rollback transaction
   */
  public function rollbackTransaction() {
    $con = Doctrine_Manager::getInstance()->connection();
    $con->rollback();
  }

  // -----------------------------------------------------------------------------------------
  // ---------------------------------- Mode Operations --------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Mode getter.
   * @return string
   */
  public function getMode() {
  	return $this->mode;
  }

  /**
   * Mode setter.
   * @return string
   */
  public function setMode($mode) {
    return $this->mode = $mode;
  }

  // -----------------------------------------------------------------------------------------
  // ---------------------------------- Language Operations ----------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Language getter.
   * @return string
   */
  public function getLanguage() {
  	return $this->language;
  }

  /**
   * Language setter.
   * @return string
   */
  public function setLanguage($language) {
    return $this->language = $language;
  }

  // -----------------------------------------------------------------------------------------
  // ------------------------------------ Block Operations -----------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * getBlockData
   * @param array $params
   * @return array
   */
  protected function getBlockData($params = null) {
   return array();
  }

  // -----------------------------------------------------------------------------------------
  // ---------------------------------- Enum Operations --------------------------------------
  // -----------------------------------------------------------------------------------------

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
    foreach ($values as $val){
      $result[$val] = lang('enum.' . strtolower($this->entityName) . '.' . $field . '.' . $val);
    }
    return $result;
  }

  /**
   * Enum value exists.
   * @param $field
   * @param $valueToCheck
   * @return bool
   */
  public function enumValueExists($field, $valueToCheck) {
    $result = false;
    $values = $this->getEnumValues($field);
    if (empty($values)) return $result;
    $result = in_array($valueToCheck, $values);
    return $result;
  }

  // -----------------------------------------------------------------------------------------
  // ---------------------------------- ValidationRule Operations ----------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Get Validation Rules.
   * @param array $exclude
   * @param array $include
   */
  public function getValidationRules($exclude = null, $include = null) {
    if (is_array($include) && count($include) > 1 && is_array($exclude) && count($exclude) > 1) {
      show_error(get_class($this) . "->getValidationRules() - You cannot specify Include and Exclude!");
    }
    $rules = array();
    if (isset($this->fields) && !empty($this->fields)) {
      $fKeys = array_keys($this->fields);
      if ($exclude) {
        $fKeys = array_diff($fKeys, $exclude);
      }
      if ($include) {
        $fKeys = $include;
      }
      foreach ($fKeys as $fKey) {
        $rules[$fKey] = $this->getValidationRule($fKey);
      }
    }
    return $rules;
  }

  /**
   * Get Validation Rules.
   * @param string $fieldName
   */
  public function getValidationRule($fieldName) {
    $rules = array();
    if (isset($this->fields[$fieldName])) {
      $field = $this->fields[$fieldName];
      if (isset($field['class'])) {
        if (strpos($field['class'], 'required') >= 0) {
          $rules[] = 'required';
        }
      }
      if (isset($field['attrs'])) {
        if (isset($field['attrs']['maxlength'])) {
          $rules['maxLength'] = $field['attrs']['maxlength'];
        }
      }
    }
    return $rules;
  }

  // -----------------------------------------------------------------------------------------
  // ----------------------------------- IMPORT/EXPORT Operations ----------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Import
   * @param array $entities
   * @param array $importFilters
   * @throws ErrorException
   */
  public function import($entities, $importFilters = array()) {
    $this->startTransaction();
    $entityObject = new $this->entityName;
    $rowNum = 1;
    try {
      foreach ($entities as $entity) {
        $fields = array_keys($entity);
        $rowNum++;
        foreach ($fields as $key) {
          $fieldAttrs = array();
          if (isset($this->fields[$key])) {
            $fieldAttrs = $this->fields[$key];
          }

          if (isset($fieldAttrs['type'])) {

            // Check for double
            if ($fieldAttrs['type'] == 'input_double') {
              $entity[$key] = (double)str_replace(',', '.', $entity[$key]);
            }

            // Check for double/interger
            if ($entity[$key] === '' && ($fieldAttrs['type'] == 'input_double' || $fieldAttrs['type'] == 'input_integer')) {
              $entity[$key] = null;
            }

            // Check for Enum
            if (isset($fieldAttrs['enum'])) {
              $enumValues = $this->getEnumValues($key);
              foreach ($enumValues as $enval) {
                $lng = lang('enum.' . strtolower($this->entityName) . '.' . $key . '.' . $enval);
                if (trim($entity[$key]) == $lng) {
                  $entity[$key] = $enval;
                  break;
                }
              }
            }

            // Check for multiple select and select
            if (($fieldAttrs['type'] == 'multipleselect' || $fieldAttrs['type'] == 'select') && !empty($entity[$key])) {
              if (!isset($fieldAttrs['enum'])) {
                $values = array();
                if (!isset($importFilters[$key]) || empty($importFilters[$key])) {
                  $resultValues = array();
                  $isOne = FALSE;
                  if (get_class($entityObject[$key]) != 'Doctrine_Collection') {
                    $isOne = TRUE;
                    foreach ($entityObject->getTable()->getRelations() as $relName => $relObject) {
                      if ($key == $relObject->getLocal()) {
                        $refEntityName = $relObject->getTable()->getOption('name');
                        $values = array($entity[$key]);
                        $nameField = ManagerHolder::get($refEntityName)->getNameField();
                        break;
                      }
                    }
                  } else {
                    $refEntityName = $entityObject[$key]->getTable()->getOption('name');
                    $values = explode(',', $entity[$key]);
                    $nameField = ManagerHolder::get($refEntityName)->getNameField();
                  }
                  foreach ($values as $val) {
                    $val = trim($val);
                    if (!empty($val)) {
                      $refEnt = ManagerHolder::get($refEntityName)->getOneWhere(array($nameField => $val), 'id, root_id, lft, rgt, level, ' . $nameField);
                      if ($refEnt) {
                        if (!in_array($refEnt['id'], $resultValues)) {
                          $resultValues[] = $refEnt['id'];
                        }
                      } else {
                        if (ManagerHolder::get($refEntityName) instanceof BaseTreeManager) {
                          $nodeId = ManagerHolder::get($refEntityName)->insert(array($nameField => $val));
                          $resultValues[] = $nodeId;
                          $nodeEnt = ManagerHolder::get($refEntityName)->getFullById($nodeId);
                          ManagerHolder::get($refEntityName)->getTree()->createRoot($nodeEnt);
                          $nodeEnt = $nodeEnt->toArray();
                          if (array_key_exists('priority', $nodeEnt)) {
                            $maxPr = ManagerHolder::get($refEntityName)->getMaxPriority();
                            ManagerHolder::get($refEntityName)->updateById($nodeId, 'priority', $maxPr + 1);
                          }
                        } else {
                          $resultValues[] = ManagerHolder::get($refEntityName)->insert(array($nameField => $val));
                        }
                      }
                    }
                  }
                  if ($isOne) {
                    $resultValues = array_pop($resultValues);
                  }
                  $entity[$key] = $resultValues;
                }
              }
            }

            // Check for images
            if (($fieldAttrs['type'] == 'image')) {
              $imgName = $entity[$key];
              $image = array();
              foreach ($images as $img) {
                if ($img['file_name'] == $imgName) {
                  $image = $img;
                  break;
                }
              }
              $imageId = null;
              if (!empty($image)) {
                $this->fileoperations->set_base_dir('./web/images');
                $folder = $this->session->userdata(self::FOLDER_SESSION_KEY);
                $newPath = $this->fileoperations->copy_file($image, $folder, TRUE);
                $this->fileoperations->get_file_info($newPath);
                $image = $this->fileoperations->file_info;
                $thumbs = $this->config->item(strtolower($this->entityName . '_' . $key), 'thumbs');
                if(!$thumbs){
                  // for backward compatibility
                  $thumbs = $this->config->item(strtolower($this->entityName), 'thumbs');
                }
                $thumbs['_admin'] = $this->config->item('_admin', 'all');
                $this->fileoperations->createImageThumbs(array($image), $thumbs);
                $imageId = ManagerHolder::get('Image')->insert($image);
              }
              $entity[$key] = $imageId;
            }
          }
        }

        // Import Filters
        foreach ($importFilters as $fk => $fv) {
          $entity[$fk] = $fv;
        }

        // Insert/Update Entity
        if (count(array_keys($entity)) > 1) {
          if (isset($entity['id']) || !empty($entity['id'])) {
            $this->update($entity);
          } else {
            $this->insert($entity);
          }
        }
      }
      $this->commitTransaction();
    } catch (Exception $e) {
      $this->rollbackTransaction();
      $newE = new ErrorException($e->getMessage(), $e->getCode(), null, null, $rowNum);
      throw $newE;
    }
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

    // Get the entities from DB
    if (!empty($keyValueArray)) {
      $entities = $this->getAllWhere($keyValueArray, $what);
    } else {
      $entities = $this->getAll($what);
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

      // Create relations array to process
      $allRells = $this->getRelations();
      $rells = array();
      foreach ($what as $k) {
        if (isset($allRells[$k])) {
          $rells[$k] = $allRells[$k];
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

  /**
   * Returns array of relative site urls and other info of all entities to be used in stiemap.xml
   * @return array of arrays for entities: loc, lastmod, changefreq, priority. Only loc is mandatory.
   * @example 'loc'=>'item/1', 'lastmod'=>'1990-01-21', 'changefreq'=>'daily', 'priority'=>0.5
   * @see http://www.sitemaps.org/ru/protocol.html#xmlTagDefinitions
   */
  public function getAllForSitemap() {
    throw new Exception('in ' . $this->entityName . 'function getAllForSitemap not implemented.');
  }


  /**
   * Setter for hydration
   * @param string $hydration
   */
  public function setHydration($hydration) {
    $this->defaultHydration = $hydration;
  }

}
?>