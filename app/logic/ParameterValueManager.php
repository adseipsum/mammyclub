<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * ParameterValueManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class ParameterValueManager extends BaseManager {

  /** Order By. */
  protected $orderBy = 'priority ASC';

  /** Fields. */
  public $fields = array("name" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "parameter" => array("type" => "select", "relation" => array("entity_name" => "Parameter")));

  /** List params. */
  public $listParams = array("name", "priority", "parameter.name");

}