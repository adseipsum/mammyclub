<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * PrivateAreaController;
 * @property CI_Session $session
 * @property Fileoperations $fileoperations
 * @property Auth $auth
 */
class Private_Area_Controller extends Base_Project_Controller {

  /**
   * Constructor
   */
  public function Private_Area_Controller() {
    parent::Base_Project_Controller();

    $this->lang->load('enum', $this->config->item('language'));

    $this->load->helpers(array('common/itirra_ajax',
                               'common/itirra_date',
                               'cookie'));

    if($this->isLoggedIn == FALSE) {
      uni_redirect('вход');
    }

    $this->auth->refresh();
    $this->authEntity = $this->auth->getAuthEntity();
    $this->layout->set('authEntity', $this->authEntity);

    $this->layout->setModule('private');
    $this->layout->setLayout('private');

    ManagerHolder::get('PregnancyWeek')->setOrderBy('number ASC');
    $pregnancyWeeks = ManagerHolder::get('PregnancyWeek')->getAll('e.*');
    $this->layout->set('pregnancyWeeks', $pregnancyWeeks);

    $registerPopupHidden = get_cookie('mammyclub_register_popup_hidden');
    if (!$registerPopupHidden && !empty($this->authEntity['pregnancyweek_id'])) {
      set_cookie('mammyclub_register_popup_hidden', 1, 60 * 60 * 24 * 365 * 10);
    }
    $this->layout->set('registerPopupHidden', $registerPopupHidden);
  }

  /**
   * Index page.
   */
  public function index() {
    ManagerHolder::get('ArticleUser')->setOrderBy('created_at DESC');
    $readArticles = ManagerHolder::get('ArticleUser')->getAllWhere(array('user_id' => $this->authEntity['id']), '*, article.*', 3);
    $this->layout->set('readArticles', $readArticles);

    ManagerHolder::get('Question')->setOrderBy('date DESC');
    $questions = ManagerHolder::get('Question')->getAllWhere(array('user_id' => $this->authEntity['id']), 'e.*', 3);
    $this->layout->set('questions', $questions);

    $products = ManagerHolder::get('PageVisit')->getViewedProducts($this->authEntity['id'], 2); // here should be 2 products
    if (!empty($products)) {
      foreach ($products as $product) {
        ManagerHolder::get('Sale')->addAvailableSaleToProducts($this->authEntity, $product);
      }
    }
    $this->layout->set('products', $products);

    if (!empty($this->authEntity['pregnancyweek_current_id'])) {
      $currentWeekNum = ManagerHolder::get('PregnancyWeek')->getById($this->authEntity['pregnancyweek_current_id'], 'number');
      $pastWeeks = ManagerHolder::get('PregnancyWeek')->getAllWhere(array('number <=' => $currentWeekNum['number']), 'e.*');
      $weeks = array();
      foreach ($pastWeeks as $pw) {
        $weeks[] = $pw['id'];
      }

      $pregnancyArticles = ManagerHolder::get('PregnancyArticle')->getAllWhere(array('pregnancyweek_id' => $weeks, 'published' => 1), 'page_url, name', 9);
      $this->layout->set('pregnancyArticles', $pregnancyArticles);
    }

    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    $this->setHeaders($page);

    $this->layout->view('index');
  }

  /**
   *  Questions page.
   */
  public function questions() {
    ManagerHolder::get('Question')->setOrderBy('date DESC');
    $questions = ManagerHolder::get('Question')->getAllWhere(array('user_id' => $this->authEntity['id']), 'e.*');

    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    $this->setHeaders($page);

    $this->layout->set('questions', $questions);
    $this->layout->view('questions');
  }

  /**
   *  Read articles page.
   */
  public function articles() {
    ManagerHolder::get('ArticleUser')->setOrderBy('created_at DESC');
    $articles = ManagerHolder::get('ArticleUser')->getAllWhere(array('user_id' => $this->authEntity['id']), '*, article.*');

    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    $this->setHeaders($page);

    $this->layout->set('articles', $articles);

    $this->layout->view('articles');
  }

  /**
   *  Edit profile info page.
   */
  public function edit_profile() {
    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    $this->setHeaders($page);

    $currentAvatar = $this->authEntity['image_id'];
    $avatars = ManagerHolder::get('DefaultAvatar')->getAll();

    $this->layout->set('currentAvatar', $currentAvatar);
    $this->layout->set('avatars', $avatars);
    $this->layout->view('edit_profile');
  }

  /**
   *  Edit info process.
   */
  public function edit_info_process() {
    $this->load->helper('common/itirra_validation');
    simple_validate_post(array('name', 'email'));
    $emailSend = FALSE;

    $data = array();
    if (isset($_POST['pregnancy_week']) && !empty($_POST['pregnancy_week'])) {
      if (!ManagerHolder::get('PregnancyWeek')->existsWhere(array('id' => $_POST['pregnancy_week']))) {
        show_404();
      } else {
        if ($_POST['pregnancy_week'] != $this->authEntity['pregnancyweek_current_id']) {
          if (empty($this->authEntity['pregnancyweek_id'])) {
            $data['pregnancyweek_id'] = $_POST['pregnancy_week'];
          }
          $data['pregnancyweek_current_id'] = $_POST['pregnancy_week'];
          $data['name'] = $_POST['name'];
          $data['pregnancyweek_current_started'] = date('Y-m-d');
        }
      }
    }
    if ($this->authEntity['name'] != $_POST['name']) {
      $data['name'] = $_POST['name'];
    }
    // Process first year data
    if(isset($_POST['child_birth_date'])) {
      if ($this->authEntity['child_birth_date'] != $_POST['child_birth_date']) {
        $data['child_birth_date'] = $_POST['child_birth_date'];
        $data['age_of_child'] = calculate_age_in_weeks($_POST['child_birth_date']);
        $data['age_of_child_current_started'] = date('Y-m-d');
        if(empty($this->authEntity['child_birth_date'])) {
          $data['newsletter'] = FALSE;
        }
      }
      if ($this->authEntity['child_sex'] != $_POST['child_sex']) {
        $data['child_sex'] = $_POST['child_sex'];
      }
      if ($this->authEntity['child_name'] != $_POST['child_name']) {
        $data['child_name'] = $_POST['child_name'];
      }
    }

    if (!empty($data)) {
      ManagerHolder::get('User')->updateAllWhere(array('id' => $this->auth->getAuthEntityId()), $data);
      set_flash_notice('Информация успешно изменена.');

      if($_POST['email'] == $this->authEntity['auth_info']['email']) {

        if (isset($_POST['pregnancy_week']) && $_POST['pregnancy_week'] != $this->authEntity['pregnancyweek_current_id']) {
          $this->authEntity = $this->auth->getAuthEntity();
          // Delete past weeks
          $currentWeek = ManagerHolder::get('PregnancyWeek')->getById($_POST['pregnancy_week'], 'e.*');
          $pastWeeks = ManagerHolder::get('UserPregnancyWeek')->getAllWhere(array('user_id' => $this->authEntity['id']));
          foreach ($pastWeeks as $pw) {
            if ($currentWeek['number'] <= $pw['pregnancyweek']['number']) {
              ManagerHolder::get('UserPregnancyWeek')->deleteAllWhere(array('user_id' => $pw['user_id'], 'pregnancy_week_id' => $pw['pregnancy_week_id']));
            }
          }
          $this->auth->refresh();
          ManagerHolder::get('PregnancyWeek')->sendPregnancyWeekEmail($this->auth->getAuthEntity());
          redirect('статья-выслана-вам-на-почту');
        }

        // Check if new "age_of_child" filed in weeks is bigger than old
        if (isset($data['age_of_child']) && $data['age_of_child'] > $this->authEntity['age_of_child']) {
          ManagerHolder::get('FirstYearBroadcast')->setOrderBy('age_of_child ASC');
          $broadcasts = ManagerHolder::get('FirstYearBroadcast')->getAllWhere(array('age_of_child <=' => $data['age_of_child']), 'e.*, countries.*, article.*, products.*');
          if(!empty($broadcasts)) {
            // Get last broadcast
            $broadcast = array_pop($broadcasts);
            ManagerHolder::get('FirstYearBroadcast')->sendSingleLetterOfBroadcast($broadcast, $this->authEntity);
            set_flash_notice('Информация успешно изменена. Письмо со статьей ' . $broadcast['subject'] . ' уже было выслано на Ваш адрес <b>' . $this->authEntity['auth_info']['email']);
          }
        }

      }
    }

    // Email change
    if ($_POST['email'] != $this->authEntity['auth_info']['email']) {
      if (ManagerHolder::get('AuthInfo')->existsWhere(array('email' => $_POST['email']))) {
        set_flash_error('Такой e-mail уже используется.');
        redirect_to_referral();
      }

      $authInfoData = array('email_confirmed' => FALSE,
                            'email' => $_POST['email'],
                            'activation_key' => md5(rand() . microtime()));
      ManagerHolder::get('AuthInfo')->updateAllWhere(array('id' => $this->authEntity['auth_info_id']), $authInfoData);
      ManagerHolder::get('User')->updateAllWhere(array('id' => $this->authEntity['id']), array('email_confirm_date' => ''));

      $this->auth->refresh();
      $this->authEntity = $this->auth->getAuthEntity();

      $authConfig = $this->config->item('auth');
      $emailData = array('entity' => $this->authEntity,
                         'url' => site_url($authConfig['url_email_confirm_process'] . '/' . $this->authEntity['auth_info']['activation_key']),
                         'key' => $this->authEntity['auth_info']['activation_key']);
      ManagerHolder::get('Email')->sendTemplate($this->authEntity['auth_info']['email'], 'new_email_confirm', $emailData);

      set_flash_notice('Информация успешно изменена. На новый e-mail выслан код активации.');
    }
    redirect_to_referral();
  }

  /**
   *  Change password process.
   */
  public function change_password_process() {
    $this->load->helper('common/itirra_validation');
    simple_validate_post(array('old_password', 'new_password', 'confirm_password'));

    $ae = $this->auth->getAuthEntity();
    if(md5($_POST['old_password']) == $ae['auth_info']['password']) {
      if($_POST['new_password'] == $_POST['confirm_password']){
        ManagerHolder::get('AuthInfo')->updateById($ae['auth_info_id'], 'password', md5($_POST['new_password']));
        $this->auth->refresh();
        set_flash_notice('Пароль успешно изменен.');
        redirect_to_referral();
      } else {
        set_flash_error('Новые пароли не совпадают.');
        redirect_to_referral();
      }
    } else {
      set_flash_error('Вы ввели неправильный старый пароль.');
      redirect_to_referral();
    }
  }

  /**
   * Change avatar process
   */
  public function change_avatar_process($imgId) {
    if (ManagerHolder::get('DefaultAvatar')->existsWhere(array('image_id' => $imgId))) {
      ManagerHolder::get('User')->updateAllWhere(array('id' =>$this->auth->getAuthEntityId()), array('image_id' => $imgId));
      set_flash_notice('Аватар успешно изменен.');
      redirect_to_referral();
    } else {
      show_404();
    }
  }

  /**
   *  Change newsletters process.
   */
  public function change_newsletters_process() {
    $this->load->helper('common/itirra_validation');
    simple_validate_post(array('newsletter',
                               'newsletter_questions',
                               'newsletter_shop',
                               'newsletter_comments',
                               'newsletter_recommended_products',
                               'newsletter_useful_tips'));

    $data = array('id' => $this->authEntity['id'],
                  'newsletter' => $_POST['newsletter'],
                  'newsletter_questions' => $_POST['newsletter_questions'],
                  'newsletter_shop' => $_POST['newsletter_shop'],
                  'newsletter_comments' => $_POST['newsletter_comments'],
                  'newsletter_recommended_products' => $_POST['newsletter_recommended_products'],
                  'newsletter_useful_tips' => $_POST['newsletter_useful_tips']);

    if(isset($_POST['newsletter_first_year'])) {
      $data['newsletter_first_year'] = $_POST['newsletter_first_year'];
      // If newsletter == FALSE - set all first year values = null
      if($data['newsletter_first_year'] == FALSE) {
        $data['age_of_child'] = null;
        $data['age_of_child_current_started'] = null;
        $data['child_birth_date'] = null;
        $data['child_sex'] = null;
        $data['child_name'] = null;
      }
    }

    // If newsletter == FALSE - set prgnancy week value = null
    if($data['newsletter'] == FALSE) {
      $data['pregnancyweek_id'] = null;
      $data['pregnancyweek_current_id'] = null;
      $data['pregnancyweek_current_started'] = null;
      ManagerHolder::get('UserPregnancyWeek')->deleteAllWhere(array('user_id' => $this->authEntity['id']));
    }

    if (isset($_POST['pregnancy_week']) && !empty($_POST['pregnancy_week'])) {
      $data['pregnancyweek_id'] = $_POST['pregnancy_week'];
      $data['pregnancyweek_current_id'] = $_POST['pregnancy_week'];
      $data['pregnancyweek_current_started'] = date(DOCTRINE_DATE_FORMAT);
    }

    ManagerHolder::get('User')->update($data);

    if (isset($_POST['pregnancy_week']) && !empty($_POST['pregnancy_week'])) {
      $this->auth->refresh();
      $this->authEntity = $this->auth->getAuthEntity();
      ManagerHolder::get('PregnancyWeek')->sendPregnancyWeekEmail($this->authEntity);
      redirect('статья-выслана-вам-на-почту');
    } else {
      set_flash_notice('Информация успешно изменена.');
      redirect_to_referral();
    }
  }

  /**
   * change_broadcast_delivery_process
   */
  public function change_broadcast_delivery_process()
  {
    $this->load->helper('common/itirra_validation');
    simple_validate_post(array('broadcast_channels'));

    $data = array(
      'id' => $this->authEntity['id'],
      'broadcast_channels' => json_encode($_POST['broadcast_channels'])
    );
    ManagerHolder::get('User')->update($data);

    set_flash_notice('Информация успешно изменена.');
    redirect_to_referral();
  }

  /**
   *  Newsletter subscribtion process
   */
  public function subscribtion_process() {
    $this->load->helper('common/itirra_validation');
    simple_validate_post('pregnancy_week');
    ConversionObserver::triggerEvent('subscribe');

    $data = array('pregnancyweek_current_id' => $_POST['pregnancy_week'],
                  'pregnancyweek_id' => $_POST['pregnancy_week'],
                  'pregnancyweek_current_started' => date('Y-m-d'),
                  'newsletter' => 1);
    ManagerHolder::get('User')->updateAllWhere(array('id' => $this->authEntity['id']), $data);

    $this->auth->refresh();
    $this->authEntity = $this->auth->getAuthEntity();
    ManagerHolder::get('PregnancyWeek')->sendPregnancyWeekEmail($this->authEntity);

    redirect('статья-выслана-вам-на-почту');
  }

  /**
   * Recently viewed products page
   */
  public function recently_viewed_products() {

    $products = ManagerHolder::get('PageVisit')->getViewedProducts($this->authEntity['id']);

//     $products = ManagerHolder::get('UserProduct')->getAllWhere(array('user_id' => $this->authEntity['id']));
//     if (!empty($products)) {
//       foreach ($products as &$product) {
//         ManagerHolder::get('Sale')->addAvailableSaleToProducts($this->authEntity['pregnancyweek_current_id'], $product['product']);
//       }
//     }

    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    $this->setHeaders($page);

    $this->layout->set('products', $products);
    $this->layout->view('viewed_products');
  }

  /**
   * Pragnancy week page
   */
  public function pregnancy_week() {
    $pregnancyWeekSeted = FALSE;

    if (!empty($this->authEntity['pregnancyweek_id'])) {
      $pregnancyWeekSeted = TRUE;

      $currentWeekNum = ManagerHolder::get('PregnancyWeek')->getById($this->authEntity['pregnancyweek_current_id'], 'number');
      $pastWeeks = ManagerHolder::get('PregnancyWeek')->getAllWhere(array('number <=' => $currentWeekNum['number']), 'e.*');
      $weeks = array();
      foreach ($pastWeeks as $pw) {
        $weeks[] = $pw['id'];
      }

      $articles = ManagerHolder::get('PregnancyArticle')->getAllWhere(array('pregnancyweek_id' => $weeks, 'published' => 1));
      $this->layout->set('articles', $articles);

      $nextPregnancyArticleNotice = ManagerHolder::get('PregnancyArticle')->getNextPregnancyArticleNotice($this->authEntity, $this->settings);
      $this->layout->set('nextPregnancyArticleNotice', $nextPregnancyArticleNotice);
    }

    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    $this->setHeaders($page);

    $this->layout->set('pregnancyWeekSeted', $pregnancyWeekSeted);
    $this->layout->view('pregnancy_week');
  }

  /**
   * First year broadcast page
   */
  public function first_year() {

    $broadcasts = array();
    if (!empty($this->authEntity['age_of_child'])) {
      $broadcasts = ManagerHolder::get('FirstYearBroadcast')->getAllWhere(array('age_of_child <=' => $this->authEntity['age_of_child'],
                                                                                'article_id <>' => ''), 'e.*, article.*');
    }

    $header = array('title' => 'Мой малыш');
    $this->layout->set('header', $header);
    $this->layout->set('broadcasts', $broadcasts);
    $this->layout->view('first_year');
  }



}