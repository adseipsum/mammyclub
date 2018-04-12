<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/base/base_controller.php';
require_once APPPATH . 'exceptions/common/ValidationException.php';
require_once APPPATH . 'exceptions/common/UserExistsException.php';

/**
 * Base Auth Controller
 * @author Itirra - www.itirra.com
 */
class Base_Auth_Controller extends Base_Controller {

  /**
   * @var CI_Session
   */
  public $session;

  /**
   * @var Auth
   */
  public $auth;

  /** Libraries to load.*/
  protected $libraries = array('common/DoctrineLoader',
                               'Session',
                               'common/Auth');

  /** Configs to load.*/
  protected $configs = array('auth');

  /** Helpers to load.*/
  protected $helpers = array('url', 'common/itirra_validation', 'common/itirra_messages', 'common/itirra_language', 'common/itirra_resources', 'common/itirra_ajax');

  /** Is logged In.*/
  protected $isLoggedIn;

  /** AuthEntity.*/
  protected $authEntity;

  /** Auth config. */
  protected $authConfig;

  /**
   * Constructor.
   */
  public function Base_Auth_Controller() {
    parent::Base_Controller();

    $this->lang->load('validation_messages', $this->config->item('language'));
    $this->lang->load('auth', $this->config->item('language'));
    $this->authConfig = $this->config->item('auth');
    $this->isLoggedIn = $this->auth->isLoggedIn();
    $this->authEntity = $this->auth->getAuthEntity();
    $this->layout->setLayout($this->config->item('layout', 'auth'));
    $this->layout->setModule($this->config->item('module', 'auth'));
    $this->layout->set('isLoggedIn', $this->isLoggedIn);
    $this->layout->set('authEntity', $this->authEntity);

    $settings = ManagerHolder::get('Settings')->getAllKV();
    $this->layout->set('settings', $settings);

    if (is_ajax()) {
      $this->layout->setLayout('ajax');
      $this->layout->set('isAjax', TRUE);
    }

  }

  // -----------------------------------------------------------------------------------------
  // ------------------------------------- REGISTER ------------------------------------------
  // -----------------------------------------------------------------------------------------

	/**
   * Register page.
   */
  public function register() {
    if ($this->isLoggedIn) {
      keep_flash_message();
      if ($this->authConfig['email_confirmation'] == TRUE) {
        if ($this->authEntity['auth_info']['email_confirmed']) {
          uni_redirect($this->authConfig['redirect_after_register'], TRUE);
        } else {
          uni_redirect($this->authConfig['url_email_confirm']);
        }
      }
      if ($this->authConfig['phone_confirmation'] == TRUE) {
        if ($this->authEntity['auth_info']['phone_confirmed']) {
          uni_redirect($this->authConfig['redirect_after_register'], TRUE);
        } else {
          uni_redirect($this->authConfig['url_phone_confirm']);
        }
      }
      uni_redirect($this->authConfig['redirect_after_register']);
    }
    $this->setRegisterViewData();
    $this->layout->view($this->authConfig['view_register']);
  }

  /**
   * Register process action.
   */
  public function register_process() {
    try {
      $this->auth->register($_POST);
      $this->postRegisterProcess();
    } catch (ValidationException $e) {
      save_post(array('password', 'password_confirmation'));
      set_flash_validation_errors($e->getErrors());
      uni_redirect($this->authConfig['url_register']);
    } catch (UserExistsException $e) {
      save_post(array('password', 'password_confirmation'));
      set_flash_error('auth.error.user_exists');
      uni_redirect($this->authConfig['url_register']);
    } catch (UserLoggedInException $e) {
      set_flash_warning('auth.error.logout_first_to_register');
      uni_redirect($this->authConfig['url_register']);
    } catch (EmailSendingException $e) {
      set_flash_error('error.email_sending');
      uni_redirect($this->authConfig['url_register']);
    } catch (NotConfirmedException $e) {
      set_flash_warning('auth.error.email_not_confirmed');
      uni_redirect($this->authConfig['url_email_confirm']);
    } catch (Exception $e) {
      if ($this->authConfig['do_login_after_register']) {
        $this->auth->logout();
      }
      set_flash_error($e->getMessage());
      uni_redirect($this->authConfig['url_register']);
    }
  }

  /**
   * Post register_process.
   * Override this in your class.
   */
  protected function postRegisterProcess() {
    if ($this->authConfig['email_confirmation']) {
      set_flash_notice('auth.message.confirmation_email_sent');
      uni_redirect($this->authConfig['url_email_confirm']);
    } else {
      uni_redirect($this->authConfig['redirect_after_register']);
    }
  }

  /**
   * Set register view data.
   * Override this in your class.
   */
  protected function setRegisterViewData() {}

  // -----------------------------------------------------------------------------------------
  // ---------------------------------- EMAIL CONFIRM ----------------------------------------
  // -----------------------------------------------------------------------------------------

	/**
   * Email confirm page.
   */
  public function email_confirm() {
    if (!$this->auth->isLoggedIn()) {
      set_flash_error("auth.error.message.login_first_to_confirm");
      uni_redirect($this->authConfig['url_login']);
    } else if ($this->auth->isEmailConfirmed()) {
      set_flash_notice("auth.message.already_confirmed");
      uni_redirect($this->authConfig['redirect_after_login'], TRUE);
    } else {
      $this->layout->view($this->authConfig['view_email_confirm']);
    }
  }

	/**
   * Email confirm process action.
   * @param string $key
   */
  public function email_confirm_process($key = null) {
    if ($this->auth->isEmailConfirmed()) {
      set_flash_notice("auth.message.already_confirmed");
      uni_redirect($this->authConfig['redirect_after_login']);
    }
    if ($key == null && isset($_POST['activation_key'])) {
      $key = $_POST['activation_key'];
    }
    try {
      $this->auth->confirmEmail($key);
      $this->postConfirmEmailProcess();
    } catch (ValidationException $e) {
      set_flash_validation_errors($e->getErrors());
      uni_redirect($this->authConfig['url_email_confirm']);
    } catch (NoUserException $e) {
      set_flash_error('auth.error.wrong_activation_key');
      uni_redirect($this->authConfig['url_email_confirm']);
    } catch (EmailSendingException $e) {
      set_flash_error('error.email_sending');
      uni_redirect($this->authConfig['url_email_confirm']);
    } catch (UserNotLoggedInException $e) {
      set_flash_error("auth.error.message.login_first_to_confirm");
      uni_redirect($this->authConfig['url_login']);
    } catch (Exception $e) {
      set_flash_error($e->getMessage());
      uni_redirect($this->authConfig['url_email_confirm']);
    }
  }

  public function postConfirmEmailProcess() {
    uni_redirect($this->authConfig['redirect_after_register']);
  }

  /**
   * Resend confirmation action.
   */
  public function resend_email_confirm() {
    if ($this->auth->isEmailConfirmed()) {
      uni_redirect($this->authConfig['redirect_after_register'], TRUE);
    }
    try {
      $this->auth->sendEmailConfirmation();
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

  // -----------------------------------------------------------------------------------------
  // --------------------------------------- LOGIN -------------------------------------------
  // -----------------------------------------------------------------------------------------

	/**
   * Login.
   */
  public function login() {
    if ($this->isLoggedIn) $this->postLoginProcess();
    $this->setLoginViewData();
    $captcha = $this->auth->getCaptcha();
    if ($captcha) {
      $this->layout->set('captcha_image', $captcha['image']);
    }
    $this->layout->view($this->authConfig['view_login']);
  }

	/**
   * Login action.
   */
  public function login_process() {
    if ($this->isLoggedIn) {
      uni_redirect($this->authConfig['redirect_after_login'], TRUE);
    }
    try {
      $this->auth->login($_POST);
      $this->postLoginProcess();

    // -- NO USER
    } catch (NoUserException $e) {
      set_flash_error('auth.error.wrong_email_password');
      save_post(array('captcha', 'password'));
      if ($this->auth->isAuthAttemptsExhausted('login')) {
        $this->auth->addCaptcha();
      }
      uni_redirect($this->authConfig['url_login']);

    // -- VALIDATION
    } catch (ValidationException $e) {
      set_flash_validation_errors($e->getErrors());
      save_post(array('captcha', 'password'));
      uni_redirect($this->authConfig['url_login']);

    // -- NOT CONFIRMED
    } catch (NotConfirmedException $e) {
      set_flash_warning('auth.error.email_not_confirmed');
      uni_redirect($this->authConfig['url_email_confirm']);

    // -- ALREADY LOGGED IN
    } catch (UserLoggedInException $e) {
    	set_flash_warning('auth.error.logout_first_to_login');
      uni_redirect($this->authConfig['url_login']);

    // -- BANNED
    } catch (UserBannedException $e) {
      $bannedMessage = $e->getMessage();
      $bannedMessage = empty($bannedMessage) ? lang('auth.error.banned') : lang('auth.error.banned_because') . $bannedMessage;
      set_flash_error($bannedMessage);
      uni_redirect($this->authConfig['url_login']);

    // -- OTHER ERROR
    } catch (Exception $e) {
      set_flash_error($e->getMessage());
      save_post(array('captcha', 'password'));
      uni_redirect($this->authConfig['url_login']);
    }
  }

  /**
   * Set login view data.
   * Override this in your class.
   */
  protected function setLoginViewData() {}

  /**
   * Post login process
   * Override this in your class.
   */
  protected function postLoginProcess() {
    $this->authEntity = $this->auth->getAuthEntity();
    if ($this->authConfig['email_confirmation'] == TRUE && !$this->authEntity['auth_info']['email_confirmed']) {
      uni_redirect($this->authConfig['url_email_confirm']);
    }
    if ($this->authConfig['phone_confirmation'] == TRUE && !$this->authEntity['auth_info']['phone_confirmed']) {
      uni_redirect($this->authConfig['url_phone_confirm']);
    }
    if (empty($this->authConfig['redirect_after_login'])) {
      if (is_ajax()) {
        die(ajax_result_redirect_current_url_top());
      } else {
        redirect_to_referral('/');
      }
    } else {
      uni_redirect($this->authConfig['redirect_after_login'], TRUE);
    }
  }

  // -----------------------------------------------------------------------------------------
  // -------------------------------------- FORGOT PASSWORD ----------------------------------
  // -----------------------------------------------------------------------------------------

	/**
   * Forgot password page.
   */
  public function forgot_password() {
    if ($this->isLoggedIn) show_404();
    $this->layout->view($this->authConfig['view_forgot_password']);
  }

  /**
   * Forgot password action.
   */
  public function forgot_password_process() {
  	if ($this->isLoggedIn) show_404();
  	try {
  		$this->auth->forgotPassword($_POST['email']);
  		set_flash_notice('auth.message.new_password_sent');
  		uni_redirect($this->authConfig['url_login']);
  	} catch (NoUserException $e) {
  		set_flash_error('auth.error.wrong_email');
  		uni_redirect($this->authConfig['url_forgot_password']);
  	} catch (Exception $e) {
  		set_flash_error($e->getMessage());
  		uni_redirect($this->authConfig['url_forgot_password']);
  	}
  }

  // -----------------------------------------------------------------------------------------
  // -------------------------------------- LOGOUT -------------------------------------------
  // -----------------------------------------------------------------------------------------

	/**
   * Logout action.
   */
  public function logout() {
    $this->auth->logout();
    if (empty($this->authConfig['redirect_after_logout'])) {
      if (is_ajax()) {
        die(ajax_result_redirect_current_url_top());
      } else {
        redirect_to_referral('/');
      }
    } else {
      uni_redirect($this->authConfig['redirect_after_logout'], TRUE);
    }
  }

}