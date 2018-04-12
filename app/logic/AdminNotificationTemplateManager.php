<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * AdminNotificationTemplateManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class AdminNotificationTemplateManager extends BaseManager {

  /** Fields. */
  public $fields = array("name" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "k" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "subject" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "email_main_text" => array("type" => "tinymce", "class" => "required", "attrs" => array("maxlength" => 65536)),
                         "admins" => array("type" => "multipleselect_chosen", "relation" => array("entity_name" => "Admin", "search" => TRUE)),
                         "additional_emails" => array("type" => "input", "attrs" => array("maxlength" => 255)));

  /** List params. */
  public $listParams = array("name");

}