<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * DefaultAvatarManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class DefaultAvatarManager extends BaseManager {

  /** Fields. */
  public $fields = array("name" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "image" => array("type" => "image"));

  /** List params. */
  public $listParams = array("name");

}