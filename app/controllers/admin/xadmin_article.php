<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_Article
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_Article extends Base_Admin_Controller {

  /** AdditionalItemActions. */
  protected $additionalItemActions = array("view");

  /** Filters. */
  protected $filters = array("published" => "", 
                             "category.id" => "", 
                             "author.id" => "");

  /** DateFilters. */
  protected $dateFilters = array("date");

  /** SearchParams. */
  protected $searchParams = array("name");

  /** An array of properties to rewrite creating links in list view. */
  protected $listViewLinksRewrite = array("category" => "articlecategory");

  /**
   * Implementation of POST_UPDATE event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function postUpdate(&$entity) {
    if (isset($_POST['generate_sitemap']) && $_POST['generate_sitemap'] == TRUE) {
      $this->CI =& get_instance();
      $this->CI->load->config('sitemap');
      $sitemapConfig = $this->CI->config->item('sitemap');

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, site_url() . 'generate_sitemap/' . $sitemapConfig['protection_code']);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_exec($curl);
      curl_close($curl);
    }
  }

  /**
   * Implementation of PRE_UPDATE event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function preUpdate(&$entity) {
    $entityFromDb = ManagerHolder::get($this->managerName)->getById($entity['id'], 'page_url, published');
    if ($_POST['published'] != $entityFromDb['published']) {
      $_POST['generate_sitemap'] = TRUE;
    }
    if ($entityFromDb['page_url'] != $entity['page_url']) {
      $redirectData = array('old_url' => $entityFromDb['page_url'],
                            'new_url' => $entity['page_url']);
      // Check if we already have a redirect from current old url or eternal cycle
      $checkWhereArray = array(array('old_url' => $redirectData['old_url']),
                               array('old_url' => $redirectData['new_url'],
                                     'new_url' => $redirectData['old_url']));
      foreach ($checkWhereArray as $where) {
        $redirectUrl = ManagerHolder::get('RedirectUrl')->getOneWhere($where, 'e.*');
        if (!empty($redirectUrl)) {
          ManagerHolder::get('RedirectUrl')->deleteById($redirectUrl['id']);
        }
      }
      ManagerHolder::get('RedirectUrl')->insert($redirectData);
    }
  }

  /**
   * Implementation of POST_INSERT event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function postInsert(&$entity) {
    if ($_POST['published'] == TRUE) {
      $this->CI =& get_instance();
      $this->CI->load->config('sitemap');
      $sitemapConfig = $this->CI->config->item('sitemap');

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, site_url() . 'generate_sitemap/' . $sitemapConfig['protection_code']);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_exec($curl);
      curl_close($curl);
    }
  }

}