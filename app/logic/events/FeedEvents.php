<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

require_once APPPATH . 'logic/events/base/BaseEvent.php';


class FeedEvents extends BaseEvent
{

  protected $logging = TRUE;

  /**
   * @return array
   */
  public function remarketingFeedExport() {

    $socialNetworkTypes = array('facebook', 'google');

    $where = array('published' => TRUE, 'not_in_stock' => FALSE);
    $entities = ManagerHolder::get('Product')->getAllWhere($where, 'e.*, brand.*, category.*, image.*');

    log_message('debug', '[remarketingFeedExport] - entities count: ' . count($entities));

    foreach ($socialNetworkTypes as $networkType) {
      $CI = &get_instance();
      $CI->load->library('RetailCrmApi');

      $feedsDirPath = './web/product_feeds';

      if (!is_dir($feedsDirPath)) {
        @mkdir($feedsDirPath, $mode = 0777);
      }

      if (empty($entities)) {
        return array('is_success' => FALSE, 'result' => 'Products not found');
      }

      $CI->load->library('common/csv');

      if ($networkType === 'google') {
        $CI->csv->setSeparator('|');
      }

      // 2. Get fields. Достаем филды по типу "facebook" для всех файлов
      $fields = ManagerHolder::get('Product')->getFeeedExportFieldsMap('facebook');
      $CI->csv->addHeader(array_keys($fields));

      $rows = ManagerHolder::get('Product')->processFeedExportRow($entities, $fields, 'facebook');

      log_message('debug', '[remarketingFeedExport] - '. $networkType . ' rows count: ' . count($rows));

      $CI->csv->addRows($rows);

      if ($networkType === 'google') {
        $CI->csv->saveFile($feedsDirPath . '/google_product_feed_export.csv', FALSE);
      } else {
        $CI->csv->saveFile($feedsDirPath . '/facebook_product_feed_export.csv', FALSE);
      }
      $CI->csv->clean();

    }

    return array('is_success' => TRUE, 'result' => count($entities));
  }


  /**
   * php delete function that deals with directories recursively
   */
  function delete_files($target)
  {
    if (is_dir($target)) {
      $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned
      foreach ($files as $file) {
        delete_files($file);
      }
      @rmdir($target);
    } elseif (is_file($target)) {
      unlink($target);
    }
  }
}