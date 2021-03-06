<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * ProductSaleCategoryManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseTreeManager.php';
class ProductSaleCategoryManager extends BaseTreeManager {

  /** Order by */
  protected $orderBy = "priority DESC";

  /** Fields. */
  public $fields = array("name" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "published" => array("type" => "checkbox"),
                         "page_url" => array("type" => "input", "class" => "required readonly", "attrs" => array("startwith" => "/", "depends" => "productsalecategory_name", "readonly" => "readonly", "maxlength" => 255)),
                         "content" => array("type" => "tinymce", "attrs" => array("maxlength" => 65536)),
//                         "priority" => array("type" => "input_integer"),
                         "google_product_category" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "header.title" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255, "depends" => "productsalecategory_name")),
                         "header.description" => array("type" => "textarea", "class" => "charCounter", "attrs" => array("maxlength" => 150)), 
                         "categories" => array("type" => "multipleselect_chosen", "relation" => array("entity_name" => "ProductCategory")));

  /** List params. */
  public $listParams = array("name", "published", "priority");

}