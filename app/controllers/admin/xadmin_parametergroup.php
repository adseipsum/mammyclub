<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_ParameterGroup
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_ParameterGroup extends Base_Admin_Controller {

  /**
   * DeleteImageFromDb.
   * Delete image from db and hard drive.
   * @param integer $imageId
   * @return Image (the deleted image)
   */
  protected function deleteImageFromDb($imageId) {
    $image = ManagerHolder::get("Image")->getById($imageId, 'e.*');
    ManagerHolder::get("Image")->deleteById($imageId);
    // Check for cache
    if(isset(ManagerHolder::get($this->managerName)->CACHE_GROUP_KEY)) {
      $cacheGroupkey = ManagerHolder::get($this->managerName)->CACHE_GROUP_KEY;
      if (!empty($cacheGroupkey)) {
        $this->load->library("common/cache");
        $this->cache->remove_group($cacheGroupkey);
      }
    }
    return $image;
  }

}