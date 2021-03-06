<?php
/**
 * AdminManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class AdminManager extends BaseManager {

  public $fields = array("name" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "email" => array("type" => "input", "class" => "required email", "attrs" => array("maxlength" => 255)),
  											 "password" => array("type" => "input", "class" => "required passwordGen", "attrs" => array("maxlength" => 255)),
  											 "allowed_ips" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "email_notice" => array("type" => "checkbox"),
                         "external_crm_id" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "permissions" => array("type" => "admin_permissions"));

  public $listParams = array("name", "email");


  /**
   * Get admin id and password_changed for login.
   * @param string $login
   * @param string $pass
   * @return array
   */
  public function getAdminByLoginPass($login, $pass) {
    $where["email"] = $login;
    $where["password"] = md5($pass);
    return $this->getOneWhere($where, 'e.*');
  }

  /**
   * Get admin count id and password_changed for login.
   * @param string $login
   * @param string $pass
   * @return integer
   */
  public function getAdminByLoginPassCount($login, $pass) {
    $where["email"] = $login;
    $where["password"] = md5($pass);
    return $this->getCountWhere($where);
  }

  /**
   * GetAdminByEmail.
   * @param string $email
   * @return mixed
   */
  public function getAdminByEmail($email) {
    $where["email"] = $email;
    return $this->getOneWhere($where);
  }

}