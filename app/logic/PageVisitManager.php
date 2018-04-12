<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * PageVisitManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class PageVisitManager extends BaseManager {

  /** Order by */
  protected $orderBy = "created_at DESC";

  /** Fields. */
  public $fields = array("url" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "time_on_page" => array("type" => "input_integer"),
                         "referrer_url" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "user" => array("type" => "select", "relation" => array("entity_name" => "User")),
                         "created_at" => array("type" => "datetime", "class" => "readonly", "attrs" => array("disabled" => "disabled")));

  /** List params. */
  public $listParams = array("time_on_page", "user.name", "created_at");

  /**
   * insertPageVisitEvent
   * @param int $userId
   * @return int
   */
  public function insertPageVisitEvent($userId) {
    $url = uri_string();
    if(empty($url)) {
      $url = '/';
    }
    $insertData = array('url' => $url,
                        'subdomain' => SUBDOMAIN,
                        'referrer_url' => urldecode(get_referrer()),
                        'user_id' => $userId);
    return ManagerHolder::get($this->entityName)->insert($insertData);
  }

  /**
   * getViewedProducts
   * @param int $userId
   * @param int $limit
   * @return array
   */
  public function getViewedProducts($userId, $limit = null) {
    $products = array();
    ManagerHolder::get('PageVisit')->setSearch('/продукт/', 'url', 'starts_with');
    ManagerHolder::get('PageVisit')->setOrderBy('created_at DESC');
    $productPagesVisits = ManagerHolder::get('PageVisit')->getAllWhere(array('user_id' => $userId), 'e.*');
    if(!empty($productPagesVisits)) {
      $pageUrls = array();
      foreach ($productPagesVisits as $ppv) {
        $url = surround_with_slashes($ppv['url']);
        if(!in_array($url, $pageUrls)) {
          $pageUrls[] = $url;
        }
      }
      if(!empty($limit)) {
        $pageUrls = array_slice($pageUrls, 0, $limit);
      }
      $products = ManagerHolder::get('Product')->getAllWhere(array('page_url' => $pageUrls), 'e.*, image.*');
      if(!empty($products)) {
        // Sort products based on $pageUrls
        $tempProdArr = array();
        foreach ($products as $p) {
          $pKey = array_search($p['page_url'], $pageUrls);
          $tempProdArr[$pKey] = $p;
        }
        ksort($tempProdArr);
        $products = $tempProdArr;
      }
    }
    return $products;
  }

}