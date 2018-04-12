<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * ParameterGroupManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class ParameterGroupManager extends BaseManager {

  /** Name field. */
  protected $nameField = "id";


  /** Fields. */
  public $fields = array("price" => array("type" => "input_integer"),
                         "product" => array("type" => "select", "relation" => array("entity_name" => "Product")),
                         "image" => array("type" => "image"),
                         "parameter_values_in" => array("type" => "multipleselect", "relation" => array("entity_name" => "ParameterValue", "search" => TRUE)),
                         "parameter_values_out" => array("type" => "multipleselect", "relation" => array("entity_name" => "ParameterValue", "search" => TRUE)));

  /** List params. */
  public $listParams = array("price", "product.name", array("parameter_values_in" => "name"), array("parameter_values_out" => "name"));

  /**
   * PostInsert.
   * OVERRIDE THIS METHOD IN A SUBCLASS.
   * @param array $entity
   */
  protected function postInsert($entity) {
    ManagerHolder::get('StoreInventory')->createDefault($entity['id'], str_replace('Manager', '', self::class));
  }

  /**
   * PostUpdate.
   * OVERRIDE THIS METHOD IN A SUBCLASS.
   * @param array $entity
   */
  protected function postUpdate($entity) {
    if (isset($entity['id'])) {
      ManagerHolder::get('StoreInventory')->createDefault($entity['id'], str_replace('Manager', '', self::class));
    }
  }

}