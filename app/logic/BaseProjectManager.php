<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * BaseProjectManager
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class BaseProjectManager extends BaseManager {

  /**
   *  $updateCount @var integer
   *  IMPORTANT!!! This property is implemented only for cases
   *  once this method will be executed on postUpdate to avoid infinite loop
   */
  private static $updateCount = 0;

  /**
   * Process Templates
   * @param int $entityId
   * @param string $what
   * @param bool $ignoreCountCheck
   */
  public function processTemplates($entityId, $what = "e.*", $ignoreCountCheck = FALSE) {
    if (self::$updateCount > 0 && $ignoreCountCheck == FALSE) {
      return;
    }

    $entity = $this->getById($entityId, $what);
    if (!empty($entity)) {

      $templates = ManagerHolder::get('Template')->getAllWhere(array('entity_name' => $this->entityName), 'e.*');
      if (!empty($templates)) {

        $CI =& get_instance();
        $CI->load->config('templates');
        $entityFieldMap = $CI->config->item('templates');
        $keyMap = $entityFieldMap[$this->entityName]['keymap'];

        $updateData = array();

        foreach ($templates as $t) {

          $tempEntity = $entity;

          // Create replace array
          $replaceArr = array();
          if (!empty($keyMap)) {
            foreach ($keyMap as $k => $v) {

              // Process count dependant fields
              if (strpos($k, '{count}') !== FALSE) {
                $key = str_replace('{count}', '', $k);
                if (strpos($t['value'], '{' . $key) !== FALSE) {
                  $count = null;
                  if (preg_match("/\{" . $key . "(\d+)\}/", $t['value'], $matches)) {
                    $count = $matches[1];
                  }
                  $k = $key . $count;
                  $tempEntity[$key] = array_slice($tempEntity[$key], 0, $count);
                }
              }

              // Process relations
              if (strpos($v, '.') !== FALSE) {
                $vArr = explode('.', $v);
                if (isset($tempEntity[$vArr[0]][$vArr[1]])) {
                  // one to many
                  $replaceArr[$k] = $tempEntity[$vArr[0]][$vArr[1]];
                } elseif (isset($tempEntity[$vArr[0]][0])) {
                  // many to many
                  $replaceArr[$k] = implode(', ', get_array_vals_by_second_key($tempEntity[$vArr[0]], $vArr[1]));
                }
              } else {
                $replaceArr[$k] = $tempEntity[$v];
              }
              if (empty($replaceArr[$k])) {
                $replaceArr[$k] = "";
              }
            }
          }

          // Fix white spaces
          $t['value'] = fix_white_spaces(trim(kprintf($t['value'], $replaceArr)));

          if (strpos($t['field'], '.') !== FALSE) {
            $fieldArr = explode('.', $t['field']);
            $updateData[$fieldArr[0]][$fieldArr[1]] = $t['value'];
          } else {
            $updateData[$t['field']] = $t['value'];
          }

        }
      }

      if (!empty($updateData)) {
        self::$updateCount++;
        $updateData['id'] = $entity['id'];
        $this->update($updateData, FALSE);
      }
    }
  }

}