<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * SaleManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/events/base/BaseEvent.php';

class BaseEvent {

  protected $logging = FALSE;

  /**
   * @return boolean
   */
  public function isLogging() {
    return $this->logging;
  }



}