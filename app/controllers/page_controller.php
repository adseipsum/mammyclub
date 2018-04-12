<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Page controller.
 * @author Itirra - http://itirra.com
 */
class Page_Controller extends Base_Project_Controller {

  /**
   * Constructor.
   */
  public function Page_Controller() {
    parent::Base_Project_Controller();
    $this->load->helper('common/itirra_date');
  }

  /**
   * Index page.
   */
  public function index() {
    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    if(empty($page)) {
      show_404();
    }

    $this->setHeaders($page);

    $page['content'] = '';
    if($page['name'] == 'О проекте') {
      $page['content'] = $this->settings['about_text'];
    } elseif($page['name'] == 'Связаться с нами') {
      $page['content'] = $this->settings['contact_text'];
    } elseif($page['name'] == 'Пользовательское соглашение') {
      $page['content'] = $this->settings['conditions_text'];
    }
    $this->layout->set('page', $page);
    $this->layout->view('page');
  }

  /**
  * Contact us page 
  */
  public function contact_us() {
    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    $this->setHeaders($page);

    if ($this->isLoggedIn) {
      $this->layout->set('email', $this->authEntity['auth_info']['email']);
    }

    $this->layout->view('contact_us');
  }

  /**
   * Contact us process
   */
  public function contact_us_process() {
    $this->load->helper('common/itirra_validation');
    simple_validate_post('email', 'message');

    $message = "Пользователь с email: {$_POST['email']} написал сообщение:" . PHP_EOL . $_POST['message'];
    $subject = $this->settings['contact_us_email_subject'];

    ManagerHolder::get('EmailNotice')->send_notice_to_admins_from_contact_us_page($subject, $message);

    set_flash_notice('Обращение отправлено.');
    redirect('связаться-с-нами');
  }

}