<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * ProductSaleCategoryProductCategoryManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class ProductSaleCategoryProductCategoryManager extends BaseManager {

  /** Name field. */
  protected $nameField = "id";


  /** Fields. */
  public $fields = array("product_sale_category" => array("type" => "select", "relation" => array("entity_name" => "ProductSaleCategory")),
                         "product_category" => array("type" => "select", "relation" => array("entity_name" => "ProductCategory")));

  /** List params. */
  public $listParams = array("product_sale_category.name", "product_category.name");

}