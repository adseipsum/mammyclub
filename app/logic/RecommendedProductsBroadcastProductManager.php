<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * RecommendedProductsBroadcastProductManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class RecommendedProductsBroadcastProductManager extends BaseManager {

  /** Name field. */
  protected $nameField = "id";


  /** Fields. */
  public $fields = array("recommended_products_broadcast" => array("type" => "select", "class" => "required", "relation" => array("entity_name" => "RecommendedProductsBroadcast")),
                         "product" => array("type" => "select", "class" => "required", "relation" => array("entity_name" => "Product")));

  /** List params. */
  public $listParams = array("recommended_products_broadcast.name", "product.name");

}