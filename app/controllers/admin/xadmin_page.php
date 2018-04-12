<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_Page
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_Page extends Base_Admin_Controller {

  /** AdditionalItemActions. */
  protected $additionalItemActions = array("view");

  /** SearchParams. */
  protected $searchParams = array("name");

  /** ImportExcludeFields. */
  protected $importExcludeFields = array("page_url");
  
  /** Is delete all action allowed */
  protected $isDeleteAllAllowed = FALSE;

	/**
   * Pre process params.
   * @return string
   */
  protected function preProcessParams($addParams = null) {
    return parent::preProcessParams('can_be_deleted');
  }
  
  /**
   * SetAddEditDataAndShowView.
   * Set all needed view data and show add_edit form.
   * @param object $entity
   */
  protected function setAddEditDataAndShowView($entity) {
    if ($entity['id']) {
      $settings = ManagerHolder::get('Settings')->getAllWhere(array("page_id" => $entity['id']), 'e.*');
      if (!empty($settings)) {
        $newFieldsStart = array_slice($this->fields, 0, count($this->fields) - 2);
        $newFields = array();
        $newFieldsEnd = array_slice($this->fields, count($this->fields) - 2);
        foreach ($settings as $s) {
          $newFields['ext_setting_' . $s['k']] = array("type" => $s['type'], "label" => $s['name'], "message" => "", "value" => $s['v']);
        }
        $this->fields = $newFieldsStart + $newFields + $newFieldsEnd;
      }
    }
    if (!$entity['can_be_deleted']) {
      $this->fields['name']['attrs']['disabled'] = 'disabled';
    }
    parent::setAddEditDataAndShowView($entity);
  }
  
  /**
   * PreProcessPost.
   * htmlspecialchars all fields except TinyMCE
   * remove all empty fields.
   */
  protected function preProcessPost() {
    foreach ($_POST as $k => $v) {
      if (strpos($k, 'ext_setting_') === 0) {
        ManagerHolder::get('Settings')->updateWhere(array("k" => str_replace('ext_setting_', "", $k)), "v", $v);
        unset($_POST[$k]);
      }
    }
    foreach ($_FILES as $k => $v) {
      if (strpos($k, 'ext_setting_') === 0) {
        $this->fileoperations->set_base_dir('./web/images');
        $folder = $this->session->userdata(self::FOLDER_SESSION_KEY);
        if ($folder) {
          $this->fileoperations->add_folder_to_uploads_dir($folder);
        }
        try {
          if ($this->fileoperations->upload($k, FALSE)) {
            $imgUrl = site_image_url($this->fileoperations->file_info);
            ManagerHolder::get('Settings')->updateWhere(array("k" => str_replace('ext_setting_', "", $k)), "v", $imgUrl);
          }
        } catch (Exception $e) {
          $message = $e->getMessage();
          set_flash_error($message);
          log_message('error', $e->getMessage());
          $this->redirectToReffer();
        }
        
        unset($_FILES[$k]);
      }
    }
    parent::preProcessPost();
  }
  

}