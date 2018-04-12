<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * Xadmin controller.
 * @author Alexei Chizhmakov (Itirra - http://itirra.com)
 */
class Base_Admin_Lang_Controller extends Base_Admin_Controller {

  /**
   * Constructor.
   */
  public function Base_Admin_Lang_Controller() {
    parent::Base_Admin_Controller();
  }

  /**
   * (non-PHPdoc)
   * @see Base_Admin_Controller::init()
   */
  protected function init() {
    parent::init();
    // Config
    $this->load->config('lang_config');
    $defLang = array_search(config_item('language'), config_item('languages'));
    ManagerHolder::setLanguage($defLang);
  }

  /**
   * Index.
   */
  public function index($page = "page1") {
    parent::index($page);
  }

  /**
   * CreateEntityPOST.
   * Prepares POST.
   * Creates Entity From Post.
   * Validates Entity.
   *
   * @return Object
   */
  protected function createEntityPOST() {
    $this->preProcessPost();
    $langs = config_item('languages');
    $langRelTrans = array();
    foreach ($langs as $k => $l) {
      $lang = array('language' => $k);
      $langRel = array('language' => $k);
      $langRelAlias = "";
      foreach (ManagerHolder::get($this->managerName)->i18nFields as $f) {
        $key = $f;
        if (strstr($key, '.') !== FALSE) {
          $arr = explode('.', $key);
          $key = str_replace('.', '_', $key);
          $langRel[$arr[1]] = $_POST[$k . '_' . $key];
          $langRelAlias = $arr[0];
        } else {
          if(isset($_POST[$k . '_' . $key])) {
            $lang[$f] = $_POST[$k . '_' . $key];
          }
        }
        unset($_POST[$k . '_' . $key]);
      }
      $entity[ManagerHolder::get($this->managerName)->translationTableAlias][] = $lang;
      if (!empty($langRelAlias)) {
        $langRelTrans[$langRelAlias]['translations'][] = $langRel;
      }
    }
    $entity = array_merge($entity, $_POST);

    $entity = ManagerHolder::get($this->managerName)->createEntityFromArray($entity);

    if (!empty($langRelTrans)) {
      $rels = ManagerHolder::get($this->managerName)->getRelations();
      foreach ($langRelTrans as $alias => $transes) {
        if (isset($entity[$alias]['id']) && !empty($entity[$alias]['id'])) {
          $ent = $entity[$alias];
          $ent->synchronizeWithArray($transes);
          ManagerHolder::get($rels[$alias])->update($ent);
        } else {
          $ent = ManagerHolder::get($rels[$alias])->createEntityFromArray($transes);
          $ent['id'] = ManagerHolder::get($rels[$alias])->insert($ent);
          $entity->$alias = $ent;
        }
      }
    }

    $this->isValid($entity);
    return $entity;
  }

  /**
   * CreateEntityId.
   * Creates Entity By Id;
   * @param integer $entityId
   * @return Object
   */
  protected function createEntityId($entityId = null) {
    ManagerHolder::setLanguage(null);
    $langs = config_item('languages');
    $entity = new $this->managerName;
    $entity = $entity->toArray();
    if ($entityId) {
      $params = "id,translations.*,";
      foreach ($this->fields as $k => $v) {
        if($v['type'] == 'map') {
          // adding $k_left_px & $k_top_px fields
          $params .= ($k == 'map' ? '' : $k . '_') . "left_px,";
          $params .= ($k == 'map' ? '' : $k . '_') . "top_px,";
        } elseif($v['type'] == 'geo') {
          $params .= ($k == 'geo' ? '' : $k . '_') . "latitude,";
          $params .= ($k == 'geo' ? '' : $k . '_') . "longitude,";
          $params .= ($k == 'geo' ? '' : $k . '_') . "address,";
        } else {
          if(strpos($k, '.') !== FALSE || !in_array($k, ManagerHolder::get($this->managerName)->i18nFields)) {
            $params .= $k . ",";
          }
        }
      }
      if (in_array('can_be_deleted', array_keys($entity))) {
        $params .= "can_be_deleted";
      }
      $params = rtrim($params, ',');
      $entity = ManagerHolder::get($this->managerName)->getById($entityId, $params);
      if (empty($entity)) {
        redirect($this->adminBaseRoute . '/' .  strtolower($this->entityUrlName));
      }
    } else {
      foreach ($langs as $k => $l) {
        $lang = array('language' => $k);
        foreach (ManagerHolder::get($this->managerName)->i18nFields as $f) {
          $lang[$f] = "";
        }
        $entity[ManagerHolder::get($this->managerName)->translationTableAlias][] = $lang;
      }
    }
    $this->layout->set("languages", array_keys($langs));
    $this->layout->set("i18nFields", ManagerHolder::get($this->managerName)->i18nFields);
    $this->load->config('lang_config');
    $defLang = array_search(config_item('language'), config_item('languages'));
    ManagerHolder::setLanguage($defLang);
    return $entity;
  }

  /**
   * Pre process params.
   * @return string
   */
  protected function preProcessParams($addParams = null) {
    $params = ManagerHolder::get($this->managerName)->getPk();
    if (is_array($params)) {
      $params = implode(', ', $params);
    }
    $params .= ', ';
    foreach ($this->listParams as $param) {
      $append = '';
      if (is_array($param)) {
        $param = array_make_plain_with_dots($param);
        $ks = array_keys($param);
        $vs = array_values($param);
        if (strpos($vs[0], ',') > 0) {
          $vsArr = explode(',', $vs[0]);
          foreach ($vsArr as $vsI => $vsVal) {
            $append .= $ks[0] . '.*';
            if ($vsI < count($vsArr) -1) {
              $append .= ', ';
            }
          }
        } else {
          $append = $ks[0] . '.*';
        }
      } else {
        if (strpos($param, '.') !== FALSE) {
          $pArr = explode('.', $param);
          $param = $pArr[0] . '.*';
        }
        $append = $param;
      }
      $params .= $append . ', ';
    }
    if ($addParams) {
      $params .= $addParams;
    }
    $params = rtrim($params, ', ');
    return $params;
  }

}