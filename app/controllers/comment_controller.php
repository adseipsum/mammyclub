<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Comment controller.
 * @author Itirra - http://itirra.com
 */
class Comment_Controller extends Base_Project_Controller {

  /** Auth config. */
  protected $authConfig;

  /**
   * Constructor.
   */
  public function Comment_Controller() {
    parent::Base_Project_Controller();
  }


  public function add_comment() {

    $this->load->helper('common/itirra_validation');

    $fieldsToValidate = array('comment', 'entity_type', 'entity_id', 'nb');
    switch ($_POST['entity_type']) {
      case 'Shop':
        if (!$this->isLoggedIn) {
          $fieldsToValidate[] = 'email';
        }
        unset($fieldsToValidate[array_search('entity_id', $fieldsToValidate)]);
        break;
      case 'Product':
        if (!$this->isLoggedIn) {
          $fieldsToValidate[] = 'email';
        }
        break;
    }
    foreach ($fieldsToValidate as $f) {
      if (!isset($_POST[$f]) || empty($_POST[$f])) {
        show_404();
      }
    }
    if(!in_array($_POST['entity_type'], array('Question', 'Article', 'Product', 'Shop'))) {
      show_404();
    }

    if(!$this->isLoggedIn && in_array($_POST['entity_type'], array('Product', 'Shop'))) {

      $this->authConfig = $this->config->item('auth');

      $user = ManagerHolder::get('User')->getOneWhere(array('auth_info.email' => $_POST['email']), 'e.*, auth_info.*');
      if(!empty($user)) {
        save_post();
        set_flash_error('Пользователь с email-ом ' . $_POST['email'] . ' уже есть в базе. Пожалуйста залогиньтесь перед тем как оставить свой отзыв.');
        redirect_to_referral();
      } else {
        try {
          $this->auth->register($_POST);
        } catch (ValidationException $e) {
          save_post(array('password', 'password_confirmation'));
          set_flash_validation_errors($e->getErrors());
          redirect_to_referral();
        } catch (UserExistsException $e) {
          save_post(array('password', 'password_confirmation'));
          set_flash_error('auth.error.user_exists');
          redirect_to_referral();
        } catch (Exception $e) {
          set_flash_error($e->getMessage());
          redirect_to_referral();
        }
        $this->auth->refresh();
        $this->authEntity = $this->auth->getAuthEntity();
      }
    }

    $entityComment = array();
    $entityComment['content'] = $_POST['comment'];
    $this->imageFromTinyMceProcess($entityComment['content']);
    if (!empty($_POST['parent_id'])) {
      $entityComment['parent_id'] = $_POST['parent_id'];
    }
    $entityComment['user_id'] = $this->authEntity['id'];
    $entityComment['date'] = date('Y-m-d H:i:s');
    if ($_POST['entity_type'] == 'Product' && isset($_POST['rating']) && !empty($_POST['rating'])) {
      $entityComment['rating'] = $_POST['rating'];
    }
    if ($_POST['entity_type'] != 'Shop') {
      $entityComment['entity_id'] = $_POST['entity_id'];
    }

    try {
      $cId = ManagerHolder::get($_POST['entity_type'] . 'Comment')->insert($entityComment);
      ManagerHolder::get('EmailNotice')->sendNewCommentNoticeToAdmins($cId, $_POST['entity_type']);
      if ($_POST['entity_type'] == 'Shop') {
        $redirectUrl = shop_url('/') . '#comments';
      } else {
        $entity = ManagerHolder::get($_POST['entity_type'])->getById($entityComment['entity_id'], 'e.*');
        if (empty($entity)) {
          throw new Exception('$entity with $entityComment["entity_id"] ' . $entityComment['entity_id'] . ' not found');
        }
        ManagerHolder::get('EmailNotice')->sendNewAnswerToQuestionNoticeToUser($_POST['entity_type'], $entity['id'], $cId, $this->authEntity);
        $redirectUrl = $entity['page_url'] . '#comments';
        if($_POST['entity_type'] == 'Product') {
          $redirectUrl = shop_url($entity['page_url'] . '#comments');
        }
      }
      set_flash_notice('Комментарий успешно добавлен!');
    } catch (Exception $e) {
      log_message('error', '[Comment_Controller->add_comment]' . $e->getMessage());
      set_flash_error('Произошла ошибка. Обратитесь к администрации сайта.');
      redirect_to_referral();
    }
    redirect($redirectUrl);
  }

  /**
   * Upload image AJAX
   * From TinyMCE
   */
  public function upload_image_ajax() {
    $resultTemplate = '<script language="javascript" type="text/javascript">window.parent.window.jbImagesDialog.uploadFinish({result: "{result}", resultCode: "{resultCode}", filename:"{file_name}"});</script>';
    $this->load->library('common/Fileoperations');
    $this->fileoperations->set_base_dir('./web/images');
    $this->fileoperations->set_upload_lib_config_value("allowed_types", 'jpg|gif|png|jpeg');

    // Upload Image
    $image = array();
    try {
      if ($this->fileoperations->upload('userfile', TRUE)) {
        $image = $this->fileoperations->file_info;
      }
    } catch (Exception $e) {
      die('Ошибка при загрузке изображения. ' . $e->getMessage());
    }
    if (empty($image)) {
      die('Ошибка при загрузке изображения');
    }

    if ($image['width'] > 500) {
      $this->config->load('thumbs');
      $thumbs = $this->config->item('comment', 'thumbs');
      if ($thumbs) {
        foreach ($thumbs as $name => $sizes) {
          $this->fileoperations->createSmartCropThumb($image, $name, $sizes["width"], $sizes["height"]);
        }
      }
    }

    $result = str_replace(array('{result}', '{resultCode}', '{file_name}'),
                          array("Изображение успешно загружено", "Ok", site_image_thumb_url('_medium', $image)),
                          $resultTemplate);

    die($result);
  }

}