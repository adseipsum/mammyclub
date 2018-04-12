<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base/base_controller.php";

/**
 * Ajax controller.
 * @author Itirra - http://itirra.com
 */
class Ajax_Controller extends Base_Controller {

  /** Libraries to load.*/
  protected $libraries = array('common/DoctrineLoader', 'Session');

  /** Helpers to load.*/
  protected $helpers = array('url',
                             'common/itirra_language',
                             'common/itirra_resources',
                             'common/itirra_messages',
                             'common/itirra_text',
                             'common/itirra_date',
                             'cookie');

  /**
   * Constructor.
   */
  public function Ajax_Controller() {
    parent::Base_Controller();
  }


	/**
	 * save client id for Google Analytics
	 */
	public function save_ajax_client_id_ga(){
		if (!empty($_POST['clientId']) && !empty($_POST['entityId'])){
			 $user = ManagerHolder::get('User')->getById($_POST['entityId'], 'e.*');
			 if (!empty($user['client_id_ga'])){
			 	$userClientId = unserialize($user['client_id_ga']);
			 	$userClientId[] = $_POST['clientId'];
				$userClientId = serialize($userClientId);
				ManagerHolder::get('User')->updateById($user['id'], 'client_id_ga', $userClientId);
			 } else {
			 	 $userClientId = array($_POST['clientId']);
				 serialize($userClientId);
				 ManagerHolder::get('User')->updateById($user['id'], 'client_id_ga', $userClientId);
			 }
		}
  }


  /**
   * save inv_channel data
   */
  public function save_inv_channel() {
    if(empty($_GET['inv_channel']) || empty($_GET['lk']) || !in_array($_GET['type'], array('user', 'siteorder'))) {
      show_404();
    }
    $managerName = 'User';
    $updateKey = 'login_key';
    if($_GET['type']=='siteorder') {
      $managerName = 'SiteOrder';
      $updateKey = 'code';
    }
    $updateData = array('inv_channel'     => $_GET['inv_channel'],
                        'inv_channel_src' => $_GET['inv_channel_src'],
                        'inv_channel_mdm' => $_GET['inv_channel_mdm'],
                        'inv_channel_cmp' => $_GET['inv_channel_cmp'],
                        'inv_channel_cnt' => $_GET['inv_channel_cnt'],
                        'inv_channel_trm' => $_GET['inv_channel_trm']);
    ManagerHolder::get($managerName)->updateAllWhere(array($updateKey => $_GET['lk']), $updateData);
  }

  /**
   * Ajax load.
   */
  public function ajax_load() {
    $pageUrl = trim(uri_string(), '/');
    $urlSegments = explode('/', $pageUrl);

    $where = array();
    $what = '';

    if ($urlSegments[1] == 'консультации') {
      $managerName = 'Question';
      $what = 'e.*, user.*, image.*';
    } elseif ($urlSegments[1] == 'беременность-по-неделям'){
	    $managerName = 'PregnancyReview';
	    $what = 'e.*, user.name, author_pregnancy_week.name';
    } elseif ($urlSegments[1] == 'статьи') {
      $managerName = 'Article';
      unset($urlSegments[0]);
      $categoryUrl = surround_with_slashes(implode('/', $urlSegments));

      $category = ManagerHolder::get('ArticleCategory')->getOneWhere(array('page_url' => $categoryUrl), 'e.*, header.*');

      if (empty($category)) {
        show_404();
      }
      $this->layout->set('category', $category);

      $where = array('published' => TRUE,
                     'category_id' => array($category['id']));
      $what = 'e.*, user.*, image.*, category.*';

      $subCategories = ManagerHolder::get('ArticleCategory')->getDescendants($category['id']);
      if (!empty($subCategories)) {
        $this->categoryLoop($subCategories, $where);
      }
    } else {
      show_404();
    }

    $page = 1;
    if (isset($_GET['p'])) {
      $page = $_GET['p'];
    }

    $perPage = 5;
    if ($managerName == 'PregnancyReview'){
    	$perPage = ManagerHolder::get('Settings')->getOneWhere(array('k' => 'review_week_per_page'), 'e.*');
	    $perPage = $perPage['v'];
    }
    $this->layout->set('perPage', $perPage);

    if ($managerName == 'Article') {
      ManagerHolder::get($managerName)->setOrderBy('priority ASC');
    } elseif (in_array($managerName, array('Question', 'PregnancyReview')) ) {
      ManagerHolder::get($managerName)->setOrderBy('date DESC');
    }

	  $result = ManagerHolder::get($managerName)->getAllWhereWithPager($where, $page, $perPage, $what);

    $this->layout->setLayout('ajax');

    if ($managerName == 'Question') {
      $this->layout->setModule('question');
      $this->layout->set('questions', $result->data);
      $product_list_html = $this->layout->view('parts/question_list', TRUE);
    } elseif ($managerName == 'PregnancyReview'){
	    $this->layout->setModule('week');
	    $this->layout->set('reviews', $result->data);
	    $product_list_html = $this->layout->view('parts/review_list', TRUE);
    }else {
      $this->layout->setModule('article');
      $this->layout->set('articles', $result->data);
      $product_list_html = $this->layout->view('parts/article_list', TRUE);
    }

    $continue_requests = TRUE;
    if($page >= $result->pager->getLastPage()) {
      $continue_requests = FALSE;
    }

    $result = array('product_list_html' => $product_list_html,
                    'continue_requests' => $continue_requests);

    die(json_encode($result));
  }

  /**
   * Comments shop ajax load.
   */
  public function comments_shop_ajax_load() {
    $page = 1;
    if (isset($_GET['p']) && !empty($_GET['p'])) {
      $page = $_GET['p'];
    }
    $result = ManagerHolder::get('ShopComment')->getBySortorderWithPager(array('published' => TRUE), $page, COMMENTS_SHOP_PER_PAGE, 'e.*, user.*');

    $this->layout->setLayout('ajax');
    $this->layout->set('comments', $result->data);
    $this->layout->set('entityType', 'Shop');
    $html = $this->layout->view('shop/parts/comments_list', TRUE);

    $result = array('product_list_html' => $html,
                    'continue_requests' => $result->pager->getLastPage() > $page);
    die(json_encode($result));
  }

  /**
   * @param unknown $cat
   * @param unknown $where
   */
  private function categoryLoop($cat, &$where) {
    foreach ($cat as $c){
      $where['category_id'][] = $c['id'];
      if(!empty($c['__children'])) {
        $this->categoryLoop($c['__children'], $where);
      }
    }
  }
}