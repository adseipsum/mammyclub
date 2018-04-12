<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";
require_once APPPATH . "logic/common/ConversionObserver.php";

/**
 * Question controller.
 * @author Itirra - http://itirra.com
 */
class Question_Controller extends Base_Project_Controller {

  /** Auth config. */
  protected $authConfig;

  /**
   * Constructor.
   */
  public function Question_Controller() {
    parent::Base_Project_Controller();

    if($this->isLoggedIn == FALSE) {
      uni_redirect('вход');
    }

    $this->layout->setModule('question');
    $this->load->helper('common/itirra_date');

    $lastQuestions = ManagerHolder::get('Question')->getAll('e.*, user.*', 5);

    $showBroadcastBlock = TRUE;
    if ($this->isLoggedIn) {
      if ($this->authEntity['newsletter'] == 1 || !empty($this->authEntity['pregnancyweek_current_id'])) {
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

    $perPage = 5;
    $this->layout->set('perPage', $perPage);

    $questions = ManagerHolder::get('Question')->getAllWhereWithPager(array(), 1, $perPage, 'e.*, user.*');

    $this->layout->set('questions', $questions->data);
    $this->layout->set('pager', $questions->pager);

    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    $this->setHeaders($page);

    $this->layout->view('list');
  }

  /**
   * Question page.
   */
  public function question() {
    $question = ManagerHolder::get('Question')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    if(empty($question)) {
      show_404();
    }
    add_utf_params_to_shop_links($question['content'], $question['name']);
    $this->layout->set('question', $question);

    $this->setHeaders($question);

    $comments = ManagerHolder::get('QuestionComment')->getAllWhere(array('entity_id' => $question['id'], 'published' => TRUE), 'e.*, user.*');
    $status = ManagerHolder::get('User')->fields['status']['options'];

    $this->layout->set('status', $status);
    $this->layout->set('comments', $comments);

    $this->layout->view('view');
  }

  /**
   * Add Question page.
   */
  public function add_question() {
    $this->load->helper('common/itirra_validation');
    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => '/задать-вопрос/'));
    $referrer = urldecode(get_referrer());
    if ($referrer && strpos($referrer, '/статья/') !== FALSE) {
      ConversionObserver::triggerEvent('from_article_to_question', $referrer);
    }

    $this->setHeaders($page);
    $this->layout->view('add_question');
  }

  /**
   * Add Question process.
   */
  public function add_question_process() {

    $this->load->helper('common/itirra_validation');
    simple_validate_post(array('content', 'qName'));

    $newUser = FALSE;

    if($this->isLoggedIn == FALSE) {

      $this->authConfig = $this->config->item('auth');

      try {
        $this->auth->register($_POST);
      } catch (ValidationException $e) {
        save_post(array('password', 'password_confirmation'));
        set_flash_validation_errors($e->getErrors());
        redirect_to_referral();
      } catch (UserExistsException $e) {
        save_post(array('password', 'password_confirmation'));
        set_flash_error('auth.error.user_exists');
        redirect_to_referral();
      } catch (Exception $e) {
        set_flash_error($e->getMessage());
        redirect_to_referral();
      }
      $this->auth->refresh();
      $this->authEntity = $this->auth->getAuthEntity();
      $newUser = TRUE;
    }

    $this->load->helper('common/itirra_language');

    $this->imageFromTinyMceProcess($_POST['content']);

    $questionArr = array('name' => $_POST['qName'],
                         'content' => $_POST['content'],
                         'date' => date('Y-m-d H:i:s'),
                         'user_id' => $this->authEntity['id']);

    $qId = ManagerHolder::get('Question')->insert($questionArr);

    $qUrl = lang_url(mb_substr($_POST['qName'], 0, 255, 'UTF-8'), null, TRUE);
    $qUrl = '/консультация/' . $qId . $qUrl;
    $qUrl = surround_with_slashes(trim(mb_substr($qUrl, 0, 253, 'UTF-8'), '-'));

    ManagerHolder::get('Question')->updateById($qId, 'page_url', $qUrl);

    ManagerHolder::get('EmailNotice')->sendNewQuestionNoticeToAdmins($qId);

    set_flash_notice('Вопрос успешно добавлен!');
    if (!$newUser && $this->authEntity['auth_info']['email_confirmed'] == FALSE) {
      redirect($qUrl . '?not_confirmed=1');
    } elseif ($newUser) {
      redirect('подтвердите-емейл-и-вопрос?qId=' . $qId);
    }
    redirect($qUrl);
  }

}