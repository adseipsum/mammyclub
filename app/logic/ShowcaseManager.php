<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * ShowcaseManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class ShowcaseManager extends BaseManager {

  /** Fields. */
  public $fields = array("name" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "published" => array("type" => "checkbox"),
                         "is_default" => array("type" => "checkbox"),
                         "age_of_child" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "products" => array("type" => "multipleselect", "relation" => array("entity_name" => "Product", "where_array" => array("not_in_stock" => FALSE, "published" => TRUE), "search" => TRUE, "sort" => TRUE)),
                         "pregnancy_weeks" => array("type" => "multipleselect_chosen", "relation" => array("entity_name" => "PregnancyWeek", "search" => TRUE)));

  /** List params. */
  public $listParams = array("name", "published", "is_default", "age_of_child", array("pregnancy_weeks" => "number"));

  /**
   * getShowcaseProducts
   * @param array $authEntity
   */
  public function getShowcaseProducts($authEntity) {
    if(isset($_GET['showcase_preview_id']) && !empty($_GET['showcase_preview_id'])) {
      $showcase = ManagerHolder::get('Showcase')->getById($_GET['showcase_preview_id'], 'e.*, products.*');
    } else {
      $showcase = ManagerHolder::get('Showcase')->getOneWhere(array('published' => TRUE, 'is_default' => TRUE), 'e.*, products.*');
      if(!empty($authEntity)) {
        $showcases = ManagerHolder::get('Showcase')->getAllWhere(array('published' => TRUE, 'is_default' => FALSE), 'e.*, products.*, pregnancy_weeks.*');
        foreach ($showcases as $sc) {
          // Process weeks
          if(empty($authEntity['pregnancyweek_current_id'])) {
            if(!empty($sc['pregnancy_weeks'])) {
              continue;
            }
          } else {
            if(empty($sc['pregnancy_weeks'])) {
              continue;
            }
            $pwIDs = get_array_vals_by_second_key($sc['pregnancy_weeks'], 'id');
            if(!in_array($authEntity['pregnancyweek_current_id'], $pwIDs)) {
              continue;
            }
          }
          // Process aoc
          if(empty($authEntity['age_of_child'])) {
            if(!empty($sc['age_of_child'])) {
              continue;
            }
          } else {
            if(empty($sc['age_of_child'])) {
              continue;
            }
            $aocArr = explode(',', $sc['age_of_child']);
            if(!empty($aocArr)) {
              foreach ($aocArr as $k => $v) {
                $aocArr[$k] = trim($v);
              }
              if(!in_array($authEntity['age_of_child'], $aocArr)) {
                continue;
              }
            }
          }
          $showcase = $sc;
          break;
        }
      }
    }
    return !empty($showcase)?$showcase['products']:array();
  }

  /**
   * PreProcessWhereQuery.
   * OVERRIDE THIS METHOD IN A SUBCLASS TO ADD joins and other stuff.
   * @param Doctrine_Query $query
   * @return Doctrine_Query
   */
  protected function preProcessWhereQuery($query, $pref, $what = "*") {
    $q = parent::preProcessWhereQuery($query, $pref, $what);
    if (strpos($what, 'products.') !== FALSE || $what == '*') {
      $q->addSelect('image.*')->leftJoin('products.image image');
    }
    return $q;
  }

}