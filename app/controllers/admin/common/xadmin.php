<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * Xadmin controller.
 * @author Alexei Chizhmakov (Itirra - http://itirra.com)
 */
class xAdmin extends Base_Admin_Controller {

  /**
   * Constuctor.
   * @return xAdmin
   */
  public function xAdmin() {
    parent::Base_Admin_Controller();
  }

  /**
   * (non-PHPdoc)
   * @see Base_Admin_Controller::getFieldsFromManager()
   */
  protected function getFieldsFromManager() {
  }

  /**
   * (non-PHPdoc)
   * @see Base_Admin_Controller::index()
   */
  public function index() {
    redirect($this->get_after_login_redirect());
  }


  /****************************************************************************************************************/
  /**********************************************  Login/Logout  **************************************************/
  /****************************************************************************************************************/

  /**
   * Login action.
   */
  public function login() {
    $adminId = $this->session->userdata(self::LOGGED_IN_ADMIN_SESSION_KEY);
    if ($adminId) {
      redirect($this->get_after_login_redirect());
    }

    $this->load->library('common/Auth');

    if (isset($_POST['login']) && isset($_POST['password'])) {

      $captcha = $this->auth->getCaptcha();
      if ($this->auth->isAuthAttemptsExhausted('login')) {
        if (!$this->auth->checkCaptcha()) {
          $error = array('captcha' => array(array('error' => 'auth.error.wrong_captcha')));
          $this->auth->addCaptcha();
          set_flash_error('admin.messages.login_wrong_captcha');
          redirect($this->adminBaseRoute . "/login");
        }
      }

      $login    = $_POST['login'];
      $password = $_POST['password'];
      if ($admin = ManagerHolder::get("Admin")->getAdminByLoginPass($login, $password)) {
        if (is_not_empty($admin['allowed_ips']) && isset($_SERVER['REMOTE_ADDR']) && !in_array($_SERVER['REMOTE_ADDR'], explode(',', str_replace(' ', '', $admin['allowed_ips'])))) {
          set_flash_error('admin.messages.login_wrong_email_password');
          redirect($this->adminBaseRoute . "/login");
        }
        // Remember ME ----------
        if (isset($_POST['remember']) && !empty($_POST['remember'])) {
          $this->load->helper('cookie');
          $rememberKey = 'remember_key';
          $rememberKeyValue = md5($admin['id'] . 'Itirra+salt' . $rememberKey);
          set_cookie($rememberKey, $rememberKeyValue, 60 * 60 * 24 * 365 * 10); // 10 years
        }
        // ----------------------
        $this->session->set_userdata(self::LOGGED_IN_ADMIN_SESSION_KEY, $admin);
        $this->auth->clearAuthAttempts();
        if ($admin['password_changed'] == "N") {
          set_flash_warning('admin.messages.please_change_password');
          redirect($this->adminBaseRoute . "/change_info");
        }
        redirect($this->get_after_login_redirect());
      } else {
        set_flash_error('admin.messages.login_wrong_email_password');

        $this->auth->increaseAuthAttempts('login');
        if ($this->auth->isAuthAttemptsExhausted('login')) {
          $this->auth->addCaptcha();
        }

        redirect($this->adminBaseRoute . "/login");
      }
    }


    $captcha = $this->auth->getCaptcha();
    if ($captcha) {
      $this->layout->set('captcha_image', $captcha['image']);
    }

    $this->layout->view('login');
  }

  /**
   * Change info action.
   */
  public function change_info() {
    if (sizeof($_POST) > 0) {
      $oldPasswordChanged = $this->loggedInAdmin['password_changed'];
      if (isset($_POST['new_password']) && isset($_POST['password']) && !empty($_POST['new_password']) && !empty($_POST['password'])) {
        if (ManagerHolder::get("Admin")->getAdminByLoginPassCount($_POST['email'], $_POST['password']) > 0) {
          $this->loggedInAdmin['password'] = md5($_POST['new_password']);
          $this->loggedInAdmin['password_changed'] = "Y";
        } else {
          set_flash_error('admin.wrong_old_password');
          redirect($this->adminBaseRoute . "/change_info");
        }
      }
      $this->loggedInAdmin['email'] = $_POST['email'];
      $this->loggedInAdmin['name'] = $_POST['name'];
      $this->loggedInAdmin['default_redirect'] = $_POST['default_redirect'];
      ManagerHolder::get("Admin")->update($this->loggedInAdmin);
      $this->session->set_userdata(self::LOGGED_IN_ADMIN_SESSION_KEY, $this->loggedInAdmin);

      set_flash_notice('admin.messages.info_successfully_changed');
      if ($oldPasswordChanged == 'N' && $this->loggedInAdmin['password_changed'] == 'Y') {
        redirect($this->get_after_login_redirect());
      }
      redirect($this->adminBaseRoute . "/change_info");
    }

    $menuItems = $this->config->item("menu_items", "admin");
    $this->layout->set("menuItems", $menuItems);

    $this->layout->set("skipStepUrl", $this->get_after_login_redirect());
    $this->layout->view('change_info');
  }


  /**
   * Forgot password action.
   */
  public function forgot_password() {
    $admin = $this->session->userdata(self::LOGGED_IN_ADMIN_SESSION_KEY);
    if ($admin) {
      redirect($this->adminBaseRoute);
    }
    if (isset($_POST['email'])) {
      if ($admin = ManagerHolder::get("Admin")->getAdminByEmail($_POST['email'])) {
        $this->load->helper('string');
        $newPassword = random_string('alnum', 8);
        $emailData = $admin;
        $emailData['login_url'] = site_url($this->adminBaseRoute . "/login");
        $emailData['new_password'] = $newPassword;
        try {
           ManagerHolder::get("Email")->sendView($_POST['email'], 'admin_forgot_password', $emailData);
           ManagerHolder::get("Admin")->updateById($admin['id'], 'password', md5($newPassword));
           set_flash_message('notice', 'admin.messages.password_successfully_sent');
           redirect($this->adminBaseRoute . "/login");
        } catch (Exception $e) {
          set_flash_message('error', 'error_general_send_mail');
          redirect($this->adminBaseRoute . "/forgot_password");
        }
      } else {
        set_flash_message('error', 'admin.messages.forgot_password_wrong_msg');
        redirect($this->adminBaseRoute . "/forgot_password");
      }
    }
    $this->layout->view('forgot_password');
  }

  /**
   * After Login Rediirect.
   */
  public function get_after_login_redirect() {
    $admin = $this->session->userdata(self::LOGGED_IN_ADMIN_SESSION_KEY);
    if ($admin) {

      if (isset($admin['email'])) {
        $this->session->set_userdata(self::LOGGED_IN_ADMIN_SESSION_KEY, ManagerHolder::get('Admin')->getOneWhere(array('email' => $admin['email']), 'e.*'));
      }

      $beforeLoginUrl = $this->session->userdata(self::SAVED_URL_SESSION_KEY);
      if ($beforeLoginUrl && strpos($beforeLoginUrl, 'ajax_admin_activity_tracking') === FALSE) {
        $this->session->unset_userdata(self::SAVED_URL_SESSION_KEY);
        return $beforeLoginUrl;
      }

      $permissions = explode('|', $admin['permissions']);

      if (isset($admin['default_redirect']) && !empty($admin['default_redirect']) && in_array($admin['default_redirect'] . '_view', $permissions)) {
        return $this->adminBaseRoute . '/' . $admin['default_redirect'];
      }

      foreach ($permissions as $p) {
        if (strstr($p, '_view') !== FALSE) {
          $entname = str_replace('_view', '', $p);
          return $this->adminBaseRoute . '/' . $entname;
        }
      }
    }
    $this->session->unset_userdata(self::LOGGED_IN_ADMIN_SESSION_KEY);
    show_404();
  }

  /**
   * Logout action.
   */
  public function logout() {
    $this->session->unset_userdata(self::LOGGED_IN_ADMIN_SESSION_KEY);
    set_flash_notice('admin.logged_out_message');
    redirect($this->adminBaseRoute . '/login');
  }

}