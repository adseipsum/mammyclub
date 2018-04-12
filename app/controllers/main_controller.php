<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Main controller.
 * @author Itirra - http://itirra.com
 */
class Main_Controller extends Base_Project_Controller {

  /**
   * Constructor.
   */
  public function Main_Controller() {
    parent::Base_Project_Controller();
    $this->layout->setLayout('index');
    $this->layout->setModule('main');
  }

  /**
   * Index page.
   */
  public function ajax_test() {
    $this->layout->setLayout('ajax');
    $this->layout->setModule('main');
    $this->layout->view('ajax_test');
  }

  /**
   * Index page.
   */
  public function index() {
    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => '/'));
    $this->setHeaders($page);

    $team = ManagerHolder::get('Team')->getAll('*', 5);
    $this->layout->set('team', $team);
    $this->layout->view('index');
  }

  /**
   * m_cabinet page.
   */
  public function m_cabinet() {
    $this->layout->setLayout('markup_layout');
    $this->layout->setmodule('markups');
    $this->layout->view('cabinet');
  }

  /**
   * Find.
   */
  public function find() {
//    if (!empty($_GET['text'])) {
//      $title = 'Поиск по запросу ' . $_GET['text'];
//    } else {
//      $title = 'Поиск по сайту';
//    }

//    $headers = array('title' => $title, 'description' => $title);
//    $this->layout->set('header', $headers);


    $this->layout->setLayout('main');
    $this->layout->view('find');

  }

}