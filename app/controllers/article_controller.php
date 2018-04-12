<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Article controller.
 * @author Itirra - http://itirra.com
 */
class Article_Controller extends Base_Project_Controller {

  /**
   * Constructor.
   */
  public function Article_Controller() {
    parent::Base_Project_Controller();
    $this->layout->setModule('article');
    $this->load->helper('common/itirra_date');

    $lastQuestions = ManagerHolder::get('Question')->getAll('e.*, user.*', 5);

    $showBroadcastBlock = TRUE;
    if ($this->isLoggedIn) {
      if ($this->authEntity['newsletter'] == 1) {
        $showBroadcastBlock = FALSE;
      }
    }

    $this->layout->set('showBroadcastBlock', $showBroadcastBlock);
    $this->layout->set('lastQuestions', $lastQuestions);
  }

  /**
   * Index page.
   */
  public function index() {
    ManagerHolder::get('ArticleCategory')->setOrderBy('priority ASC');
    $categories = ManagerHolder::get('ArticleCategory')->getAllWhere(array('level' => 0), 'e.*, image.*');

   foreach ($categories as $k => &$c) {
     $where = array('published' => TRUE,
                    'category_id' => array($c['id']));

     if(!empty($c['__children'])) {
       $this->categoryLoop($c['__children'], $where);
     }
     $c['articles'] = ManagerHolder::get('Article')->getAllWhere($where, 'e.*, image.*', 6);
     unset($categories[$k]['__children']);
   }
     //ManagerHolder::get('ArticleCategory')->setOrderBy('priority ASC');
     //$categories = ManagerHolder::get('ArticleCategory')->getAllWhere(array('level' => 0), 'e.*, image.*');
    $this->layout->set('categories', $categories);

    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    $this->setHeaders($page);

    $this->layout->view('index');
  }

  /**
   * Category page.
   */
  public function category() {
    $pageUrl = surround_with_slashes(uri_string());
    $category = ManagerHolder::get('ArticleCategory')->getOneWhere(array('page_url' => $pageUrl), 'e.*, header.*');
    if(empty($category)) {
      show_404();
    }
    $this->layout->set('category', $category);
    $this->setHeaders($category);

    $subCategories = ManagerHolder::get('ArticleCategory')->getDescendants($category['id']);
    $this->layout->set('subCategories', $subCategories);

    $parentCategories = ManagerHolder::get('ArticleCategory')->getAncestors($category['id'], null, false, Doctrine_Core::HYDRATE_ARRAY);
    $this->layout->set('parentCategories', $parentCategories);

    $perPage = 5;
    $this->layout->set('perPage', $perPage);

    $where = array('published' => TRUE,
                   'category_id' => array($category['id']));
    if(!empty($subCategories)) {
      $this->categoryLoop($subCategories, $where);
    }
    ManagerHolder::get('Article')->setOrderBy('priority ASC');
    $articles = ManagerHolder::get('Article')->getAllWhereWithPager($where, 1, $perPage, 'e.*, image.*, category.*');
    if ($this->isLoggedIn) {
      $userArticles = ManagerHolder::get('ArticleUser')->getAllWhere(array('user_id' => $this->auth->getAuthEntityId()), 'article_id');
      $userArticleIds = get_array_vals_by_second_key($userArticles, 'article_id');
      foreach ($articles->data as &$art) {
        if (in_array($art['id'], $userArticleIds)) {
          $art['is_read'] = TRUE;
        } else {
          $art['is_read'] = FALSE;
        }
      }
    }

    $this->layout->set('articles', $articles->data);
    $this->layout->set('pager', $articles->pager);
    $this->layout->view('list');
  }

  /**
   * Article page.
   */
  public function article() {
    $pageUrl = surround_with_slashes(uri_string());
    $article = ManagerHolder::get('Article')->getOneWhere(array('page_url' => $pageUrl), 'e.*, category.*, image.*, header.*, author.*');
    if(empty($article)) {
      show_404();
    }

    if ($article['published'] == FALSE && (!isset($_SESSION['LOGGED_IN_ADMIN_SESSION_KEY']) || empty($_SESSION['LOGGED_IN_ADMIN_SESSION_KEY']))) {
      show_404();
    }

    ManagerHolder::get('Article')->increment($article['id'], 'view_count');

    if(strpos($article['content'], '{BROADCAST_PROMO_BLOCK}')) {
      $broadcastBlockHtml = $this->layout->render('includes/parts/broadcast_block', array('settings' => $this->settings, 'articleExamples'), TRUE);
      $broadcastBlockHtml = str_replace('numeric-box">', 'numeric-box box-in-view">', $broadcastBlockHtml);
      $article['content'] = str_replace('<p><span class="description">{BROADCAST_PROMO_BLOCK}</span></p>', $broadcastBlockHtml, $article['content']);
      $article['content'] = str_replace('<p><span>{BROADCAST_PROMO_BLOCK}</span></p>', $broadcastBlockHtml, $article['content']);
      $article['content'] = str_replace('<p>{BROADCAST_PROMO_BLOCK}</p>', $broadcastBlockHtml, $article['content']);
      $article['content'] = str_replace('{BROADCAST_PROMO_BLOCK}', $broadcastBlockHtml, $article['content']);
    }

    add_utf_params_to_shop_links($article['content'], $article['name']);

    if($this->isLoggedIn == TRUE) {
      $where = array('article_id' => $article['id'], 'user_id' => $this->authEntity['id']);
      if(!ManagerHolder::get('ArticleUser')->existsWhere($where)) {
        ManagerHolder::get('ArticleUser')->insert($where);
      }
    }

    /*
    $imgMicroData = '';
    $imgTemplate = '<span itemscope itemtype="http://schema.org/ImageObject">
                      <span itemprop="name">{ARTICLE_NAME}</span>
                        {IMG}
                    </span>';

    $firstImg = $this->getImgFromHtml($article['content']);
    if (!empty($firstImg)) {
      $firstImg = str_replace('<img', '<img itemprop="contentUrl"', $firstImg);
      $imgMicroData = kprintf($imgTemplate, array('IMG' => $firstImg, 'ARTICLE_NAME' => $article['name']), 0, FALSE);
    }
    $this->layout->set('imgMicroData', $imgMicroData);
    */

    $article['content'] = str_replace('<img', '<img itemprop="image"', $article['content']);

    $this->createArticleContents($article['content'], $article['hide_contents']);

    // Process ad slots
    $this->processAdSlots($article['content'], $this->campaign);

    /*
    $firstImg = $this->getImgFromHtml($article['content']);
    if (!empty($firstImg)) {
      $processedImg = str_replace('<img', '<img itemprop="image"', $firstImg);
      $article['content'] = str_replace($firstImg, $processedImg, $article['content']);
    }
    */

    if(!empty($article['head_section_code'])) {
      $this->layout->set('head_section_code', $article['head_section_code']);
    }
    $this->layout->set('article', $article);
    $this->setHeaders($article);

    $parentCategories = ManagerHolder::get('ArticleCategory')->getAncestors($article['category']['id'], null, false, Doctrine_Core::HYDRATE_ARRAY);
    $this->layout->set('parentCategories', $parentCategories);

	  $comments = ManagerHolder::get('ArticleComment')->getAllWhere(array('entity_id' => $article['id'], 'published' => TRUE), 'e.*, user.*');
    $status = ManagerHolder::get('User')->fields['status']['options'];
    $this->layout->set('status', $status);
    $this->layout->set('comments', $comments);

    $this->layout->view('view');
  }

  /**
   * Print article.
   */
  public function print_article() {
    if(empty($_GET['id'])) {
      show_404();
    }
    $this->layout->setLayout('ajax');

    $article = ManagerHolder::get('Article')->getById($_GET['id'], 'e.*, author.*');
    if(strpos($article['content'], '{BROADCAST_PROMO_BLOCK}')) {
      $article['content'] = str_replace('<p><span class="description">{BROADCAST_PROMO_BLOCK}</span></p>', '', $article['content']);
      $article['content'] = str_replace('<p><span>{BROADCAST_PROMO_BLOCK}</span></p>', '', $article['content']);
      $article['content'] = str_replace('<p>{BROADCAST_PROMO_BLOCK}</p>', '', $article['content']);
      $article['content'] = str_replace('{BROADCAST_PROMO_BLOCK}', '', $article['content']);
    }

    $this->layout->set('article', $article);
    $this->layout->view('print_article');
  }

  /**
  * Removes first occurence of a specified tag. Should be called repeatedly to delete all occurencies.
  * If tag contains a dot ".", it searches for class.
  * @param $html HTML code.
  * @param $tag Tag to delete.
  * @return HTML code without first occurence of a specified tag. If the tag was not found РїС—Р… returns FALSE.
  */
  protected function getImgFromHtml($html) {
    $openingTag = '<img';
    // Define where tag starts
    $openingTagPosition = strpos($html, $openingTag);
    if ($openingTagPosition !== FALSE) {
      // Save everything from the start of HTML to the start of tag
      $htmlPartBeforeTag = substr($html, 0, $openingTagPosition);
      $closingTag = '/>';
      // Define where tag ends
      $closingTagPosition = strpos($html, $closingTag) + 2;
      // Get image HTML
      $htmlOfImage = substr($html, $openingTagPosition, $closingTagPosition - $openingTagPosition);

      return $htmlOfImage;
    } else {
      return FALSE;
    }
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