<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Search controller.
 * @author Itirra - http://itirra.com
 */
class Search_Controller extends Base_Project_Controller {

  protected $managerName = 'Article';

  /**
   * Constructor.
   */
  public function Search_Controller() {
    parent::Base_Project_Controller();
    $this->load->helper('common/itirra_date');
    $this->layout->setModule('article');

//     $lastQuestions = ManagerHolder::get('Question')->getAll('e.*, user.*', 5);
//     foreach ($lastQuestions as &$q) {
//       $q['name'] = truncate(strip_tags($q['content']), 30, '...');
//     }
//     $this->layout->set('lastQuestions', $lastQuestions);
  }

  /**
   * Index page.
   */
  public function index() {
    if (!isset($_GET['q'])) {
      show_404();
    }

    $searchFields = 'content, name';
    if(is_shop()) {
      $this->managerName = 'Product';
      $searchFields = 'name, product_code';
      $this->setCategories();
      $this->layout->setModule('shop');
      $this->layout->setLayout('shop');
    }

    $query = trim(hsc($_GET['q']));
    $this->layout->set('query', $query);

    $header = array();
    $header['title'] = 'Результат поиска - не найдено.';

    $result = new stdClass();
    $result->data = array();

    if(!empty($query)) {
      ManagerHolder::get($this->managerName)->setSearch($query, $searchFields, 'contains', TRUE);

      if (is_shop()) {
        $this->load->helper('common/itirra_pager');
        $page = pager_get_page_number();
        $result = ManagerHolder::get('Product')->getAllWhereWithPager(array('published' => TRUE), $page, 30, 'e.*, image.*');
        $this->layout->set('pager', $result->pager);
      } else {
        $result->data = ManagerHolder::get($this->managerName)->getAllWhere(array('published' => TRUE), 'e.*');
      }

      if(!empty($result->data)) {
        foreach ($result->data as &$r) {
          if (!is_shop()) {
            $charLimit = 600;
            if(!empty($r['image'])) {
              $charLimit = 400;
            }

            $content = str_replace('{BROADCAST_PROMO_BLOCK}', '', trim(strip_tags($r['content'])));

            $r['description'] = mb_substr($content, 0, $charLimit, 'UTF-8') . '...';

            $pos = mb_strpos($content, $query, null, 'UTF-8');
            if(!empty($pos)) {
              $r['description'] = '...' . mb_substr($content, $pos, $charLimit, 'UTF-8') . '...';
            }
            if((strlen($r['description']) - 6) < $charLimit) {
              $r['description'] = '...' . mb_substr($content, $pos- ($charLimit-strlen($r['description'])), $charLimit, 'UTF-8') . '...';
            }
          }
        }
      }
      $header['title'] = $query . ' - Результат поиска';
    }

    $this->layout->set('header', $header);
    $this->layout->set('result', $result->data);
    $this->layout->set('managerName', $this->managerName);
    $this->layout->view('search_results');
  }

  /**
   * Set categories
   */
  private function setCategories() {
    $this->load->library("common/cache");
    $categories = $this->cache->get('menu_cats', 'PRODUCT_CATEGORY_CACHE_GROUP_KEY');
    if(empty($categories)) {
      $categories = ManagerHolder::get('ProductCategory')->getWhere(array());
      $categories = $this->processCategoryLoop($categories);
      $this->cache->save('menu_cats', $categories, 'PRODUCT_CATEGORY_CACHE_GROUP_KEY');
    }
    $this->layout->set('categories', $categories);
  }

  /**
   * processCategoryLoop
   * @param array $categories
   */
  private function processCategoryLoop($categories) {
    if(!empty($categories)) {
      foreach ($categories as $k => $v) {
        if (!$v['published'] && (!isset($_SESSION['LOGGED_IN_ADMIN_SESSION_KEY']) || empty($_SESSION['LOGGED_IN_ADMIN_SESSION_KEY']))) {
          unset($categories[$k]);
          continue;
        }
        if(!empty($v['__children'])) {
          $categories[$k]['__children'] = $this->processCategoryLoop($v['__children']);
        }
      }
    }
    return $categories;
  }

}