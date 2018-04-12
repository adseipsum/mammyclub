<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * Xadmin controller.
 * @author Itirra - http://itirra.com
 */
class xAdmin_Admin extends Base_Admin_Controller {

  /** Extra entitites. */
  protected $extraEntities = array();


  /**
   * Add/edit entity.
   * @param integer $entityId
   * @return void
   */
  public function add_edit($entityId = null) {
    if ($entityId) {
      unset($this->fields["password"]);
    }
    $menuItems = array_merge($this->menuItems, $this->extraEntities);
    $this->layout->set("menuItems", $menuItems);
    $this->layout->set("permissionsProcessLink", $this->adminBaseRoute . '/' . strtolower($this->entityName) . '/permissions_process');
    parent::add_edit($entityId);
  }

  /**
   * Add/edit process.
   * @return void
   */
  public function add_edit_process() {
    if (!isset($_POST['id'])) {
      $data = $_POST;
      $data['login_url'] = site_url($this->adminBaseRoute . "/login");
      ManagerHolder::get("Email")->sendView($data['email'], 'admin_new_administrator', $data);
      $_POST["password"] = md5($_POST["password"]);
    }
     
    $permissions = array();
    foreach ($_POST as $k => $v) {
      if (strpos($k, 'perm_') === 0) {
        $permissions[] = str_replace('perm_', '', $k);
      }
    }
    $_POST['permissions'] = implode('|', $permissions);
    parent::add_edit_process();
  }
  
  /**
   * Implementation of POST_UPDATE event callback
   * @param Object $entity reference
   * @return Object
   */
  protected function postUpdate(&$entity) {
    if ($entity['id'] == $this->loggedInAdmin['id']) {
      $admin = ManagerHolder::get($this->entityName)->getById($entity['id'], 'e.*');
      $this->session->set_userdata(self::LOGGED_IN_ADMIN_SESSION_KEY, $admin);
    }
  }


  /**
   * Delete.
   * @param integer $entityId
   * @return void
   */
  public function delete($entityId) {
    if ($entityId == $this->loggedInAdmin['id']) {
      set_flash_warning("admin.messages.admin.cannot_delete_current");
      $this->redirectToReffer();
    }
    parent::delete($entityId);
  }

  /**
   * Delete all.
   * @return void
   */
  public function delete_all() {
    if (isset($_POST["d_id"])) {
      foreach ($_POST["d_id"] as $id) {
        if ($id == $this->loggedInAdmin['id']) {
          set_flash_warning("admin.messages.admin.cannot_delete_current");
          $this->redirectToReffer();
        }
      }
    }
    parent::delete_all();
  }

  /**
   * Change theme.
   */
  public function change_theme($theme) {
    ManagerHolder::get($this->managerName)->updateById($this->loggedInAdmin['id'], 'theme', $theme);
    $admin = ManagerHolder::get($this->managerName)->getById($this->loggedInAdmin['id']);
    $this->session->set_userdata(self::LOGGED_IN_ADMIN_SESSION_KEY, $admin);
    set_flash_notice("admin.messages.info_successfully_changed");
    redirect($this->adminBaseRoute . "/change_info");
  }
}