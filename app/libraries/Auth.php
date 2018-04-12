<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'logic/common/ManagerHolder.php';
require_once APPPATH . 'exceptions/common/NotConfirmedException.php';
require_once APPPATH . 'exceptions/common/ValidationException.php';
require_once APPPATH . 'exceptions/common/UserExistsException.php';
require_once APPPATH . 'exceptions/common/UserLoggedInException.php';
require_once APPPATH . 'exceptions/common/NoUserException.php';
require_once APPPATH . 'exceptions/common/UserNotLoggedInException.php';
require_once APPPATH . 'exceptions/common/UserBannedException.php';



/**
 * Auth.
 * @author Itirra - http://itirra.com
 *
 * @property CI_Base $ci
 */
class Auth {

  /** Password placeholder to be sent in email. */
  const PASSWORD_PLACEHOLDER = '***';

  /** Password placeholder to be sent in email. */
  const AFTER_LOGIN_REDIRECT_KEY = 'AFTER_LOGIN_REDIRECT_KEY';

  /** Config. */
  private $config;

  /** Auth entity. */
  private $authEntity = null;

  /** Manager instance. */
  private $manager;

  /** What. */
  private $what = 'e.*, auth_info.*';


  /**
   * Constructor.
   * @param string $config
   */
  public function Auth() {
    $this->ci =& get_instance();
    $this->ci->load->config('auth');
    $this->config = $this->ci->config->item('auth');
    if(isset($this->config['what']) && !empty($this->config['what'])) {
      $this->what = $this->config['what'];
    }
    if($this->isAdminArea() == FALSE) {
      $this->manager = ManagerHolder::get($this->config['entity_name']);
      $this->authEntity = $this->getAuthEntity();
      $this->checkRememberMeCookie();
    }
  }

  // -----------------------------------------------------------------------------------------
  // ------------------------------------- REGISTER ------------------------------------------
  // -----------------------------------------------------------------------------------------

	/**
   * Register.
   * @param array $data
   * @throws ValidationException
   * @throws UserExistsException
   * @throws UserLoggedInException
   * @return array
   */
  public function register($data) {
    // Logged in check
    if ($this->isLoggedIn()) {
      throw new UserLoggedInException();
    }
    // Validation by HuBuCo API's
	  $CI =& get_instance();
	  $CI->load->library("HuBuCo");
	  $emaiIsValid = $CI->hubuco->hubucoValidationEmail($data['email']);
	  if (!$emaiIsValid) {
		  $errorMsg = lang('register.validation.exeption.wrong_email');
		  log_message('error', kprintf($errorMsg, array('email' => $data['email'])));
      throw new Exception($errorMsg);
	  }

	  // Form validation
	  $vres = validate_array($data, $this->config['register']['fields'], 'registration');
	  if (!$vres['valid']) {
      log_message('error', 'register - validation exeption ' . print_r($vres['errors'], true));
      throw new ValidationException($vres['errors']);
    }

    // If no password in post - generate new password
    if(empty($data['password']) && isset($this->config['generate_password_on_register']) && $this->config['generate_password_on_register'] == TRUE) {
      $data['password'] = $this->generatePassword();
    }

    // Make new entity
    $entity = array(
    	'auth_info' => array(
        'email' => $data['email'],
        'password' => $this->preparePassword($data['password'])
      )
    );
    foreach ($this->config['register']['fields'] as $f => $vrules) {
      if ($f != 'email' && $f != 'password' && $f != 'auth_info' && isset($data[$f])) {
        $entity[$f] = $data[$f];
      }
    }
    $this->assignActivationKey($entity);

    // Insert the new Entity
    try {

      // Check if entity exists in order to remove "Duplicate entry" error log messages
      $emailExists = ManagerHolder::get('AuthInfo')->existsWhere(array('email' => $entity['auth_info']['email']));
      if ($emailExists) {
        throw new UserExistsException(UserExistsException::DUPLICATE_EMAIL);
      }

      $eId = $this->manager->insert($entity);
    } catch (Exception $e) {
      if ($e->getCode() == DOCTRINE_DUPLICATE_ENTRY_EXCEPTION_CODE) {
        $message = $e->getMessage();
        if (strpos($message, '@') > 0) {
          throw new UserExistsException(UserExistsException::DUPLICATE_EMAIL);
        } else {
          throw new UserExistsException(UserExistsException::DUPLICATE_PHONE);
        }
      } else {
        throw $e;
      }
    }

    if ($this->config['email_confirmation'] || $this->config['do_login_after_register']) {
      $entity = $this->manager->getById($eId, $this->what);

      if ($this->config['email_confirmation']) {
        $entity['auth_info']['password'] = $data['password'];
        $this->sendEmailConfirmation($entity);
      }

      if ($this->config['do_login_after_register']) {
        $entity['auth_info']['password'] = $this->preparePassword($data['password']);
        $this->login($entity, FALSE);
      }
    }

  }

  /**
   * After registration.
   * @param $authEntity
   * @param $password Unencrypted password for email
   * @throws UserNotLoggedInException
   */
  public function afterRegistration($entity = null, $password = null) {
    if (!isset($entity)) $entity = $this->getAuthEntity();
    if (!$entity) throw new UserNotLoggedInException();

    if (!isset($entity['processed_after_registration'])) return;

    if (!$entity['processed_after_registration']) {
      // Send email
      $data = array (
        'email' => $entity['auth_info']['email'],
        'password' => isset($password) ? $password : self::PASSWORD_PLACEHOLDER
      );
      ManagerHolder::get('EmailMandrill')->sendTemplate($entity['auth_info']['email'], 'after_registration', $data);

      // Update entity
      ManagerHolder::get('AuthEntity')->updateById($entity['auth_info']['id'], 'processed_after_registration', TRUE);
      $this->refresh();
    }
  }

  // -----------------------------------------------------------------------------------------
  // ------------------------------------- EMAIL CONFIRM -------------------------------------
  // -----------------------------------------------------------------------------------------

	/**
   * Send email confirmation.
   * @param $entity
   */
  public function sendEmailConfirmation($entity = null) {
    if (!$this->config['email_confirmation']) return;
    if (!isset($entity)) {
      if (!$this->isLoggedIn()) {
        throw new UserNotLoggedInException();
      }
      $entity = $this->getAuthEntity();
    }
		// Check leads from Facebook
	  if ($entity['inv_channel_src'] == 'facebook.com'){
		  return ManagerHolder::get('TriggeredBroadcast')->sendSingleTriggeredLetter(TRIGGERED_BROADCAST_WELCOME_FACEBOOK, $entity);
	  } else {
		  return ManagerHolder::get('TriggeredBroadcast')->sendSingleTriggeredLetter(TRIGGERED_BROADCAST_WELCOME, $entity);
	  }
  }

	/**
	 * Confirm email.
	 * @param string $key
	 * @throws UserNotLoggedInException
	 * @throws ValidationException
	 * @throws NoUserException
	 */
  public function confirmEmail($key) {
    // Logged in check
    if (!$this->isLoggedIn()) {
      throw new UserNotLoggedInException();
    }

    // Validate data
    $vRes = validate_field('activation_key', $key, $this->config['email_confirm']['fields']['activation_key'], 'email_confirm');
    if (!$vRes['valid']) {
      throw new ValidationException($vRes['errors']);
    }

    // sanitize activation key
    $key = preg_replace('/[^a-zA-Z0-9]/', '', $key);

    // Get user by activation key
    $entity = $this->manager->getOneWhere(array('auth_info.activation_key' => $key), $this->what);
    if (!$entity) {
      throw new NoUserException();
    }
    $entity['auth_info']['email_confirmed'] = TRUE;
    $entity['auth_info']['activation_key'] = '';

    $this->updateEntity($entity);
    $this->pushToSession($entity);
  }

	/**
   * Assign activation key.
   * @param array $entity
   */
  private function assignActivationKey(&$entity) {
    if ($this->config['email_confirmation']) {
      $entity['auth_info']['activation_key'] = $this->generateActivationKey();
      $entity['auth_info']['email_confirmed'] = FALSE;
    } else {
      $entity['auth_info']['email_confirmed'] = TRUE;
    }
  }

  /**
   * Generate activation key.
   * @return string
   */
  private function generateActivationKey() {
    return md5(rand() . microtime());
  }

  /**
   * Is email confirmed.
   * @param array $entity
   */
  public function isEmailConfirmed($entity = null) {
    $result = false;
    if (!isset($entity)) {
      $entity = $this->getAuthEntity();
    }
    if (!empty($entity)) {
      $result = $this->config['email_confirmation'] && $entity['auth_info']['email_confirmed'] == TRUE;
    }
    return $result;
  }

  // -----------------------------------------------------------------------------------------
  // ----------------------------------------- LOGIN -----------------------------------------
  // -----------------------------------------------------------------------------------------

	/**
   * Login.
   * @param array $data
   */
  public function login($data = null, $checkCaptcha = TRUE) {
    $this->ci->load->helper('common/itirra_validation');

    // Logged in check
    if ($this->isLoggedIn()) {
      throw new UserLoggedInException();
    }

    $loginFields = $this->config['login']['fields'];

    // Form validation
    $dataToValidate = array();
    if (isset($data['auth_info'])) {
      foreach ($loginFields as $key => $value) {
        if (in_array($key, array('password', 'email', 'phone'))) {
          $dataToValidate[$key] = $data['auth_info'][$key];
        } else {
          $dataToValidate[$key] = $data[$key];
        }
      }
    } else {
      foreach ($loginFields as $key => $value) {
        // If called from form.
        // Prepare password!
        if ($key == 'password') {
          $dataToValidate['password'] = $this->preparePassword($data['password']);
        } else {
          $dataToValidate[$key] = $data[$key];
        }
      }
    }
    $vres = validate_array($dataToValidate, $this->config['login']['fields'], 'login');
    if (!$vres['valid']) {
      throw new ValidationException($vres['errors']);
    }

    // Auth attempts
    if ($checkCaptcha) {
      $captcha = $this->getCaptcha();
      if ($this->isAuthAttemptsExhausted('login') && !empty($captcha)) {
        if (!$this->checkCaptcha()) {
          $error = array('captcha' => array(array('error' => 'auth.error.wrong_captcha')));
          $this->addCaptcha();
          throw new ValidationException($error);
        }
      }
    }


    // Get entity
    $where = array();
    foreach ($dataToValidate as $k => $v) {
      if (in_array($k, array('password', 'email', 'phone'))) {
        $where['auth_info.' . $k] = $dataToValidate[$k];
      } else {
        $where[$k] = $dataToValidate[$k];
      }
    }
    $entity = array();
    if (!empty($where)) {
      $entity = $this->manager->getOneWhere($where, $this->what);
    }
    if (empty($entity)) {
      $this->increaseAuthAttempts('login');
      throw new NoUserException();
    }

    // Check if banned
    if ($entity['auth_info']['banned']) {
      $this->increaseAuthAttempts('login');
      throw new UserBannedException($entity['auth_info']['banned_reason']);
    }

    $this->clearAuthAttempts('login');
    $this->pushToSession($entity);

    // Remember ME
    if (isset($data[$this->config['rememberme_checkbox_name']]) && $data[$this->config['rememberme_checkbox_name']] == TRUE) {
      $this->setRememberMeCookie($entity);
    }

    // Check if email confirmed
    if ($this->config['email_confirmation'] && !$entity['auth_info']['email_confirmed'] && !$this->config['do_login_after_register']) {
      $this->sendEmailConfirmation($entity);
      throw new NotConfirmedException(NotConfirmedException::NOT_CONFIRMED_EMAIL);
    }

    // Check if phone confirmed
    if ($this->config['phone_confirmation'] && !$entity['auth_info']['phone_confirmed'] && !$this->config['do_login_after_register']) {
      $this->sendPhoneConfirmation($entity);
      throw new NotConfirmedException(NotConfirmedException::NOT_CONFIRMED_PHONE);
    }

    $this->assignLastIp($entity);
    $this->assignLastLogin($entity);
    $this->updateEntity($entity);
    $this->pushToSession($entity);
  }

	/**
   * Prepare entity before login.
   *
   * @param array $data
   * @return array
   */
  private function prepareEntityBeforeLogin($data) {
    if ( ! isset($data['email']) || empty($data['email']) ||
         ! isset($data['password']) || empty($data['password'])) {
      throw new Exception();
    }
    $entity = array(
    	'auth_info' => array(
        'email' => $data['email'],
      	'password' => $this->preparePassword($data['password'])
      )
    );
    return $entity;
  }

	/**
   * Is logged in.
   * @return bool
   */
  public function isLoggedIn() {
    return $this->getAuthEntity() ? TRUE : FALSE;
  }

	/**
   * Logout.
   * Clear session and remember me cookie.
   */
  public function logout() {
    // Remove remember me cookie
    $this->removeRememberMeCookie();

    // Save session keys
    $sessionKeysToSave = array();
    if (isset($this->config['save_session_keys_on_logout']) && !empty($this->config['save_session_keys_on_logout'])) {
      foreach ($this->config['save_session_keys_on_logout'] as $sessionKey) {
        $sessionKeysToSave[$sessionKey] = $this->ci->session->userdata($sessionKey);
      }
    }

    // Destroy session
    $this->ci->session->clear();

    // Recover keys
    if (!empty($sessionKeysToSave)) {
      foreach ($sessionKeysToSave as $key => $value) {
        $this->ci->session->set_userdata($key, $value);
      }
    }
  }

  // -----------------------------------------------------------------------------------------
  // ----------------------------------- FORGOT PASSWORD -------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Forgot password.
   * @param string $email
   * @param bool $sendView
   * @throws NoUserException
   */
  public function forgotPassword($email, $sendView = FALSE) {
  	$this->checkEmail($email);

  	$where = array(
      'auth_info.email' => $email
  	);
  	$entity = $this->manager->getOneWhere($where, $this->what);
  	if (empty($entity)) {
  	  throw new NoUserException();
  	}

  	$newPassword = $this->generatePassword();
  	$entity['auth_info']['password'] = $this->preparePassword($newPassword);
  	$this->updateEntity($entity);

  	$this->sendEmailForgotPassword($entity, $newPassword, $sendView);
  }

  /**
   * Send email for forgot password.
   * @param string $entity
   * @param string $newPassword
   * @param bool $sendView
   */
  private function sendEmailForgotPassword($entity, $newPassword, $sendView) {
  	$data = array(
      'password' => $newPassword
  	);
  	if($sendView == FALSE) {
  	  ManagerHolder::get('EmailMandrill')->sendTemplate($entity['auth_info']['email'], 'forgot_password', $data);
  	} else {
  	  ManagerHolder::get('EmailMandrill')->sendView($entity['auth_info']['email'], 'forgot_password', $data);
  	}
  }

  /**
   * Generate password.
   * @return string
   */
  private function generatePassword() {
  	$this->ci->load->helper('string');
  	return random_string('alnum', 8);
  }

  // -----------------------------------------------------------------------------------------
  // ------------------------------------------- SESSION -------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Push entity to session.
   * @param $entity
   */
  private function pushToSession($entity) {
    if (!is_array($entity)) $entity = $entity->toArray();
    $this->ci->session->set_userdata($this->config['entity_session_key'], $entity);
  }

  /**
   * Pop entity from session.
   * @return array
   */
  private function popFromSession(){
    return $this->ci->session->userdata($this->config["entity_session_key"]);
  }

  // -----------------------------------------------------------------------------------------
  // --------------------------------------- AUTH ENTITY -------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Update entity.
   *
   * @param $entity
   */
  private function updateEntity($entity) {
    $this->manager->update($entity);
  }

  /**
   * Get auth entity.
   * @param $field Field name to get.
   * @return array
   */
  public function getAuthEntity($field = null) {
    $result = null;
    $authEntity = $this->popFromSession();
    if (!isset($field)) {
      // Return full auth entity
      $result = $authEntity;
    } else {
      // Try to return a certain field
      // Auth entity first will be transformed into plain array with dots
      // so you can specify nested fields
      $authEntity = array_make_plain_with_dots($authEntity);
      if (isset($authEntity[$field])) {
        $result = $authEntity[$field];
      }
    }
    return $result;
  }

  /**
   * Get auth entity id.
   *
   * @return id
   */
  public function getAuthEntityId() {
    $authEntity = $this->popFromSession();
    return !empty($authEntity) ? $authEntity['id'] : null;
  }

  /**
   * Refresh auth entity from database.
   */
  public function refresh() {
    if (!$this->isLoggedIn()) return;
    $authEntity = $this->manager->getById($this->getAuthEntityId(), $this->what);
    $this->manager->addAvailableSalestoUser($authEntity);
    $this->pushToSession($authEntity);
  }

  // -----------------------------------------------------------------------------------------
  // ------------------------------------------ MISC -----------------------------------------
  // -----------------------------------------------------------------------------------------

  /**
   * Check email.
   *
   * @param string $email
   * @throws Exception
   */
  private function checkEmail($email) {
    $this->ci->load->helper('email');
    if (!valid_email($email)) throw new Exception('Invalid email.');
  }

  /**
   * Prepare password.
   * @param $password
   * @return string
   */
  public function preparePassword($password) {
    if (empty($password)) {
      return $password;
    }
    return md5($password);
  }

  /**
   * Assign last ip.
   * @param $entity
   */
  private function assignLastIp(&$entity) {
    $entity['auth_info']['last_ip'] =  $this->ci->input->ip_address();;
  }

  /**
   * Assign last login date/time.
   * @param $entity
   */
  private function assignLastLogin(&$entity) {
    $entity['auth_info']['last_login'] = date(DOCTRINE_DATE_FORMAT);
  }



//   public function setAfterLoginUrl($v = NULL) {
//     if ($v === FALSE) {
//       $this->ci->session->set_userdata(self::AFTER_LOGIN_REDIRECT_KEY, NULL);
//     } elseif ($v === NULL) {
//       $this->ci->session->set_userdata(self::AFTER_LOGIN_REDIRECT_KEY, current_url());
//     }

//   }

//   public function getAfterLoginUrl() {
//     $url = $this->ci->session->userdata(self::AFTER_LOGIN_REDIRECT_KEY);
//     $this->setAfterLoginUrl(FALSE);
//     return $url;
//   }


  //############################################ AuthAttempts ############################################/

  /**
   * Increases the auth attempts.
   * @param string $type
   * @return void
   */
  public function increaseAuthAttempts($type = 'login') {
    $attempt = new AuthAttempt();
    $attempt['ip'] = $this->ci->input->ip_address();
    $attempt['type'] = $type;
    ManagerHolder::get('AuthAttempt')->insert($attempt);
  }

  /**
   * Clear the auth attempts.
   * @param string $type
   * @return void
   */
  public function clearAuthAttempts($type = 'login') {
    $ip = $this->ci->input->ip_address();
    $keyValueArray['ip'] = $ip;
    $keyValueArray['type'] = $type;
    ManagerHolder::get('AuthAttempt')->deleteAllWhere($keyValueArray);
  }


  //############################################ Captcha ############################################/


  /**
   * Get captcha.
   */
  public function getCaptcha() {
    return $this->ci->session->userdata("captcha");
  }

  /**
   * Check captcha.
   */
  public function checkCaptcha() {
    $captcha = $this->getCaptcha();
    if (isset($_POST['captcha']) && ($captcha != null)) {
      if ($_POST['captcha'] == $captcha['word']) {
        $this->ci->session->unset_userdata('captcha');
        return TRUE;
      } else {
        $this->ci->session->unset_userdata('captcha');
        return FALSE;
      }
    } else {
      $this->ci->session->unset_userdata('captcha');
      return FALSE;
    }
  }

  /**
   * Add captcha.
   */
  public function addCaptcha() {
    $this->ci->load->plugin('captcha');
    $captcha_dir = trim($this->config['captcha_path'], './');
    $vals = array(
      'img_path'     => $this->config['captcha_path'],
      'img_url'      => base_url() . $captcha_dir . '/',
      'font_path'    => $this->config['captcha_fonts_path'],
      'font_size'    => $this->config['captcha_font_size'],
      'img_width'    => $this->config['captcha_width'],
      'img_height'   => $this->config['captcha_height'],
      'show_grid'    => $this->config['captcha_grid'],
      'expiration'   => $this->config['captcha_expire'],
      'symbols_count'=> $this->config['captcha_symbols_count']
    );
    $this->captcha = create_captcha($vals);
    $this->ci->session->set_userdata('captcha', $this->captcha);
  }

  /**
   * Checks if the auth attempts of this type is more than constant.
   * @param string $type
   * @return boolean
   */
  public function isAuthAttemptsExhausted($type = 'login') {
    $ip = $this->ci->input->ip_address();
    $count = ManagerHolder::get('AuthAttempt')->getCountWhere(array('ip' => $ip, 'type' => $type));
    if ($count >= $this->config['auth_attempts'] && $this->config['auth_attempts'] !== null) {
      return TRUE;
    } else {
      return FALSE;
    }
  }


 //############################################ Remember me ############################################/

 /**
  * Generate remember me key.
  * @param int $id
  * @return string
  */
 private function generateRememberMeKey($id) {
   $key = $this->config['rememberme_cookie_name'] . "_id_" . $id;
   return md5($key);
 }

 /**
  * Check remember me key.
  * @param $key
  * @param $id
  * @return bool
  */
 private function checkRememberMeKey($key, $id) {
   return $key == $this->generateRememberMeKey($id);
 }

 /**
  * Set remember me cookie.
  * @param $entity
  * @return void
  */
 public function setRememberMeCookie($entity) {
   $this->ci->load->helper("cookie");
   $data = array (
     "key" => $this->generateRememberMeKey($entity['id']),
     "id" => $entity['id'],
   );
   $cookie = array (
     "name"   => $this->config['rememberme_cookie_name'],
     "value"   => serialize($data),
     "expire"  => $this->config['rememberme_cookie_expire']
   );
   set_cookie($cookie);
 }

 /**
  * Remove remember me cookie
  * @param array $entity
  */
 public function removeRememberMeCookie() {
   $this->ci->load->helper('cookie');
   delete_cookie($this->config['rememberme_cookie_name']);
 }

 /**
  * Check remember me cookie.
  * Does autologin.
  * @return void
  */
 public function checkRememberMeCookie() {
   if (!$this->isLoggedIn()) {
     $this->ci->load->helper('cookie');
     $rememberMeCookie = $this->ci->input->cookie($this->config['rememberme_cookie_name']);
     if ($rememberMeCookie != "") {
       $rememberMeCookie = unserialize($rememberMeCookie);
       if (isset($rememberMeCookie['key']) && isset($rememberMeCookie['id'])) {
         if ($this->checkRememberMeKey($rememberMeCookie['key'], $rememberMeCookie['id'])) {
           $entity = $this->manager->getById($rememberMeCookie['id'], 'e.*, auth_info.*');
           if ($entity) {
             $this->login($entity);
           }
         }
       }
     }
   }
 }

 /**
  * Check for Admin Area
  * @return boolean
  */
 private function isAdminArea() {
   $result = FALSE;
   $this->ci->load->config('admin', TRUE);
   $adminBaseRoute = site_url($this->ci->config->item('base_route', 'admin'));
   if(strpos(current_url(), $adminBaseRoute) !== FALSE) {
     $result = TRUE;
   }
   return $result;
 }

//  //############################################ URI permissions ############################################/
//
//  /**
//   * Check URI permissions.
//   * @return void
//   */
//  public function checkUriPermissions() {
//    if ($this->authEntity) {
//      if ($this->authEntity['auth_info']['banned']) {
//        $this->dispatchRedirect('banned');
//      }
//      if ($this->config['email_confirmation']) {
//        if ($this->authEntity['auth_info']['confirmed'] == TRUE) {
//          return TRUE;
//        } else {
//          set_flash_warning('need_to_be_confirmed');
//          $this->saveRedirectUrlToSession();
//          $this->dispatchRedirect('not_confirmed');
//        }
//      } else {
//        return TRUE;
//      }
//      return TRUE;
//    } else {
//      set_flash_warning('need_to_be_logged_in');
//      $this->saveRedirectUrlToSession();
//      $this->dispatchRedirect('login');
//    }
//  }
//
//
//  //############################################ Redirect methods ############################################/
//
//  /**
//   * Save redirect URL to session.
//   * @return void
//   */
//  public function saveRedirectUrlToSession($url = null) {
//    $this->ci->session->set_userdata("redirect_url", ($url)? $url : $this->ci->uri->uri_string());
//  }
//
//  /**
//   * Sets flash message
//   * @param  $key
//   * @return void
//   */
//  protected function setAuthFlashMessage($key) {
//    set_flash_message("error", "auth.$key");
//  }
//
//  /**
//   *
//   * @param  $key
//   * @return void
//   */
//  public function setFlashMessageNeedToBeLoggedId() {
//    $this->setAuthFlashMessage('need_to_be_logged_id');
//  }
//
//
//  /**
//   * Restore Redirect Url To Session
//   * @return void
//   */
//  // TODO: flash
//  public function restoreRedirectUrlToSession() {
//    $url = $this->ci->session->userdata('redirect_url');
//    if ($url) {
//      redirect($url);
//    } else {
//      return false;
//    }
//  }
//
//  /**
//   * Dispatch redirect.
//   * @param $action
//   * @return void
//   */
//  public function dispatchRedirect($action) {
//    switch ($action) {
//      case "login": {
//        redirect($this->config["login_url"]);
//        break;
//      }
//      case "register": {
//        redirect($this->config["register_url"]);
//        break;
//      }
//      case "not_allowed": {
//        redirect($this->config["not_allowed_url"]);
//        break;
//      }
//      case "not_confirmed": {
//        redirect($this->config["not_confirmed_url"]);
//        break;
//      }
//      case "registration_ok": {
//        redirect($this->config["registration_ok_url"]);
//        break;
//      }
//      case "banned": {
//        redirect($this->config["banned_url"]);
//        break;
//      }
//    }
//  }
//
//
//  //############################################ Update ############################################/
//
//  /**
//   * Update session.
//   */
//  public function updateSession() {
//    $oldEntity = $this->popFromSession();
//    $entity = $this->manager->getById($oldEntity["id"]);
//    $this->pushToSession($entity);
//  }
//
//  /**
//   * Update entity.
//   * @param $entity
//   */
//  public function updateEntity($entity) {
//    if (empty($entity)) return;
//    $entity['id'] = $this->getAuthEntityId();
//    $result = $this->manager->update($entity);
//    $this->updateSession();
//    return $result;
//  }

}