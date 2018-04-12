<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * ProductCategoryManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseTreeManager.php';
class ProductCategoryManager extends BaseTreeManager {

  public $CACHE_GROUP_KEY = 'PRODUCT_CATEGORY_CACHE_GROUP_KEY';

  /** Order by */
  protected $orderBy = "priority ASC";

  /** Fields. */
  public $fields = array("name" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "page_url" => array("type" => "input", "class" => "required readonly", "attrs" => array("startwith" => "/", "depends" => "productcategory_name", "readonly" => "readonly", "maxlength" => 255, "translit_ignore" => TRUE)),
                         "content" => array("type" => "tinymce", "attrs" => array("maxlength" => 65536)),
                         "published" => array("type" => "checkbox"),
                         "google_product_category" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "filters" => array("type" => "multipleselect_chosen", "relation" => array("entity_name" => "Filter", "search" => TRUE)),
                         "header.title" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255, "depends" => "productcategory_name")),
                         "header.description" => array("type" => "textarea", "class" => "charCounter"));

  /** List params. */
  public $listParams = array("name", "published");

  /**
   * preProcessWhereQuery
   * @param Doctrine_Query $query
   * @param $pref
   * @param string $what
   * @return Doctrine_Query
   */
  protected function preProcessWhereQuery($query, $pref, $what = "*") {
    $query = parent::preProcessWhereQuery($query, $pref, $what);
    if (strpos($what, 'filters.') !== FALSE || $what == '*') {
      $query->addSelect('filtervalues.*')->leftJoin('filters.filtervalues filtervalues');
    }
    return $query;
  }

  /**
   * Returns array of relative site urls and other info of all entities to be used in stiemap.xml
   * @return array of arrays for entities: loc, lastmod, changefreq, priority. Only loc is mandatory.
   * @example 'loc'=>'item/1', 'lastmod'=>'1990-01-21', 'changefreq'=>'daily', 'priority'=>0.5
   * @see http://www.sitemaps.org/ru/protocol.html#xmlTagDefinitions
   */
  public function getAllForSitemap() {
    $categories = $this->getAll('*');

    $urls = array();
    if (!empty($categories)) {
      foreach ($categories as $c) {
        $urls[] = array('loc' => $c['page_url']);
      }
    }

    return $urls;
  }

}