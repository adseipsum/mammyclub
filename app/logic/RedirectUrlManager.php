<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * RedirectUrlManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class RedirectUrlManager extends BaseManager {

  /** Name field. */
  protected $nameField = "id";


  /** Fields. */
  public $fields = array("old_url" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "new_url" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)));

  /** List params. */
  public $listParams = array();

}