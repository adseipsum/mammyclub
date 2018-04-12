<?php
/**
 * ConversionEventManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class ConversionEventManager extends BaseManager {

    /** Order By. */
  protected $orderBy = 'created_at DESC';

  /** Fields. */
  public $fields = array("conversion" => array("type" => "select", "relation" => array("entity_name" => "Conversion")),
  											 "page" => array("type" => "input"),
  											 "ip" => array("type" => "input"),
                         "user_agent" => array("type" => "textarea"),
                         "guid" => array("type" => "input"),
                         "created_at" => array("type" => "input"),
                         "guid" => array("type" => "input"),
                         "comment" => array("type" => "textarea"));

  /** List params. */
  public $listParams = array("conversion.name", "page", "ip", "user_agent", "guid", "created_at", "comment");
  
	/**
   * Filter Values
   * @param string $filterName
   */
  public function getFilterValues($filterName) {
    if ($filterName == 'conversion.id') {
      return ManagerHolder::get('Conversion')->getAsViewArray();
    }
  }
  
  /**
   * PreProcessWhereQuery.
   * OVERRIDE THIS METHOD IN A SUBCLASS TO ADD joins and other stuff.
   * @param Doctrine_Query $query
   * @return Doctrine_Query
   */
  protected function preProcessWhereQuery($query, $pref, $what = '*') {
    $query->addSelect("conversion.*")->leftJoin($pref . ".conversion conversion");
    return $query;
  }

}