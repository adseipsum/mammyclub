<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * FilterManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class FilterManager extends BaseManager {

  /** Fields. */
  public $fields = array("name" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)));

  /** List params. */
  public $listParams = array("name");

}