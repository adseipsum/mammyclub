<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/base_auth_controller.php';
require_once APPPATH . 'logic/common/ConversionObserver.php';

/**
 * Controller.
 * @author Itirra - www.itirra.com
 */
class Auth_Controller extends Base_Auth_Controller {

  /**
   * Constructor.
   */
  public function Auth_Controller() {
    parent::Base_Auth_Controller();
  }

  /**
   * Email confirm process
   */
  public function email_confirm_process($key = null) {
    if ($key != null) {
      $authInfo = ManagerHolder::get('AuthInfo')->getOneWhere(array('activation_key' => $key), 'activation_key');
      if (!empty($authInfo) && !$this->isLoggedIn) {
        $data = ManagerHolder::get('User')->getOneWhere(array('auth_info_id' => $authInfo['id']), 'e.*, auth_info.*');
        $this->auth->login($data, FALSE);
        $this->authEntity = $this->auth->getAuthEntity();
      }
    }
    parent::email_confirm_process($key);
  }

  /**
   * Resend confirmation action.
   */
  public function resend_email_confirm() {
    if ($this->auth->isEmailConfirmed()) {
      uni_redirect($this->authConfig['redirect_after_register'], TRUE);
    }
    try {
      if (!$this->isLoggedIn) {
        throw new UserNotLoggedInException();
      }
      $entity = $this->auth->getAuthEntity();

      $this->load->helper('string');
      $config = $this->config->item('auth');
      $password = random_string('alnum', 8);
      ManagerHolder::get('AuthInfo')->updateById($entity['auth_info']['id'], 'password', md5($password));

      $entity['auth_info']['password'] = $password;

      ManagerHolder::get('TriggeredBroadcast')->sendSingleTriggeredLetter(TRIGGERED_BROADCAST_WELCOME, $entity);

      set_flash_notice('auth.message.confirmation_code_resent');
      uni_redirect($this->authConfig['url_email_confirm']);
    } catch (EmailSendingException $e) {
      set_flash_error('error.email_sending');
      uni_redirect($this->authConfig['url_email_confirm']);
    } catch (UserNotLoggedInException $e) {
      set_flash_error('auth.error.not_logged_in');
      uni_redirect($this->authConfig['url_email_confirm']);
    } catch (Exception $e) {
      set_flash_error($e->getMessage());
      uni_redirect($this->authConfig['url_email_confirm']);
    }
  }

  /**
   * PostConfirmEmailProcess
   */
  public function postConfirmEmailProcess() {
    ManagerHolder::get('User')->updateById($this->authEntity['id'], 'email_confirm_date', $date = date('Y-m-d H:i:s'));
    ConversionObserver::triggerEvent('e_mail_confirm');
    ManagerHolder::get('TriggeredBroadcast')->sendSingleTriggeredLetter(TRIGGERED_BROADCAST_AFTER_CONFIRM, $this->authEntity);

    // Check leads from Facebook
	  $authEntity = $this->auth->getAuthEntity();
		if (!empty($authEntity['inv_channel_src']) && $authEntity['inv_channel_src'] == 'facebook.com'){
			uni_redirect('личный-кабинет', TRUE);
		} else {
			uni_redirect('емейл-подтвержден', TRUE);
		}
  }

  /**
   * Confirmed email page
   */
  public function confirmed_email_page() {
    if (!$this->isLoggedIn) {
      show_404();
    }
    $this->layout->setLayout('main');
    $this->layout->view('confirmed_email_message');
  }

  /**
   * Register process action.
   */
  public function register_process() {
    $country = ManagerHolder::get('User')->detectCountry();
    if ($country == 'UA' && !empty($_POST['email']) && ManagerHolder::get('User')->domainBlockedInUa($_POST['email'])) {
      set_flash_error('Адрес e-mail, который вы указали, входит в список заблокированных на территории Украины.
                       Это означает, что с большой вероятностью вы не сможете получить доступ к этому почтовому ящику и читать нашу рассылку.
                       Укажите, пожалуйста, другой адрес e-mail.
                       Если у вас нет другого e-mail, вы можете создать его, например здесь https://www.google.com/intl/uk/gmail/about/');
      save_post();
      uni_redirect('регистрация');
    }

    if (isset($_GET['type']) && ($_GET['type']) == 'in_bottom_of_article') {
      $referrer = urldecode(get_referrer());
      if ($referrer && strpos($referrer, '/статья/') !== FALSE) {
        ConversionObserver::triggerEvent('every_reg_in_bottom_of_article', $referrer);
      }
    }
    parent::register_process();
  }

  /**
   * Register page.
   */
  public function register() {
    if (isset($_GET['type']) && ($_GET['type']) == 'in_top') {
      $referrer = urldecode(get_referrer());
      ConversionObserver::triggerEvent('click_reg_button_in_header', $referrer);
    }
    parent::register();
  }

  /**
   * Post register_process.
   * Override this in your class.
   */
  protected function postRegisterProcess() {
    ConversionObserver::triggerEvent('successful_reg');

    $user = array('id' => $this->auth->getAuthEntityId(),
                  'newsletter_questions' => TRUE,
                  'newsletter_comments' => TRUE,
                  'newsletter_shop' => TRUE,
                  'country' => $this->country,
                  'broadcast_channels' => ManagerHolder::get('User')->getDefaultBroadcastChannels());

    if (isset($_POST['pregnancyweek_id']) && !empty($_POST['pregnancyweek_id'])) {
      $user['newsletter'] = TRUE;
      $user['pregnancyweek_current_id'] = $_POST['pregnancyweek_id'];
      $user['pregnancyweek_current_started'] = date('Y-m-d');
    }
    ManagerHolder::get('User')->update($user);

    if (isset($_POST['pregnancyweek_id']) && !empty($_POST['pregnancyweek_id'])) {
      $this->auth->refresh();
      $this->authEntity = $this->auth->getAuthEntity();
      ManagerHolder::get('PregnancyWeek')->sendPregnancyWeekEmail($this->authEntity);
      uni_redirect('подтверждение-емейла-и-статья?pregnancy_week=' . $_POST['pregnancyweek_id'], TRUE);
    }
    uni_redirect($this->authConfig['url_email_confirm'], TRUE);
  }

  /**
   * Email confirmation with pregnancy week page
   */
  public function email_confirm_with_pregnancy_week() {
    if (!$this->isLoggedIn) {
      show_404();
    }

    if (isset($_GET['pregnancy_week']) && !empty($_GET['pregnancy_week'])) {
      $pregnancyWeek = ManagerHolder::get('PregnancyWeek')->getById($_GET['pregnancy_week'], 'e.*');
      if (empty($pregnancyWeek)) {
        show_404();
      }
      $this->layout->setLayout('main');
      $this->layout->set('pregnancyWeek', $pregnancyWeek);
      $this->layout->set('authEntity', $this->authEntity);
      $this->layout->view('email_confirm_and_pregnancy_week');
    } else {
      show_404();
    }
  }

  /**
   * Email confirmation and new question
   */
  public function email_confirm_new_question() {
    if (!$this->isLoggedIn) {
      show_404();
    }

    if (!isset($_GET['qId']) || empty($_GET['qId'])) {
      show_404();
    }

    $question = ManagerHolder::get('Question')->getById($_GET['qId'], 'e.*');

    $this->layout->set('qUrl', $question['page_url']);
    $this->layout->setLayout('main');
    $this->layout->view('email_confirm_and_question');
  }

  /**
   * Ajax email confirm message
   */
  public function ajax_email_confirm_message() {
    if (!$this->isLoggedIn) {
      show_404();
    }
    $this->layout->view('email_confirm_message');
  }
}