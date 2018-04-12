<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Pregnancy Week controller.
 * @author Itirra - http://itirra.com
 */
class Pregnancy_Week_Controller extends Base_Project_Controller {

  /**
   * @var CI_Session
   */
  public $session;

  /**
   * Constructor.
   */
  public function Pregnancy_Week_Controller() {
    parent::Base_Project_Controller();
    $this->layout->setLayout('index');
    $this->layout->setModule('week');
    $this->load->helper('common/itirra_date');
  }

  /**
   * Index page.
   */
  public function index() {
    $referrer = urldecode(get_referrer());
    if ($referrer && strpos($referrer, '/статья/') !== FALSE) {
      ConversionObserver::triggerEvent('from_artcle_to_pregnancy_week', $referrer);
    }
	  $perPage= $this->settings['review_week_per_page'];
	  ManagerHolder::get('PregnancyReview')->setOrderBy('date DESC');
	  $pregnancyReviews = ManagerHolder::get('PregnancyReview')->getAllWhereWithPager(array(), 1, $perPage, 'e.*, user.name, author_pregnancy_week.name');

	  $this->layout->set('reviews', $pregnancyReviews->data);
	  $this->layout->set('perPage', $perPage);

    ManagerHolder::get('ArticleExample')->setOrderBy('priority ASC');
    $articleExample = ManagerHolder::get('ArticleExample')->getAll();
    $this->layout->set('articleExample', $articleExample);

    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    $this->setHeaders($page);

    $content = $this->settings['pregnancy_week_page_content'];

    // Change content for A/B testing (add button after second paragraph)
    if (url_equals(PREGNANCY_WEEK_PAGE_ROUTE . '-3')) {
      if (preg_match_all('#<p>(.*?)<\/p>#', $content, $matches)) {
        if (isset($matches[0][1])) {
          $button = $this->layout->render('includes/week/parts/subscribe_button_1', array('isLoggedIn' => $this->isLoggedIn, 'authEntity' => $this->authEntity), TRUE);
          $content = str_replace($matches[0][1], $matches[0][1] . $button, $content);
        }
      }
    }

    if (strpos($content, '{ADVANTAGES_BLOCK}')) {
      $advantagesBlock = $this->layout->render('includes/week/parts/advantages_block', array('settings' => $this->settings), TRUE);
      $content = str_replace('{ADVANTAGES_BLOCK}', $advantagesBlock, $content);
    }
    if (strpos($content, '{ARTICLE_EXAMPLES_BLOCK}')) {
      $articlesExamplesBlock = $this->layout->render('includes/week/parts/article_examples_block', array('settings' => $this->settings, 'articleExample' => $articleExample, 'isLoggedIn' => $this->isLoggedIn, 'authEntity' => $this->authEntity, 'weeks' => $this->weeks), TRUE);
      $content = str_replace('{ARTICLE_EXAMPLES_BLOCK}', $articlesExamplesBlock, $content);
    }

    if($this->isLoggedIn == TRUE) {
      $pregnancyWeekSeted = FALSE;
      if (!empty($this->authEntity['pregnancyweek_id']) && !empty($this->authEntity['pregnancyweek_current_id'])) {
        $pregnancyWeekSeted = TRUE;

        $nextPregnancyArticleNotice = ManagerHolder::get('PregnancyArticle')->getNextPregnancyArticleNotice($this->authEntity, $this->settings);
        $this->layout->set('nextPregnancyArticleNotice', $nextPregnancyArticleNotice);

        $currentWeekNum = ManagerHolder::get('PregnancyWeek')->getById($this->authEntity['pregnancyweek_current_id'], 'number');
        $pastWeeks = ManagerHolder::get('PregnancyWeek')->getAllWhere(array('number <=' => $currentWeekNum['number']), 'e.*');
        $weeks = get_array_vals_by_second_key($pastWeeks, 'id');

        $articles = ManagerHolder::get('PregnancyArticle')->getAllWhere(array('pregnancyweek_id' => $weeks, 'published' => 1), 'e.*');
        $this->layout->set('articles', $articles);
      }
    }

    if (url_equals('беременность-по-неделям1') || url_equals('беременность-по-неделям2')) {
      $headers = array('title' => 'Беременность по неделям',
                       'description' => 'Беременность по неделям - уникальный цикл статей о развитии малыша и изменениях в Вашем организме в течение всего срока беременности.');
      $this->layout->set('header', $headers);
    }

    $this->layout->set('content', $content);
    $this->layout->view('index');

  }

  /**
   * Add review process.
   */
  public function add_review_process() {
    if ($this->isLoggedIn == FALSE) {
      uni_redirect('вход');
    }
    $this->load->helper('common/itirra_validation');
    simple_validate_post('review');

    $reviewArr = array('name' => $_POST['review'],
                       'date' => date('Y-m-d'),
                       'user_id' => $this->authEntity['id'],
                       'author_pregnancy_week_id' => $this->authEntity['pregnancyweek_current_id']);
    $reviewId = ManagerHolder::get('PregnancyReview')->insert($reviewArr);

    ManagerHolder::get('EmailNotice')->sendNewPregnancyWeekReviewToAdmins($reviewId);
    set_flash_notice('Отзыв успешно добавлен!');
    redirect('беременность-по-неделям');
  }

  /**
   * Newsletters subscribe process.
   */
  public function newsletters_subscribe_process() {
    if ($this->isLoggedIn == FALSE) {
      uni_redirect('вход');
    }
    if(!isset($_POST['pregnancyweek_id']) || empty($_POST['pregnancyweek_id'])) {
      set_flash_error('Вы не указали текущую неделю беременности');
      redirect_to_referral();
    }

    if(!ManagerHolder::get('PregnancyWeek')->existsWhere(array('id' => $_POST['pregnancyweek_id']))) {
      show_404();
    }

    $this->authEntity['pregnancyweek_id'] = $_POST['pregnancyweek_id'];
    $this->authEntity['pregnancyweek_current_id'] = $_POST['pregnancyweek_id'];
    $this->authEntity['pregnancyweek_current_started'] = date('Y-m-d');
    $this->authEntity['newsletter'] = TRUE;
    unset($this->authEntity['auth_info']);
    ManagerHolder::get('User')->update($this->authEntity);
    ConversionObserver::triggerEvent('subscribe');

    $this->auth->refresh();
    $this->authEntity = $this->auth->getAuthEntity();
    ManagerHolder::get('PregnancyWeek')->sendPregnancyWeekEmail($this->authEntity);

    if (isset($_GET['popup']) && !empty($_GET['popup'])) {
      redirect('успешная-подписка?pregnancy_week=' . $_POST['pregnancyweek_id'] . '&popup=1');
    } else {
      redirect('успешная-подписка?pregnancy_week=' . $_POST['pregnancyweek_id']);
    }

  }

  /**
   * Subscribe success page
   */
  public function subscribe_success_page() {
    if (isset($_GET['pregnancy_week']) && !empty($_GET['pregnancy_week'])) {
      $pregnancyWeek = ManagerHolder::get('PregnancyWeek')->getById($_GET['pregnancy_week'], 'e.*');
      if (!empty($pregnancyWeek)) {
        if (isset($_GET['popup']) && !empty($_GET['popup'])) {
          $this->layout->setLayout('ajax');
        } else {
          $this->layout->setLayout('main');
        }
        $this->layout->set('pregnancyWeek', $pregnancyWeek);
        $this->layout->view('subscribe_success');
      } else {
        show_404();
      }
    } else {
      show_404();
    }
  }

  /**
   * Pregnancy week article
   */
  public function pregnancy_week_article() {
    $article = ManagerHolder::get('PregnancyArticle')->getOneWhere(array('page_url' => surround_with_slashes(uri_string()), 'published' => 1));
    if (empty($article)) {
      show_404();
    }

    ManagerHolder::get('PregnancyWeek')->setOrderBy('number ASC');
    $pregnancyWeeks = ManagerHolder::get('PregnancyWeek')->getAll('id, name, number');
    $this->layout->set('pregnancyWeeks', $pregnancyWeeks);

    $article['content'] = str_replace('<img', '<img itemprop="image"', $article['content']);

    add_utf_params_to_shop_links($article['content'], $article['name']);

    $this->createArticleContents($article['content'], $article['hide_contents']);

    $this->setHeaders($article);

    $this->layout->setModule('week');
    $this->layout->set('article', $article);
    $this->layout->view('view');
  }

  /**
   * Unsubscribe process.
   */
  public function unsubscribe_process() {

    $data = array();
    $data['id'] = $this->authEntity['id'];
    $data['newsletter'] = FALSE;
    $data['pregnancyweek_id'] = null;
    $data['pregnancyweek_current_id'] = null;
    $data['pregnancyweek_current_started'] = null;
    ManagerHolder::get('UserPregnancyWeek')->deleteAllWhere(array('user_id' => $data['id']));

    ManagerHolder::get('User')->update($data);

    redirect('отписка');
  }

  /**
   * Unsubscribe.
   */
  public function unsubscribe() {
    if($this->isLoggedIn == FALSE || empty($this->authEntity)) {
      show_404();
    }
    $header = array('title' => 'Вы успешно отписались от рассылки "Моя неделя беременности"',
                    'description' => 'Вы успешно отписались от рассылки "Моя неделя беременности"');
    $this->layout->set('header', $header);

    $this->layout->setModule('week');
    $this->layout->set('user', $this->authEntity);
    $this->layout->view('unsubscribe');
  }

  /**
   * Resubscribe process.
   */
  public function resubscribe_process() {
    ManagerHolder::get('User')->updateById($this->authEntity['id'], 'newsletter', TRUE);
    set_flash_notice('Вы снова подписаны на нашу рассылку "Беременность по неделям"');
    redirect('личный-кабинет/редактирование-информации');
  }

  /**
   * Unsubscribe reason process.
   */
  public function unsubscribe_reason_process() {
    if($this->isLoggedIn == FALSE || empty($this->authEntity)) {
      show_404();
    }
    if(!isset($_POST['reason']) || empty($_POST['reason'])) {
      set_flash_error('Вы не указали причину отписки.');
      redirect_to_referral();
    }

    if(!empty($this->settings['site_email'])) {
      $message = 'Пользователь <a href="' . admin_site_url('user/add_edit/' . $this->authEntity['id']) . '">' . $this->authEntity['name'] . '</a> был отписан от рассылки по причине: <br />' . $_POST['reason'];
      ManagerHolder::get('Email')->send($this->settings['site_email'], 'Причина отписки', $message);
    }

    set_flash_notice('Спасибо за ваш комментарий.');
    redirect_to_referral();
  }

  /**
   * Email was sent page.
   */
  public function email_was_sent() {
    if($this->isLoggedIn == FALSE) {
      show_404();
    }

    $pregnancyWeek = ManagerHolder::get('PregnancyWeek')->getById($this->authEntity['pregnancyweek_current_id'], 'id');
    $pregnancyArticle = ManagerHolder::get('PregnancyArticle')->getOneWhere(array('pregnancyweek_id' => $pregnancyWeek['id']), 'name');

    $pageContent = '';
    if(isset($this->settings['article_was_sent_content']) && !empty($this->settings['article_was_sent_content'])) {
      $pageContent = $this->settings['article_was_sent_content'];
    }
    $this->layout->set('content', $pageContent);

    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    $this->setHeaders($page);

    $this->layout->setModule('week');
    $this->layout->set('user', $this->authEntity);
    $this->layout->set('article', $pregnancyArticle);
    $this->layout->view('email_was_sent');
  }

  /**
   * Ajax article example
   */
  public function ajax_article_example($aId = null) {
    if ($aId == null) {
      show_404();
    }

    $article = ManagerHolder::get('PregnancyArticle')->getById($aId);

    $this->createArticleContents($article['content']);

    $this->layout->setLayout('ajax');
    $this->layout->set('article', $article);
    $this->layout->view('ajax_article_example_block');
  }

  public function pregnancy_article_list() {
    ManagerHolder::get('PregnancyArticle')->setOrderBy('pregnancyweek.number ASC');
    $pregnancyArticles = ManagerHolder::get('PregnancyArticle')->getAll('name, page_url');

    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    $this->setHeaders($page);

    $this->layout->set('pregnancyArticles', $pregnancyArticles);
    $this->layout->view('pregnancy_article_list');
  }



  /**
   * Ajax subscribe pregnancy week.
   */
  public function ajax_subscribe_pregnancy_week() {
    $this->layout->setLayout('ajax');
    $this->layout->view('parts/ajax_subscribe_pregnancy_week');
  }

}