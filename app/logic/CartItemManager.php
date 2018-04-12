<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * CartItemManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class CartItemManager extends BaseManager {

  /** Name field. */
  protected $nameField = "id";


  /** Fields. */
  public $fields = array("cart" => array("type" => "select", "class" => "required", "relation" => array("entity_name" => "Cart")),
                         "product" => array("type" => "select", "class" => "required", "relation" => array("entity_name" => "Product")),
                         "qty" => array("type" => "input_integer"),
                         "price" => array("type" => "input_double"));

  /** List params. */
  public $listParams = array("cart.name", "product.name", "qty", "price");


  /**
   * PreProcessWhereQuery.
   * OVERRIDE THIS METHOD IN A SUBCLASS TO ADD joins and other stuff.
   * @param Doctrine_Query $query
   * @return Doctrine_Query
   */
  protected function preProcessWhereQuery($query, $pref, $what = "*") {
    $q = parent::preProcessWhereQuery($query, $pref, $what);
    if (strpos($what, 'product.') !== FALSE || $what == '*') {
      $q->addSelect('cimage.*')->leftJoin('product.image cimage');
	    $q->addSelect("product_category.*")->leftJoin("product.category product_category");
    }
    return $q;
  }



}