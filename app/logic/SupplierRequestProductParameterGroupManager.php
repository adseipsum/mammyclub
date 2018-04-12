<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * SupplierRequestProductParameterGroupManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class SupplierRequestProductParameterGroupManager extends BaseManager {

  /** Name field. */
  protected $nameField = "id";


  /** Fields. */
  public $fields = array("supplier_request" => array("type" => "select", "class" => "required", "relation" => array("entity_name" => "SupplierRequest")),
                         "product" => array("type" => "select", "relation" => array("entity_name" => "Product")),
                         "parameter_group" => array("type" => "select", "relation" => array("entity_name" => "ParameterGroup")));

  /** List params. */
  public $listParams = array("supplier_request.name", "product.name", "parameter_group.name");

  /**
   * PostInsert.
   * OVERRIDE THIS METHOD IN A SUBCLASS.
   * @param array $entity
   */
  protected function preInsert($entity) {
    if (!empty($entity['siteorder_code'])) {
      $siteOrder = ManagerHolder::get('SiteOrder')->getOneWhere(array('code' => $entity['siteorder_code']), 'shipment_date');
      $entity['shipment_date'] = $siteOrder['shipment_date'];
    }
  }

}