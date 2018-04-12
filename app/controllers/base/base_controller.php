<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'logic/common/ManagerHolder.php';

/**
 * Base_Controller.
 * @author Itirra - http://itirra.com
 *
 * @property CI_Loader $load
 * @property Layout $layout
 */
class Base_Controller extends Controller {

  /** Configs to load. */
  protected $configs = array();

	/** Default configs to load. */
  protected $defaultConfigs = array('app_constants', 'scripts');

  /** Helpers to load.*/
  protected $helpers = array();

	/** Default helpers to load. */
  protected $defaultHelpers = array('common/itirra_commons');

  /** Libraries to load.*/
  protected $libraries = array();

	/** Default libraries to load. */
  protected $defaultLibraries = array('common/Layout');

  /** Is ajax request. */
  protected $is_ajax_request = false;

  /**
   * Constructor.
   */
  public function Base_Controller() {
    parent::Controller();

    $shopUrl = rtrim(preg_replace("(https?://)", "$0shop.", $this->config->config['base_url']), '/');
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      header('Access-Control-Allow-Origin: ' . $shopUrl);
      header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
      header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
      header('Access-Control-Allow-Credentials: true');
      die();
    } else {
      header('Access-Control-Allow-Origin: ' . $shopUrl);
      header('Access-Control-Allow-Credentials: true');
    }
    header('Content-Type: text/html; charset=UTF-8');

    $this->is_ajax_request = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));

    $this->loadConfigs();
    $this->loadLanguage();
    $this->loadHelpers();
    $this->loadLibraries();

    // Push GET to view
    $get = array();
    if (!empty($_GET)) {
      foreach ($_GET as $k => $v) {
        if (is_array($v)) {
          $get[$k] = $v;
        } else {
          $get[$k] = urldecode($v);
        }
      }
    }
    $this->layout->set('get', $get);

    $this->layout->set('isAjaxRequest', $this->is_ajax_request);
  }

  /**
   * Load configs.
   */
  protected function loadConfigs() {
    $cnfgs = array_merge($this->defaultConfigs, $this->configs);
    foreach ($cnfgs as $c) {
      $this->load->config($c);
    }
  }

  /**
   * Load language.
   */
  protected function loadLanguage() {
    $this->lang->load('message_properties', $this->config->item('language'));
  }

  /**
   * Load libraries.
   */
  protected function loadLibraries() {
    $libraries = $this->libraries;
    if (array_keys($libraries) !== range(0, count($libraries) - 1)) {
      $libraries = array_keys($this->libraries);
    }
    $libs = array_merge($this->defaultLibraries, $libraries);
    foreach ($libs as $l) {
      $options = null;
      if (isset($this->libraries[$l]) && is_array($this->libraries[$l])) {
        $options = $this->libraries[$l];
      }
      $this->load->library($l, $options);
    }
  }

  /**
   * Load helpers.
   */
  protected function loadHelpers() {
    $hlprs = array_merge($this->defaultHelpers, $this->helpers);
    foreach ($hlprs as $h) {
      $this->load->helper($h);
    }
  }

  /**
   * Add configs.
   * @param array $array
   * @param bool $after
   */
  protected function addConfigs($array, $after = TRUE) {
  	if ($after) {
  		$this->configs = array_merge($this->configs, $array);
  	} else {
  		$this->configs = array_merge($array, $this->configs);
  	}
  }

	/**
   * Add libraries.
   * @param array $array
   * @param bool $after
   */
  protected function addLibraries($array, $after = TRUE) {
    if ($after) {
      $this->libraries = array_merge($this->libraries, $array);
    } else {
      $this->libraries = array_merge($array, $this->libraries);
    }
  }

  /**
   * Add helpers.
   * @param array $array
   * @param bool $after
   */
  protected function addHelpers($array, $after = TRUE) {
    if ($after) {
      $this->helpers = array_merge($this->helpers, $array);
    } else {
      $this->helpers = array_merge($array, $this->helpers);
    }
  }

}