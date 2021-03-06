<?php
/**
 * AuthInfoManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class AuthInfoManager extends BaseManager {

  /** Fields. */
  public $fields = array("email" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "email_confirmed" => array("type" => "checkbox"),
                         "activation_key" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "password" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "phone" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "phone_confirmed" => array("type" => "checkbox"),
                         "banned" => array("type" => "checkbox"),
                         "banned_reason" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "last_ip" => array("type" => "input", "attrs" => array("maxlength" => 50)),
                         "last_login" => array("type" => "datetime"),
                         "facebook_id" => array("type" => "input", "attrs" => array("maxlength" => 30)),
                         "vkontakte_id" => array("type" => "input", "attrs" => array("maxlength" => 30)),
                         "gmail_id" => array("type" => "input", "attrs" => array("maxlength" => 140)),
                         "mailru_id" => array("type" => "input", "attrs" => array("maxlength" => 30)));

  /** List params. */
  public $listParams = array("email", "email_confirmed", "activation_key", "password", "phone", "phone_confirmed", "banned", "banned_reason", "last_ip", "last_login", "facebook_id", "vkontakte_id", "gmail_id", "mailru_id");

}