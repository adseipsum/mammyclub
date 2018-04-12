<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * ProductBroadcastUserManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class ProductBroadcastUserManager extends BaseManager {

  /** Fields. */
  public $fields = array("users" => array("type" => "select", "relation" => array("entity_name" => "User")),
                         "product_broadcast" => array("type" => "select", "relation" => array("entity_name" => "ProductBroadcast")));

  /** List params. */
  public $listParams = array("users", "product_broadcast.name");

}