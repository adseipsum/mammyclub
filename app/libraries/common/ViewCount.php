<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ViewCount library
 * To count "views" of an entity (page)
 * Itirra - http://itirra.com
 * @author Alexei Chizhmakov (Itirra - www.itirra.com) 
 */
class ViewCount {

  /** Key for data storage. */
  const DATA_KEY = "VIEW_COUNT_DATA";

  /**
   * Constructor.
   * @return ViewCount
   */
  public function ViewCount () {
    $CI =& get_instance();
    $CI->load->helper('cookie');
  }
  
  /**
   * Counted to check whether view has been counted for a entity/page
   * 
   * @param $key - prefix like page name or entity name
   * @param $id - id of page or entity
   * @return bool
   */
  public function counted($key, $id) {
    $result = TRUE;
    $key = 'view_count_' . $key . '_' . $id;

    $cookieData = get_cookie($key);
    if ($cookieData == FALSE) {
      set_cookie($key, '1', 60*60*24*30*24); // 2 years
      $result = FALSE;
    }
    return $result;
  }
  
}
?>