<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$loader = require_once BASEPATH . 'vendor/autoload.php';

/**
 * RetailCrmApi library
 * Itirra - http://itirra.com
 */
class RetailCrmApi {

  /** @var RetailCrm\ApiClient */
  protected $client = NULL;

  /** Shop Url */
  protected $url = 'https://shop-mammyclub.retailcrm.ru';

  /** Api key */
  protected $apiKey = 'mqvSXCjguiRcCI6nPWNKYUe9yk6Z6keU';

  protected $apiVersion = 'v5';

  /**
   * Constructor.
   */
  public function __construct() {
    $this->client['v5'] = new RetailCrm\ApiClient($this->url, $this->apiKey, \RetailCrm\ApiClient::V5);
    $this->client['v4'] = new RetailCrm\ApiClient($this->url, $this->apiKey, \RetailCrm\ApiClient::V4);
    $this->client['v3'] = new RetailCrm\ApiClient($this->url, $this->apiKey, \RetailCrm\ApiClient::V3);
  }

  /**
   * @param $version
   */
  public function setApiVersion($version) {
    $this->apiVersion = $version;
  }

  /**
   * @param string $version
   * @return mixed
   */
  public function getClient($version = 'v5') {
    return $this->client[$version];
  }

  /***
   * @param $name
   * @param $arguments
   * @return bool|mixed
   */
  public function __call($name, $arguments) {
    $response = FALSE;
    try {
      $response = call_user_func_array(array($this->client[$this->apiVersion], $name), $arguments);
    } catch (RetailCrm\Exception\CurlException $e) {
      log_message("error", "RetailCrm Connection error: " . $e->getMessage());
    }

    return $response;
  }

}