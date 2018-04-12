<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";


/**
 * Dev controller.
 * !!! For developers only
 * @author Itirra - http://itirra.com
 */
class Dev_Controller extends Base_Project_Controller
{

  /**
   * Constructor.
   */
  public function Dev_Controller()
  {
    parent::Base_Project_Controller();
    if (!url_contains('64E29112-06E9-11E2-9CB7-5A826188709B')) show_404();
  }

  /**
   *  Generate two remarketing feed files for Google and Facebook
   */
  public function remarketing_feed_export()
  {
    Events::trigger('Feed.remarketingFeedExport');
  }


  /**
   * unsubscribe_user_current_pregnancyweek_via_csv_process
   */
  public function unsubscribe_user_current_pregnancyweek_via_csv_process()
  {

    // Load CSV Library
    $this->load->library('common/csv');

    $this->csv->setInputFileEncoding('utf-8');

    $this->csv->setSeparator("\r");
    // Read CSV File
//		$this->csv->readFile('./web/unsubscribe_user_current_pregnancy_week.csv');
    $this->csv->readFile('./web/unsubscribe_user_newsletter.csv');

    $emails = array();

    while (($row = $this->csv->readRow()) !== FALSE) {
      if (!in_array(';', $row)) {
        foreach ($row as $r) {
          $emails[] = trim($r);
        }
      }
    }
    trace(count($emails));

    $users = ManagerHolder::get('User')->getAllWhere(array('auth_info.email' => $emails), 'e.id');
    trace(count($users));

    $userIds = get_array_vals_by_second_key($users, 'id');
//		$nativeQ = 'UPDATE user SET pregnancyweek_current_id = null WHERE id IN (' . implode(',', $userIds) . ')';

    // unsubscribe newsletter from users
    $nativeQ = 'UPDATE user SET newsletter = NULL WHERE id IN (' . implode(',', $userIds) . ')';
//		ManagerHolder::get('User')->executeNativeSQL($nativeQ);

  }


  /**
   * create_default_store_inventories
   */
  public function create_default_store_inventories()
  {
    $products = ManagerHolder::get('Product')->getAll('bar_code, parameter_groups.*');
    if (!empty($products)) {
      foreach ($products as $p) {
        if (!empty($p['parameter_groups'])) {
          foreach ($p['parameter_groups'] as $pg) {
            ManagerHolder::get('StoreInventory')->createDefault($pg['id'], 'ParameterGroup');
          }
        } else {
          ManagerHolder::get('StoreInventory')->createDefault($p['id'], 'Product');
        }
      }
    }
    traced('Finished');
  }

  /**
   * unsubscribe_recipients_via_csv_from_broadcasts_process
   */
  public function unsubscribe_recipients_via_csv_from_broadcasts_process()
  {

    // Load CSV Library
    $this->load->library('common/csv');

    $this->csv->setInputFileEncoding('utf-8');

    $this->csv->setSeparator("\r");

    // Read CSV File
    $this->csv->readFile('./web/unsubscribe.csv');

    $emails = array();

    while (($row = $this->csv->readRow()) !== FALSE) {
      foreach ($row as $r) {
        $emails[] = $r;
      }
    }

    array_shift($emails);

    $users = ManagerHolder::get('User')->getAllWhere(array('auth_info.email' => $emails), 'e.id');

    $userData = array('newsletter' => 0,
      'newsletter_first_year' => 0,
      'newsletter_recommended_products' => 0,
      'newsletter_useful_tips' => 0,
      'newsletter_shop' => 0,
      'pregnancyweek_id' => 'null',
      'pregnancyweek_current_id' => 'null',
      'pregnancyweek_current_started' => 'null',
      'age_of_child' => 'null',
      'age_of_child_current_started' => 'null',
      'child_birth_date' => 'null',
      'child_sex' => 'null',
      'child_name' => 'null');

    $userIds = get_array_vals_by_second_key($users, 'id');

    $setClause = '';
    foreach ($userData as $k => $v) {
      if (!empty($setClause)) {
        $setClause .= ', ';
      }
      $setClause .= $k . ' = ' . $v;
    }

    $nativeQ = 'UPDATE user SET %s WHERE id IN (' . implode(',', $userIds) . ')';
    $nativeQ = sprintf($nativeQ, $setClause);
    ManagerHolder::get('User')->executeNativeSQL($nativeQ);
  }


  /**
   * process_users_broadcast_settings
   */
  public function process_users_broadcast_settings()
  {
    $errorsCount = 0;
    $users = ManagerHolder::get('User')->getAll('e.*');
    foreach ($users as $u) {
      try {
        ManagerHolder::get('User')->updateBroadcastSettings($u);
      } catch (Exception $e) {
        $errorsCount++;
      }
    }
    die('done! users: ' . count($users) . '; errors: ' . $errorsCount);
  }

  /**
   * process_default_users_broadcast_channels
   */
  public function process_default_users_broadcast_channels()
  {
    ManagerHolder::get('User')
      ->executeNativeSQL("UPDATE user SET broadcast_channels = '" . ManagerHolder::get('User')->getDefaultBroadcastChannels() . "'");
  }

  /**
   * fix_redirects_form_csv
   */
  public function fix_redirects_form_csv()
  {

    set_time_limit(0);

    $this->load->library('common/csv');

    $this->csv->setSeparator(',');
    $this->csv->setInputFileEncoding('utf-8');

    $this->csv->readFile('./web/mammyclub_redirect_fixes.csv');

    $shopBaseUrl = 'https://shop.mammyclub.com/';
    $fields = array('old_url', 'new_url');

    $processedCount = 0;
    $redirectsInsertedCount = 0;
    $successRows = array();
    $errorRows = array();

    while (($row = $this->csv->readRow()) !== FALSE) {

      if (count($fields) != count($row)) {
        $errorRows[] = $row;
        continue;
      }

      $processedCount++;

      $row = array_combine($fields, $row);
      $row['old_url'] = $shopBaseUrl . trim($row['old_url'], '/');
      $row['new_url'] = $shopBaseUrl . trim($row['new_url'], '/');

      $oldUrlResult = $this->sendRequest($row['old_url'], 0, TRUE);
      $newUrlResult = $this->sendRequest($row['new_url'], 0, TRUE);

      $row['old_url_code'] = $oldUrlResult['http_code'];
      $row['new_url_code'] = $newUrlResult['http_code'];

      if ($oldUrlResult['http_code'] == 404 && $newUrlResult['http_code'] == 200) {
        $successRows[] = $row;
      } else {
        $errorRows[] = $row;
      }
    }

    if (count($successRows) > 0) {
      foreach ($successRows as $k => $row) {
        $redirectData = array('old_url' => surround_with_slashes(str_replace($shopBaseUrl, '', $row['old_url'])),
          'new_url' => surround_with_slashes(str_replace($shopBaseUrl, '', $row['new_url'])));
        // Check if we already have a redirect from current old url or eternal cycle
        $checkWhereArray = array(
          array('old_url' => $redirectData['old_url']),
          array('old_url' => $redirectData['new_url'],
            'new_url' => $redirectData['old_url'])
        );
        foreach ($checkWhereArray as $where) {
          $redirectUrl = ManagerHolder::get('RedirectUrl')->getOneWhere($where, 'e.*');
          if (!empty($redirectUrl)) {
            ManagerHolder::get('RedirectUrl')->deleteById($redirectUrl['id']);
          }
        }
        ManagerHolder::get('RedirectUrl')->insert($redirectData);
        $redirectsInsertedCount++;
      }
    }

    trace('processedCount: ' . $processedCount);

    trace('successRows:');
    trace($successRows);

    trace('redirectsInsertedCount: ' . $redirectsInsertedCount);

    trace('errorRows:');
    trace($errorRows);

    die('finished');
  }

  /**
   * sendRequest by CURL
   */
  private function sendRequest($url, $timeout = 0, $nobody = FALSE)
  {
    $result = array('response' => null, 'http_code' => null);
    $curlHandle = curl_init();
    curl_setopt($curlHandle, CURLOPT_URL, $url);
    $options = array(CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_ENCODING => "UTF-8",
      CURLOPT_FAILONERROR => FALSE,
      CURLOPT_FOLLOWLOCATION => FALSE,
      CURLOPT_CONNECTTIMEOUT => $timeout,
      CURLOPT_TIMEOUT => $timeout,
      CURLOPT_SSL_VERIFYPEER => FALSE);

    if ($nobody) {
      curl_setopt($curlHandle, CURLOPT_HEADER, true);
      curl_setopt($curlHandle, CURLOPT_NOBODY, true);  // we don't need body
    }

    curl_setopt_array($curlHandle, $options);
    $result['response'] = curl_exec($curlHandle);
    $result['http_code'] = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
    curl_close($curlHandle);
    return $result;
  }

  /**
   * optimize_images
   */
  public function optimize_images()
  {
    set_time_limit(0);
    define('LAST_OPTIMIZED_FILE_PATH_STORAGE', './web/last_optimized_image.txt');

    log_trace('Started');
    if (!file_exists(LAST_OPTIMIZED_FILE_PATH_STORAGE)) {
      file_put_contents(LAST_OPTIMIZED_FILE_PATH_STORAGE, '');
    }
    if (!is_writable(LAST_OPTIMIZED_FILE_PATH_STORAGE)) {
      log_traced('LAST_OPTIMIZED_FILE_PATH_STORAGE is not writable');
    }

    $lastProcessedFilePath = file_get_contents(LAST_OPTIMIZED_FILE_PATH_STORAGE);
    if (!empty($lastProcessedFilePath)) {
      log_trace('Skip every image before: ' . $lastProcessedFilePath);
    }

    $this->process_dir_recurcive($_SERVER['DOCUMENT_ROOT'] . '/web/images/uploads', $lastProcessedFilePath);
    log_traced('Finished! Processed images: ' . ImageOptimizerWrapper::$processedCount);
  }

  /**
   * process_dir_recurcive
   */
  private function process_dir_recurcive($dir, &$lastProcessedFilePath)
  {
    log_trace('Processing directory: ' . $dir);
    $items = glob($dir . '/*');
    if (!empty($items)) {
      if (!is_dir($items[0])) {
        log_trace('Files count: ' . count($items));
      }
      foreach ($items as $i) {
        if (is_dir($i)) {
          $this->process_dir_recurcive($i, $lastProcessedFilePath);
        } else {

          // Check if we need to process this file
          $needProcessing = FALSE;
          if (empty($lastProcessedFilePath)) {
            $needProcessing = TRUE;
          } elseif ($lastProcessedFilePath == $i) {
            $lastProcessedFilePath = '';
            log_trace('$lastProcessedFilePath was cleared');
          }
          if (!$needProcessing) {
            continue;
          }

          try {
            ImageOptimizerWrapper::optimize($i);
            file_put_contents(LAST_OPTIMIZED_FILE_PATH_STORAGE, $i);
          } catch (Exception $e) {
            log_trace('Exception: ' . $e->getMessage());
          }

        }
      }
    }
    log_trace('Finished directory: ' . $dir);
  }

  /**
   * process_product_thumbs
   */
  public function process_product_thumbs()
  {
    // Get product images
    $nativeQ = 'SELECT * FROM resource WHERE web_path IN ("uploads/product/") AND width <> height';
    $productImages = ManagerHolder::get('Image')->executeNativeSQL($nativeQ);
    // Get param groups images
    $nativeQ = 'SELECT * FROM resource WHERE web_path IN ("uploads/parameter_group/")';
    $paramGroupImages = ManagerHolder::get('Image')->executeNativeSQL($nativeQ);
    if (!empty($paramGroupImages)) {
      foreach ($paramGroupImages as $pi) {
        $productImages[] = $pi;
      }
    }
    trace(count($productImages));

    if (empty($productImages)) {
      die('No images found');
    }


    $this->load->library('common/Fileoperations');
    $thumbMaxSize = 60;

    $processed = 0;
    $updated = 0;
    foreach ($productImages as $img) {
      $processed++;
      $filePath = $img['file_path'] . $img['file_name'];
      if (!file_exists($filePath)) {
        continue;
      }

      trace('[process_product_thumbs] - processing image id: ' . $img['id'] . '; filePath: ' . $filePath);

      // Check if thumb not exists
      $thumbFilePath = str_replace($img['extension'], '_small_one' . $img['extension'], $filePath);
      if (file_exists($thumbFilePath)) {
        $this->fileoperations->get_file_info($thumbFilePath);
      }
      if (!file_exists($thumbFilePath) || isset($this->fileoperations->file_info['width']) && $this->fileoperations->file_info['width'] != $thumbMaxSize) {
        $this->fileoperations->createSmartCropThumb($img, '_small_one', 60, 60);
        $updated++;
      }

    }

    trace('[process_product_thumbs] - $processed: ' . $processed . '; $updated: ' . $updated);
    traced($productImages);
  }

  /**
   * send_apologise_emails
   */
  public function send_apologise_emails()
  {

    $fyMandrillBroadcasts = ManagerHolder::get('MandrillBroadcast')->getAllWhere(array('type' => 'first_year_broadcast', 'created_at >' => date('Y-m-d') . ' 15:40:01'), 'id, subject');
    trace($fyMandrillBroadcasts);

    $fyMandrillBroadcastsIds = get_array_vals_by_second_key($fyMandrillBroadcasts, 'id');

    $recipients = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere(array('broadcast_id' => $fyMandrillBroadcastsIds), 'e.*');

    $userIds = array_unique(get_array_vals_by_second_key($recipients, 'user_id'));
    $wrongUsers = ManagerHolder::get('User')->getAllWhere(array('id' => $userIds), 'id, name, auth_info.*');
//     $normRec = array();
//     foreach ($recipients as $x => $r) {
//     	foreach ($wrongUsers as $xx => $u) {
//     		if ($r['email'] == $u['auth_info']['email'] && $r['user_id'] == $u['id']) {
//     			$r['user'] = $u;
//     			$normRec[] = $r;
//     			unset($recipients[$x]);
//     			unset($wrongUsers[$xx]);
//     		}
//     	}
//     }
//     traced($normRec);

    $wrongEmails = get_array_vals_by_second_key($wrongUsers, 'auth_info', 'email');
    trace('$wrongEmails');
    trace(count($wrongEmails));


    $emails = array_unique(get_array_vals_by_second_key($recipients, 'email'));
    trace('$emails');
    trace(count($emails));

    $intersect = array();
    foreach ($emails as $k => $e) {
      foreach ($wrongEmails as $kk => $we) {
        if ($e == $we) {
          $intersect[] = $e;
          unset($emails[$k]);
          unset($wrongEmails[$kk]);
        }
      }
    }
    trace('$intersect'); // 1957
    trace($intersect);

    trace('$emails');
    trace(array_values($emails)); // 1209

    trace('$wrongEmails');
    trace(array_values($wrongEmails)); // 1209

//     trace('array_intersect');
//     $intersect = array_intersect($emails, $wrongEmails);
//     trace($intersect);

//     trace('array_diff');
//     $diff = array_diff($wrongEmails, $emails);
//     trace($diff);


    $users = ManagerHolder::get('User')->getAllWhere(array('auth_info.email' => $emails), 'id, name, auth_info.*');

//     $users = ManagerHolder::get('User')->getAllWhere(array('name' => array('alexeii.boyko', 'oleg.poda', 'ovpoda')), 'id, name, auth_info.*');

    $count = 0;
    foreach ($users as $u) {
      $entity = array('email' => $u['auth_info']['email'],
        'name' => $u['name']);
      trace($entity);
      $subject = 'Извините за доставленные неудобства! Удалите, пожалуйста, предыдущее письмо.';
      ManagerHolder::get('EmailMandrill')->sendTemplate($u['auth_info']['email'], 'apologise', $u, $subject);
      $count++;
    }

    traced($count);
  }

  /**
   * test_cli
   */
  public function test_cli()
  {
    $msg = '[test_cli] - script was executed via ';
    $msg .= is_cli() ? 'cli' : 'non cli';
    $msg .= ' interface';
    $msg .= '. User: ' . shell_exec('whoami');
    log_message('error', $msg);
    traced($msg);
  }

  /**
   * process_products_on_order_status
   */
  public function process_products_on_order_status()
  {
    $managerNames = array('Product', 'ParameterGroup');
    foreach ($managerNames as $managerName) {
      $entities = ManagerHolder::get($managerName)->getAll('in_stock');
      if (!empty($entities)) {
        foreach ($entities as $p) {
          $onOrder = FALSE;
          if ($p['in_stock'] == FALSE) {
            $onOrder = TRUE;
          }
//          if ($p['on_order'] != $onOrder) {
//            ManagerHolder::get($managerName)->updateById($p['id'], 'on_order', $onOrder);
//          }
        }
      }
      trace($entities);
    }
    traced('finished');
  }

  /**
   * test_excel
   */
  public function test_excel()
  {
    require_once('./lib/phpExcel/PHPExcel.php');
    require_once('./lib/phpExcel/PHPExcel/Writer/Excel5.php');

    $borderStyle = array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM,
      'color' => array('rgb' => '000000')));

    $fontStyle = array('name' => 'Times New Roman', 'size' => 11);
    $fontBoldStyle = array_merge($fontStyle, array('bold' => true));

    $xls = new PHPExcel();
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('Відвантаження');

    $sheet->getColumnDimension("B")->setWidth(20);
    $sheet->getColumnDimension("D")->setWidth(30);
    $sheet->getColumnDimension("E")->setWidth(13);

    $sheet->setCellValue("A1", 'Заявка на відвантаження товару');
    $sheet->getStyle('A1')->getFont()->applyFromArray($fontBoldStyle);
    $sheet->mergeCells('A1:F1');
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue("B2", '№ заявки:');
    $sheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle('B2')->getFont()->applyFromArray($fontBoldStyle);

    $sheet->setCellValue("C2", '123');
    $sheet->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C2')->getFont()->applyFromArray($fontStyle);
    $sheet->getStyle('C2')->getBorders()->applyFromArray($borderStyle);

    $sheet->setCellValue("D2", 'Дата заявки:');
    $sheet->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle('D2')->getFont()->applyFromArray($fontBoldStyle);

    $sheet->setCellValue("E2", date('Y-m-d'));
    $sheet->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E2')->getFont()->applyFromArray($fontStyle);
    $sheet->getStyle('E2')->getBorders()->applyFromArray($borderStyle);

    // Order table
    $orderData = array('Замовник' => 'ФОП Пода',
      'Перевізник' => 'Новая Почта',
      'Місто доставки' => 'значение поля Город из заказа',
      'Адреса отримувача' => 'значение поля Номер склада или полей Улица, Номер дома, Номер квартиры из заказа  (в зависимости от того куда доставка на склад или домой)',
      'ПІБ отримувача' => 'значение поля ФИО из заказа',
      'Телефон отримувача' => 'значение поля Телефон из заказа',
      'Платник' => 'ФОП Пода',
      'Форма розрахунку' => 'Безготивкова',
      'Оголошена вартість вантажу' => 'значение поля Итого из заказа',
      'Внутрішній № замовлення' => 'Наш номер заказа',
      'Сума післяплати товар' => 'Если способ оплаты cash - значение поля Итого из заказа, если другой способ оплаты - 0',
      'Інші додаткові послуги' => 'нет',
      'ІПН Відправника' => '2845605576');
    $rowNum = 4;
    foreach ($orderData as $k => $v) {
      $sheet->mergeCellsByColumnAndRow(0, $rowNum, 1, $rowNum);
      $sheet->setCellValueByColumnAndRow(0, $rowNum, $k);
      $sheet->mergeCellsByColumnAndRow(2, $rowNum, 5, $rowNum);
      $sheet->setCellValueByColumnAndRow(2, $rowNum, $v);
      for ($i = 2; $i <= 5; $i++) {
        $sheet->getStyleByColumnAndRow($i, $rowNum)->getBorders()->applyFromArray($borderStyle);
      }
      $sheet->getStyleByColumnAndRow(2, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $rowNum++;
    }
    $rowNum++;

    // Product table
    $sheet->getRowDimension($rowNum)->setRowHeight('34,5');

    $productDataFields = array(0 => 'index', 1 => 'code', 2 => 'name', 4 => 'weight', 5 => 'qty');
    $productData = array(array('index' => 1, 'code' => '12345', 'name' => 'Товар 1', 'qty' => 1),
      array('index' => 2, 'code' => '32423', 'name' => 'Товар 2', 'qty' => 5));

    // Product table header
    $sheet->mergeCellsByColumnAndRow(2, $rowNum, 3, $rowNum);
    for ($i = 0; $i <= 5; $i++) {
      $sheet->getStyleByColumnAndRow($i, $rowNum)->getBorders()->applyFromArray($borderStyle);
      $sheet->getStyleByColumnAndRow($i, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      if (isset($productDataFields[$i])) {
        $sheet->setCellValueByColumnAndRow($i, $rowNum, lang('excel.shipment.product_table.header.' . $productDataFields[$i]));
      }
    }

    // Product table products
    $rowNum++;
    foreach ($productData as $p) {
      $sheet->mergeCellsByColumnAndRow(2, $rowNum, 3, $rowNum);
      foreach ($p as $k => $v) {
        for ($i = 0; $i <= 5; $i++) {
          $sheet->getStyleByColumnAndRow($i, $rowNum)->getBorders()->applyFromArray($borderStyle);
          $sheet->getStyleByColumnAndRow($i, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          if ($i == array_search($k, $productDataFields)) {
            $sheet->setCellValueByColumnAndRow($i, $rowNum, $v);
          }
        }
      }
      $rowNum++;
    }

    // Send HTTP-headers
    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=matrix.xls");

    // Throw file to output
    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');
  }

  /**
   * process_siteorder_statuses
   */
  public function process_siteorder_statuses()
  {

    trace('[process_siteorder_statuses] - Started');

    $this->lang->load('enum', $this->config->item('language'));

    $processed = 0;
    $updated = 0;
    $statusPool = array();

    $siteorders = ManagerHolder::get('SiteOrder')->getAllWhere(array('status <>' => ''), 'e.*');
    if (!empty($siteorders)) {
      foreach ($siteorders as $s) {
        $processed++;
        if (empty($s['siteorder_status_id'])) {
          if (empty($statusPool[$s['status']])) {
            $status = ManagerHolder::get('SiteOrderStatus')->getOneWhere(array('k' => $s['status']), 'e.*');
            if (empty($status)) {
              $status = array('name' => lang('enum.siteorder.status.' . $s['status']),
                'k' => $s['status']);
              trace($status);
              $status['id'] = ManagerHolder::get('SiteOrderStatus')->insert($status);
            }
            $statusPool[$s['status']] = $status;
          }
          ManagerHolder::get('SiteOrder')->updateById($s['id'], 'siteorder_status_id', $statusPool[$s['status']]['id']);
          $updated++;
        }
      }
    }

    traced('[process_siteorder_statuses] - Finished! Processed: ' . $processed . '; Updated: ' . $updated);
  }

  /**
   * process_barcodes
   */
  public function process_barcodes()
  {
    $managers = array('Product', 'ParameterGroup');
    foreach ($managers as $managerName) {
      trace('Processing: ' . $managerName);
      $updated = 0;
      $entities = ManagerHolder::get($managerName)->getAllWhere(array('bar_code <>' => ''), 'id, bar_code');
      foreach ($entities as $e) {
        $bcFixed = string_clean_up($e['bar_code']);
        if (strlen($e['bar_code']) != strlen($bcFixed)) {
          trace($e['bar_code']);
          trace($bcFixed);
          trace('------------------------------------------------');
//           ManagerHolder::get($managerName)->updateById($e['id'], 'bar_code', $bcFixed);
          $updated++;
        }
      }
      trace('Finished. Updated: ' . $updated);
    }
    die('finished');
  }

  /**
   * mbr_error_test
   */
  public function mbr_error_test()
  {

    $a = 's:1346:"a:28:{s:2:"id";s:4:"9334";s:4:"name";s:14:"ma-rin-ka_2011";s:10:"newsletter";b:0;s:15:"newsletter_shop";b:1;s:20:"newsletter_questions";b:1;s:19:"newsletter_comments";b:1;s:31:"newsletter_recommended_products";b:0;s:21:"newsletter_first_year";b:1;s:22:"newsletter_useful_tips";b:1;s:21:"buys_without_discount";b:0;s:18:"email_confirm_date";s:19:"2016-01-18 23:56:51";s:9:"name_full";N;s:5:"phone";N;s:7:"country";s:2:"RU";s:16:"pregnancyweek_id";N;s:24:"pregnancyweek_current_id";N;s:29:"pregnancyweek_current_started";N;s:9:"login_key";s:32:"9063f4e21bb9d850e0a584c00d806687";s:6:"status";s:4:"user";s:12:"age_of_child";s:1:"1";s:28:"age_of_child_current_started";s:10:"2016-05-02";s:16:"child_birth_date";N;s:9:"child_sex";N;s:10:"child_name";N;s:8:"image_id";s:3:"230";s:12:"auth_info_id";s:5:"11109";s:4:"DESC";s:4:"9334";s:9:"auth_info";a:17:{s:2:"id";s:5:"11109";s:5:"email";s:22:"ma-rin-ka_2011@mail.ru";s:15:"email_confirmed";b:1;s:14:"activation_key";s:0:"";s:8:"password";s:32:"81783f3afac92c543e6f95beda57d3dc";s:5:"phone";N;s:15:"phone_confirmed";b:0;s:6:"banned";b:0;s:13:"banned_reason";N;s:7:"last_ip";s:13:"95.213.218.36";s:10:"last_login";s:19:"2016-04-18 12:31:39";s:11:"facebook_id";N;s:12:"vkontakte_id";N;s:8:"gmail_id";N;s:9:"mailru_id";N;s:10:"created_at";s:19:"2016-01-18 23:55:25";s:4:"DESC";s:19:"2016-01-18 23:55:25";}}";';
    trace($a);
    $a = unserialize($a);
    trace($a);
    $a = unserialize($a);
    trace($a);


    $users = ManagerHolder::get('User')->getAllWhere(array('auth_info.email' => 'ma-rin-ka_2011@mail.ru'), 'e.*, auth_info.*');
    $user = $users[0];

    $user['MandrillBroadcastRecipient'] = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere(array('user_id' => $user['id']), 'e.*');

//     // Insert recipient data
//     $userData = array('email' => $user['auth_info']['email'],
//                       'user_id' => $user['id'],
//                       'is_read' => 0,
//                       'is_send' => 0,
//                       'data' => serialize($user),
//                       'broadcast_id' => 1000,
//                       'updated_at' => date(DOCTRINE_DATE_FORMAT));
//     $recipientId = ManagerHolder::get('MandrillBroadcastRecipient')->insert($userData);

    trace($user);
  }

  /**
   * process_mbr_html_rells
   * @param string $protection_code
   */
  public function process_mbr_html_rells()
  {

    trace('[process_mbr_html_rells] - started');
    log_message('error', '[process_mbr_html_rells] - started');

//     $recipients = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere(array('html<>' => ''), 'id, html, email_html.*', 40000);
    $nativeQ = 'SELECT r1.id, r1.html, r2.html AS email_html
                FROM mandrill_broadcast_recipient AS r1
                LEFT JOIN mandrill_broadcast_recipient_html AS r2 ON (r2.recipient_id = r1.id)
                WHERE r1.html IS NOT NULL AND r1.html <> ""
                LIMIT 20000';

    $recipients = ManagerHolder::get('MandrillBroadcastRecipient')->executeNativeSQL($nativeQ);

    trace('[process_mbr_html_rells] - found $recipients: ' . count($recipients));
    log_message('error', '[process_mbr_html_rells] - found $recipients: ' . count($recipients));

    if (empty($recipients)) {
      die();
    }

    foreach ($recipients as $k => $r) {
      if (empty($r['email_html'])) {
        $htmlData = array('recipient_id' => $r['id'],
          'html' => $r['html']);
        ManagerHolder::get('MandrillBroadcastRecipientHtml')->insert($htmlData);
      }
      ManagerHolder::get('MandrillBroadcastRecipient')->updateById($r['id'], 'html', '');
      unset($recipients[$k]);
    }

    log_message('error', '[process_mbr_html_rells] - finished');
    traced('[process_mbr_html_rells] - finished');
  }

  /**
   * process_ips
   * @param $type
   */
  public function process_ips()
  {
    trace('[process_ips] - Started');

    $users = ManagerHolder::get('User')->getAll('e.*, auth_info.*');
    if (empty($users)) {
      traced('[process_ips] - No users found - Finished');
    }

    $this->load->library('Maxmind');
    $this->maxmind->setOptions(array('98359', 'TwUSChKug9Xx'));

    $processed = 0;
    $detected = 0;
    $failed = 0;
    foreach ($users as $u) {
      $country = 'UA';
      if (!empty($u['auth_info']['last_ip']) && $u['auth_info']['last_ip'] != '127.0.0.1') {
        try {
          $country = $this->maxmind->detect($u['auth_info']['last_ip'], 'country');
          $detected++;
        } catch (Exception $e) {
          log_message('error', '[maxmind] - failed with message: ' . $e->getMessage());
          $failed++;
        }
      }
      ManagerHolder::get('User')->updateById($u['id'], 'country', $country);
      $processed++;
    }

    traced('[process_ips] - Finished! INFO: $processed: ' . $processed . '; $detected: ' . $detected . '; $failed: ' . $failed);
  }

  /**
   * make_thumbs
   * @param $type
   */
  public function regenerate_thumbs($entityName, $relationType = 'one')
  {
    set_time_limit(0);
    $this->load->library('common/Fileoperations');
    $this->load->config('thumbs');
    if ($relationType == 'many') {
      $entities = ManagerHolder::get($entityName)->getAll('id, images.*');
      $this->make_thumbs_for_entities($entities, $entityName, 'images', NULL, TRUE);
    } else {
      $entities = ManagerHolder::get($entityName)->getAll('id, image.*');
      $this->make_thumbs_for_entities($entities, $entityName);
    }
    echo "done for $entityName, count = " . count($entities);
  }


  /**
   * make_thumbs_for_entities
   * @param $entities
   * @param $entityName
   */
  private function make_thumbs_for_entities($entities, $entityName, $alias = 'image', $fieldName = 'image_id', $isMultipleImagesField = false, $imageRelAlias = 'image')
  {
    set_time_limit(0);
    foreach ($entities as $e) {
      if (!empty($e[$alias])) {
        if ($isMultipleImagesField) {
          foreach ($e[$alias] as $imageRel) {
            $this->make_thumbs_for_image($imageRel[$imageRelAlias], $entityName, $fieldName);
          }
        } else {
          $this->make_thumbs_for_image($e[$alias], $entityName, $fieldName);
        }
      }
    }
  }


  /**
   * make_thumbs_for_image
   * @param $image
   * @param $thumbs
   */
  private function make_thumbs_for_image($image, $entityName, $fieldName)
  {
    $thumbs = $this->config->item(strtolower($entityName), 'thumbs');
    $thumbs['_admin'] = $this->config->item('_admin', 'all');
    foreach ($thumbs as $name => $sizes) {
      if (isset($sizes['smart_crop']) && $sizes['smart_crop']) {
        $this->fileoperations->createSmartCropThumb($image, $name, $sizes["width"], $sizes["height"]);
      } else {
        $this->fileoperations->createImageThumb($image, $name, $sizes["width"], $sizes["height"]);
      }
    }
  }


  /**
   * get content
   */
  public function get_mandrill_content()
  {

    trace('[get_mandrill_content] - STARTED');

    ManagerHolder::get('EmailMandrill')->getContent('857cc52d08c74cc48dc11d5bf6d3c683');

  }

  /**
   * process_unsubscribed_users
   */
  public function process_unsubscribed_users()
  {

    $processed = 0;
    $updated = 0;

    $users = ManagerHolder::get('User')->getAll('e.*');

    trace('[process_unsubscribed_users] - Users to process: ' . count($users));

    foreach ($users as $u) {

      $processed++;

      $data = array();
      if ($u['newsletter_first_year'] == FALSE) {
        $data['age_of_child'] = null;
        $data['age_of_child_current_started'] = null;
        $data['child_birth_date'] = null;
        $data['child_sex'] = null;
        $data['child_name'] = null;
      }
      if ($u['newsletter'] == FALSE) {
        $data['pregnancyweek_id'] = null;
        $data['pregnancyweek_current_id'] = null;
        $data['pregnancyweek_current_started'] = null;
        ManagerHolder::get('UserPregnancyWeek')->deleteAllWhere(array('user_id' => $u['id']));
      }
      if (!empty($data)) {
        $data['id'] = $u['id'];
        ManagerHolder::get('User')->update($data);
        $updated++;
      }
    }

    traced('[process_unsubscribed_users] - Processed: ' . $processed . '; Updated: ' . $updated);
  }


  /**
   * process_shop_texts
   */
  public function process_shop_texts()
  {

    trace('[process_shop_texts] - started');

    $managerNames = array('Article', 'ArticleComment', 'Question', 'QuestionComment', 'PregnancyArticle', 'ProductComment');

    foreach ($managerNames as $managerName) {

      trace('PROCESSING: ' . $managerName);

      $processed = 0;
      $updated = 0;

      $entities = ManagerHolder::get($managerName)->getAll('e.*');

      foreach ($entities as $a) {

        $processed++;

        preg_match_all("'<a.*?href=\"(http[s]*://[^>\"]*?)\"[^>]*?>(.*?)</a>'si", $a['content'], $matches);
        if (isset($matches[1]) && !empty($matches[1])) {

          $processItemText = 'ID: ' . $a['id'];
          if (isset($a['name'])) {
            $processItemText = 'Name: ' . $a['name'];
          }

          $needUpdate = FALSE;
          $tempInfoArr = array();

          foreach ($matches[1] as $link) {
            $decodedLink = urldecode($link);
            if (strpos($decodedLink, '/магазин') !== FALSE) {
              $newLink = shop_url(str_replace(site_url('/магазин'), '', $decodedLink));
              $newLinkEncoded = $this->russianUrlEncode(str_replace(site_url('/магазин'), '', $decodedLink));

              $tempInfoArr[] = $link . '<br/>' . $decodedLink . '<br/>' . $newLink . '<br/>' . $newLinkEncoded;

              $needUpdate = TRUE;
              $a['content'] = str_replace($link, $newLinkEncoded, $a['content']);
            }
          }

          if ($needUpdate == TRUE) {
            trace($processItemText);
            foreach ($tempInfoArr as $ti) {
              trace($ti);
            }
            trace('------------------------------------------------------------------------------------');
            ManagerHolder::get($managerName)->updateById($a['id'], 'content', $a['content']);
            $updated++;
          }


        }

      }

      trace('INFO - Total: ' . count($entities) . '; Processed: ' . $processed . '; Updated: ' . $updated);
      trace('=========================================================================================');
    }


    traced('[process_shop_texts] - finished!');
  }

  /**
   * russianUrlEncode
   * @param string $decodedLink
   */
  private function russianUrlEncode($decodedLink)
  {
    $decodedUrlArr = explode('/', $decodedLink);
    foreach ($decodedUrlArr as &$segment) {
      $segment = urlencode($segment);
    }
    $decodedLink = implode('/', $decodedUrlArr);
    return shop_url($decodedLink);
  }

  /**
   * process_product_empty_headers
   */
  public function process_product_empty_headers()
  {
    trace('[process_product_empty_headers] - Started');
    $products = ManagerHolder::get('Product')->getAllWhere(array('header_id' => NULL), 'e.*');
    if (!empty($products)) {
      foreach ($products as $p) {
        $header = array('title' => $p['name']);
        $hId = ManagerHolder::get('Header')->insert($header);
        ManagerHolder::get('Product')->updateById($p['id'], 'header_id', $hId);
      }
    }
    die('[process_product_empty_headers] - FINISHED');
  }

  public function process_product_brand_page_url()
  {
    trace('[process_product_brand_page_url] - Started');
    $brands = ManagerHolder::get('ProductBrand')->getAllWhere(array('page_url' => '/'), 'e.name');
    trace('[process_product_brand_page_url] - Brands to process: ' . count($brands));
    if (!empty($brands)) {
      foreach ($brands as $b) {
        $newPageUrl = lang_url($b['name'], null, TRUE);
        ManagerHolder::get('ProductBrand')->updateById($b['id'], 'page_url', $newPageUrl);
      }
    }
    traced('[process_product_brand_page_url] - FINISHED');
  }

  /**
   * Get ICML catalog
   */
  public function get_icml_catalog()
  {
    require_once(APPPATH . '/libraries/MoySkladICMLParser.php');
    // configure
    $parser = new MoySkladICMLParser(
      'admin@mammyclub',
      '4806e7475f',
      'shop-mammyclub',
      array(
        'directory' => 'web/icml_file/',
        'file' => 'catalog.xml',
      )
    );

    // generate
    $parser->generateICML();
  }

  /**
   * Get ICML catalog
   */
  public function generate_icml_catalog()
  {
    $this->load->library('RetailCrmApi');
    $response = $this->retailcrmapi->ordersGet('152C', 'id');
    traced($response);
    die();

    $shop = 'MammyClub';
    $date = new DateTime();
    $xmlstr = '<yml_catalog date="' . $date->format('Y-m-d H:i:s') . '"><shop><name>' . $shop . '</name></shop></yml_catalog>';
    $xml = new SimpleXMLElement($xmlstr);
    $categoriesXml = $xml->shop->addChild('categories', '');

    $categories = ManagerHolder::get('ProductCategory')->getWhere(array(), 'e.*');
    $categories = $this->processCategoryLoop($categories);

    $this->addCategoriesToIcml($categoriesXml, $categories);
    $offersXml = $xml->shop->addChild('offers', '');

    $products = ManagerHolder::get('Product')->getAll('e.*, parameter_groups.*, brand.*');
    $productsCatalog = array();
    traced($products);
    foreach ($products as $k => $product) {
      if (empty($product['parameter_groups'])) {
        $productsCatalog[] = $product;
      } else {
        foreach ($product['parameter_groups'] as $group) {
          $p = $product;
          $p['bar_code'] = $group['bar_code'];
          if (!empty($group['price'])) {
            $p['price'] = $group['price'];
          }
          $p['not_in_stock'] = $group['not_in_stock'];
          $p['offer_name'] = $p['name'] . ' ' . $group['main_parameter_value']['name'];
          $productsCatalog[] = $p;
        }
      }
      unset($products[$k]);
    }

    foreach ($productsCatalog as $product) {
      $productXml = $offersXml->addChild('offer', '');

      $productXml->addAttribute('productId', 'p' . $product['id']);
      $productXml->addAttribute('id', $product['bar_code']);

      $productXml->addChild('categoryId', $product['category_id']);
      $productXml->addChild('price', $product['price']);
      if (isset($product['offer_name']) && !empty($product['offer_name'])) {
        $productXml->addChild('name', htmlspecialchars($product['offer_name']));
      } else {
        $productXml->addChild('name', htmlspecialchars($product['name']));
      }

      $productXml->addChild('productName', htmlspecialchars($product['name']));
      $productXml->addChild('vendor', htmlspecialchars($product['brand']['name']));

      $unitXml = $productXml->addChild('unit', '');
      $unitXml->addAttribute('code', 'pcs');
      $unitXml->addAttribute('name', 'Штука');
      $unitXml->addAttribute('sym', 'шт.');

      $articleXml = $productXml->addChild('param', $product['product_code']);
      $articleXml->addAttribute('name', 'Артикул');
      $articleXml->addAttribute('code', 'article');
    }

    file_put_contents('web/icml_file/our_catalog.xml', $xml->asXML());
  }

  /**
   * processCategoryLoop
   * @param array $categories
   */
  private function processCategoryLoop($categories)
  {
    if (!empty($categories)) {
      foreach ($categories as $k => $v) {
        if (!$v['published']) {
          unset($categories[$k]);
          continue;
        }
        if (!empty($v['__children'])) {
          $categories[$k]['__children'] = $this->processCategoryLoop($v['__children']);
        }
      }
    }
    return $categories;
  }

  private function addCategoriesToIcml($categoriesXml, $categories)
  {
    foreach ($categories as $category) {
      $categoryXml = $categoriesXml->addChild('category', $category['name']);
      $categoryXml->addAttribute('id', $category['id']);
      if ($category['level'] > 0) {
        $categoryXml->addAttribute('parentId', $category['root_id']);
      }

      if (!empty($category['__children'])) {
        $this->addCategoriesToIcml($categoriesXml, $category['__children']);
      }
    }

  }

}