<?php
/**
 * HeaderManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class HeaderManager extends BaseManager {
  
  /**
  * Constructor.
  * @name entity name
  */
  public function HeaderManager($name = null, $mode = null) {
    if (config_item('languages') && count(config_item('languages')) > 1) {
      $this->i18nFields = array("title", "description");
    }
    parent::BaseManager($name, $mode);
  }
    

  /** Fields. */
  public $fields = array("title" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "description" => array("type" => "input", "attrs" => array("maxlength" => 255)));

  /** List params. */
  public $listParams = array("title", "description");
  

}