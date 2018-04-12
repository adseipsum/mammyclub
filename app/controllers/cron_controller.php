<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Cron controller.
 * @author Itirra - http://itirra.com
 */
class Cron_Controller extends Base_Project_Controller {

  /* Security code. Ensures that nobody runs this controller, but cron */
  const PROTECTION_CODE = '30ad3a3a1d2c7c63102e09e6fe4bb253';

  /** $checkedValidLinks */
  protected $checkedValidLinks = array();

  /**
   * Constructor.
   */
  public function Cron_Controller() {
    parent::Base_Project_Controller();
    if(!url_contains(self::PROTECTION_CODE) ) show_404();
    set_time_limit(0);
  }

  /**
   * content_links_checker
   */
  public function content_links_checker() {
    log_message('debug', '[content_links_checker] - Started');
    $result = array();
    $managerNames = array('PregnancyWeek', 'FirstYearBroadcast', 'UsefulTipsBroadcast', 'RecommendedProductsBroadcast', 'TyBroadcast', 'Article');
    foreach ($managerNames as $managerName) {
      log_message('debug', '[content_links_checker] - Processing manager: ' . $managerName);
      $entities = ManagerHolder::get($managerName)->getAll('e.*');
      if (!empty($entities)) {
        foreach ($entities as $e) {
          $links = $this->parseContentGetBrokenLinks($e);
          if (!empty($links)) {
            log_message('debug', '[content_links_checker] - found ' . count($links) . ' links for entity_id ' . $e['id']);
            $result[$managerName][$e['id']] = array('name' => $e['name'], 'links' => $links);
          }
        }
      }
    }

    if (!empty($result)) {
      $fields = array('broadcast_type', 'broadcast_name', 'anchor', 'url');
      $fTrans = array();
      foreach ($fields as $f) {
        $fTrans[] = lang('content_links_checker_report.header.' . $f);
      }
      $this->load->library('common/csv');
      $this->csv->addHeader($fTrans);

      $rows = array();

      foreach ($result as $managerName => $entities) {
        foreach ($entities as $e) {
          foreach ($e['links'] as $link) {
            $rows[] = array('broadcast_type' => lang('content_links_checker_report.row.broadcast_type.' . $managerName),
                            'broadcast_name' => $e['name'],
                            'anchor'         => $link['anchor'],
                            'url'            => $link['url']);
            }
        }
      }

      $this->csv->addRows($rows);

      $fileName = 'content_links_checker_report_' . date('Y-m-d') . '.csv';
      $this->csv->saveFile('./web/' . $fileName);

      $subject = 'Отчет по проверки ссылок в рассылках за прошлые сутки';
      $message = 'Отчет во вложении письма';
      $to = 'mariya.shatokhina@mammyclub.com, oleg.poda@mammyclub.com, valera@builderclub.com, sasha.poda@thelauncher.pro';
      ManagerHolder::get('Email')->addAttachment('./web/' . $fileName);
      ManagerHolder::get('Email')->send($to, $subject, $message);
      unlink('./web/' . $fileName);
    }

    log_message('debug', '[content_links_checker] - Finished');
    traced($result);
  }

  /**
   * parseContentGetBrokenLinks
   * @param array $entity
   */
  private function parseContentGetBrokenLinks($entity) {
    $result = array();
    $fieldsToCheck = array('email_intro', 'email_main_text', 'email_outro', 'email_short_text', 'content');
    foreach ($fieldsToCheck as $f) {
      if (isset($entity[$f]) && !empty($entity[$f])) {
        preg_match_all("'<a.*?href=\"(http[s]*://[^>\"]*?)\"[^>]*?>(.*?)</a>'si", $entity[$f], $matches);
        if (isset($matches[1][0]) && !empty($matches[1][0])) {
          foreach ($matches[1] as $i => $url) {
            if (in_array($url, $this->checkedValidLinks)) {
              continue;
            }
            if (check_url_for_404($url)) {
              $res = array('url' => $url, 'anchor' => '');
              if (isset($matches[2][$i]) && !empty($matches[2][$i])) {
                $res['anchor'] = $matches[2][$i];
              }
              $result[] = $res;
            } else {
              $this->checkedValidLinks[] = $url;
            }
          }
        }
      }
    }
    return $result;
  }


  /**
   * php delete function that deals with directories recursively
   */
  private function delete_files($target) {
    if(is_dir($target)) {
      $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned
      foreach($files as $file) {
        $this->delete_files( $file );
      }
      @rmdir($target);
    } elseif(is_file($target)) {
      unlink($target);
    }
  }

  /**
   * siteorder_report
   */
  public function siteorder_report($protection_code) {

    if ($protection_code != self::PROTECTION_CODE) {
      show_404();
    }

    log_message('debug', '[Cron_Controller -> siteorder_report] - Started');

    // Process to emails
    $to = array('oleg.poda@thelauncher.pro', 'mariya.shatokhina@mammyclub.com');
    if(isset($_GET['toemail']) && !empty($_GET['toemail'])) {
      $to = array($_GET['toemail']);
      unset($_GET['toemail']);
    }

    $fields = array('code', 'product.category.name', 'product.brand.name', 'product.product_code', 'product.name', 'additional_product_param', 'price', 'pregnancyweek', 'age_of_child', 'month_of_year');

    // Load CSV Library
    $this->load->library('common/csv');

    // Set headers
    $fTrans = array();
    foreach ($fields as $f) {
      $fTrans[] = lang('siteorder_report.header.' . $f);
    }
    $this->csv->addHeader($fTrans);

    $rows = array();

    $where = $this->processFiltersWhere();
    if(empty($where)) {
      $agoDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 day") ) . " 00:00:00";
      $where = array('created_at >=' => $agoDate);
    }

    log_message('debug', '[Cron_Controller -> siteorder_report] - $where: ' . print_r($where, TRUE));

    $orders = ManagerHolder::get('SiteOrder')->getAllWhere($where, 'e.*, Cart.*, user.*');
    if(!empty($orders)) {

      $allPossibleProductParameterValues = ManagerHolder::get('ParameterValue')->getAll('id, name');
      $allPossibleProductParameterValuesIDs = get_array_vals_by_second_key($allPossibleProductParameterValues, 'id');

      foreach ($orders as $o) {

        foreach ($o['Cart'][0]['items'] as $cartItem) {

          $row = array();

          foreach ($fields as $f) {
            $row[$f] = '';
            if($f == 'code') {
              $row[$f] = $o[$f];
              continue;
            }
            if($f == 'additional_product_param') {
              if(!empty($cartItem['additional_product_params'])) {
                $cartItem['additional_product_params'] = unserialize($cartItem['additional_product_params']);
                $paramValueID = array_pop($cartItem['additional_product_params']);
                $paramValueKey = array_search($paramValueID, $allPossibleProductParameterValuesIDs);
                $row[$f] = $allPossibleProductParameterValues[$paramValueKey]['name'];
              }
              continue;
            }
            if($f == 'pregnancyweek') {
              if(!empty($o['user']['pregnancyweek_current'])) {
                $row[$f] = $o['user']['pregnancyweek_current']['number'];
              }
              continue;
            }
            if($f == 'age_of_child') {
              $row[$f] = $o['user']['age_of_child'];
              continue;
            }
            if($f == 'month_of_year') {
              $row[$f] = lang('month.' . date('n', strtotime($o['created_at'])));
              continue;
            }
            if(strpos($f, '.') !== FALSE) {
              $row[$f] = get_nested_array_value_by_key_with_dots($cartItem, $f);
            } else {
              $row[$f] = $cartItem[$f];
            }
          }

          $rows[] = $row;
        }
      }
    }
    $this->csv->addRows($rows);

    $fileName = 'siteorder_report_' . date('Y-m-d') . '.csv';

    $this->csv->saveFile('./web/' . $fileName);

    $subject = 'Отчет по статистике продаж за прошлые сутки';
    $message = 'Отчет во вложении письма';
    ManagerHolder::get('Email')->addAttachment('./web/' . $fileName);
    ManagerHolder::get('Email')->send($to, $subject, $message);

    unlink('./web/' . $fileName);

    log_message('debug', '[Cron_Controller -> siteorder_report] - Finished');
    die();
  }

  /**
   * processFiltersWhere
   */
  private function processFiltersWhere() {

    $result = array();

    // Get same filters from xAdmin_SiteOrder
    $dateFilters = array("created_at");
    $filters = array("paid" => "",
                     "status" => "",
                     "user.id" => "",
                     "made_via_phone" => "");

    // Get from GET array
    if (!empty($_GET)) {

      log_message('debug', '[Cron_Controller -> siteorder_report -> processFiltersWhere] - GET: ' . print_r($_GET, TRUE));

      foreach ($filters as $key => $value) {
        if (!isset($_GET[$key])) continue;
        $result[$key] = $_GET[$key];
      }

      // Get DateFilters from GET array
      foreach ($dateFilters as $key) {
        if (isset($_GET[$key . '_from']) && isset($_GET[$key . '_to'])) {
          $result[$key . 'BETWEEN'] = $_GET[$key . '_from'] . ' 00:00:00' . ' AND ' . $_GET[$key . '_to'] . ' 23:59:59';
        } else {
          if (isset($_GET[$key . '_from'])) {
            $result[$key . '>'] = $_GET[$key . '_from'] . ' 00:00:00';
          }
          if (isset($_GET[$key . '_to'])) {
            $result[$key . '<'] = $_GET[$key . '_to'] . ' 23:59:59';
          }
        }
      }
    }

    if(!empty($result)) {
      foreach ($result as $key => &$value) {
        if ((!is_array($value) && trim($value) == '') || (is_array($value) && empty($value))) {
          unset($result[$key]);
        }
        if ($value === 'NULL') {
          $value = null;
        }
      }
    }

    return $result;
  }

  /**
   * np_get_warehouse_list
   */
  public function np_get_warehouse_list($protection_code) {

    if ($protection_code != self::PROTECTION_CODE) {
      show_404();
    }

    log_message('debug', '[cron -> np_get_warehouse_list] - started');

    try {
      $npJson = file_get_contents('http://new.novaposhta.ua/shop/office/getJsonWarehouseList/');
      if($npJson != FALSE) {
        file_put_contents('./web/np.json', $npJson);
      }
    } catch (Exception $e) {
      log_message('error', '[cron -> np_get_warehouse_list] - error: ' . $e->getMessage());
    }

    log_message('debug', '[cron -> np_get_warehouse_list] - finished');
  }

  /**
   * Showcase report
   */
  public function showcase_report($protection_code) {

    if ($protection_code != self::PROTECTION_CODE) {
      show_404();
    }

    $fields = array('showcase_type', 'showcase_sex_type', 'showcase_name', 'product_name', 'product_status');

    // Load CSV Library
    $this->load->library('common/csv');

    // Set headers
    $fTrans = array();
    foreach ($fields as $f) {
      $fTrans[] = lang('showcase_report.header.' . $f);
    }
    $this->csv->addHeader($fTrans);

    $managerMap = array('PregnancyWeek' => array('products'),
                        'FirstYearBroadcast' => array('products', 'products_boys', 'products_girls'),
                        'Showcase' => array('products'),
                        'RecommendedProductsBroadcast' => array('products'),
                        'UsefulTipsBroadcast' => array('products', 'products_boys', 'products_girls'));


    $rows = array();
    foreach ($managerMap as $manager => $productsArray) {

      log_message('debug', '[cron -> showcase_report] - processing ' . $manager);

      $what = 'e.*';
      foreach ($productsArray as $pa) {
        $what .= ', ' . $pa . '.*';
      }

      $entities = ManagerHolder::get($manager)->getAll($what);
      if(empty($entities)) {
        continue;
      }

      foreach ($entities as $e) {

        $eNameField = 'subject';
        if($manager == 'PregnancyWeek') {
          $eNameField = 'email_subject';
        } elseif ($manager == 'Showcase') {
          $eNameField = 'name';
        }

        foreach ($productsArray as $productsAlias) {

          if(!empty($e[$productsAlias])) {

            foreach ($e[$productsAlias] as $product) {
              $row = array('showcase_type' => lang('showcase_report.row.showcase_type.' . $manager),
                           'showcase_sex_type' => lang('showcase_report.row.showcase_sex_type.' . $productsAlias),
                           'showcase_name' => $e[$eNameField],
                           'product_name'  => $product['name']);
              $row['product_status'] = 'Есть в наличии';
              if($product['not_in_stock'] == TRUE) {
                $row['product_status'] = 'Нет в наличии';
              }
              $rows[] = $row;
            }

          }

        }

      }

    }

    $this->csv->addRows($rows);

    $this->csv->saveFile('./web/showcase_report.csv');

    $to = array('oleg.poda@thelauncher.pro', 'mariya.shatokhina@mammyclub.com');
    $subject = 'Отчет об неактуальных товарах';
    $message = 'Отчет доступен по ссылке: ' . site_url('web/showcase_report.csv');
    ManagerHolder::get('Email')->send($to, $subject, $message);

    traced($rows);
  }

  /**
   * Process_mbr_html
   * @param string $protection_code
   */
  public function process_mbr_html($protection_code) {

    if ($protection_code != self::PROTECTION_CODE) {
      show_404();
    }

    log_message('debug', '[process_mbr_html] - started');

    $agoDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 week") ) . " 00:00:00";;
    $mbr = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere(array('updated_at <' => $agoDate), 'id');
    if (!empty($mbr)) {
      $where = array('recipient_id' => get_array_vals_by_second_key($mbr, 'id'));
      $htmlCount = ManagerHolder::get('MandrillBroadcastRecipientHtml')->getCountWhere($where, 'id');
      log_message('debug', '[process_mbr_html] - found $htmlCount to process: ' . $htmlCount);
      if($htmlCount > 0) {
        ManagerHolder::get('MandrillBroadcastRecipientHtml')->deleteAllWhere($where);
      }
    }
    log_message('debug', '[process_mbr_html] - finished');
  }

  /**
   * Pregnancy week recount
   * @param string $protection_code
   */
  public function pregnancy_week_recount($protection_code) {

    if ($protection_code != self::PROTECTION_CODE) {
      show_404();
    }

    log_message('debug', '[pregnancy_week_recount] - Started');

    $dateFormat = 'Y-m-d';
    $today = date($dateFormat);

    $week = array('normal' => 60 * 60 * 24 * 7,
                  'with_summer_time_recount' => 60 * 60 * 24 * 7 - 60 * 60,
                  'with_winter_time_recount' => 60 * 60 * 24 * 7 + 60 * 60);

    $lastWeekNum = ManagerHolder::get('PregnancyWeek')->getMax('number');
    ManagerHolder::get('User')->setOrderBy('id ASC');

    //Select all users
    $users = ManagerHolder::get('User')->getAll('pregnancyweek_current_id, pregnancyweek_current_started, age_of_child, newsletter, newsletter_first_year, age_of_child_current_started');

    log_message('debug', '[pregnancy_week_recount] - Found users: ' . count($users));

    foreach ($users as $user) {
      // Select current week data
      $weekData = ManagerHolder::get('PregnancyWeek')->getOneWhere(array('id' => $user['pregnancyweek_current_id']), 'e.*');
      foreach ($week as $w) {
        if (strtotime($today) - strtotime($user['pregnancyweek_current_started']) == $w) {

          // Check for last week
          if ($weekData['number'] < $lastWeekNum) {
            // Save past week
            try {
              ManagerHolder::get('UserPregnancyWeek')->insert(array('user_id' => $user['id'], 'pregnancy_week_id' => $user['pregnancyweek_current_id']));
            } catch (Exception $e) {
              log_message('error', '[pregnancy_week_recount] - Exception: ' . $e->getMessage());
            }
            // Select week data for next week
            $nextWeekData = ManagerHolder::get('PregnancyWeek')->getOneWhere(array('number' => $weekData['number'] + 1), 'e.*');
            // Update user data
            $data = array('pregnancyweek_current_id' => $nextWeekData['id'],
            			        'pregnancyweek_current_started' => date($dateFormat));
            ManagerHolder::get('User')->updateAllWhere(array('id' => $user['id']), $data);
          }

          // Process first_year broadcast related logic
          if($user['newsletter'] == TRUE && empty($user['age_of_child']) && $weekData['number'] == $lastWeekNum) {
            // Subscribe user on newsletter_first_year
            $firstYearData = array();
            $firstYearData['id'] = $user['id'];
            $firstYearData['age_of_child'] = 1;
            $firstYearData['newsletter_first_year'] = TRUE;
            $firstYearData['age_of_child_current_started'] = $today;

            $firstYearData['newsletter'] = FALSE;
            $firstYearData['pregnancyweek_id'] = null;
            $firstYearData['pregnancyweek_current_id'] = null;
            $firstYearData['pregnancyweek_current_started'] = null;
            ManagerHolder::get('UserPregnancyWeek')->deleteAllWhere(array('user_id' => $user['id']));

            ManagerHolder::get('User')->update($firstYearData);
          }

        }

        // Process first_year broadcast related logic
        if(strtotime($today) - strtotime($user['age_of_child_current_started']) == $w) {
          $firstYearData = array();
          $firstYearData['age_of_child'] = $user['age_of_child'] + 1;
          $firstYearData['age_of_child_current_started'] = $today;
          ManagerHolder::get('User')->updateAllWhere(array('id' => $user['id']), $firstYearData);
        }

      }

    }

    log_message('debug', '[pregnancy_week_recount] - Finished');
  }


  /**
   * shadow_pinger
   */
  public function shadow_pinger($protection_code) {
    if ($protection_code != self::PROTECTION_CODE) {
      show_404();
    }

    $emails = 'alexeii.boyko@gmail.com, chizhmakov@itirra.com, civictyper13@gmail.com, rajen@mail.ru';

    $textCheck = 'это твой помощник, который поможет тебе быстро найти ответ на задание или скачать учебник по школьной программе без всяких ограничений';

    // Default server statuses
    $serversStatus = array('main' => 'up', 'proxy' => 'up');

    // Create service storage file if not exists
    $pingerFilePath = './web/uploads/pinger.txt';
    if (!file_exists($pingerFilePath)) {
      $fh = fopen($pingerFilePath, 'w');
      fclose($fh);
      file_put_contents($pingerFilePath, json_encode($serversStatus));
    }

    // Get pinger info
    $pingerFileContents = file_get_contents($pingerFilePath);
    if (!empty($pingerFileContents)) {
      $serversStatus = json_decode($pingerFileContents, TRUE);
    }

    $pagesForProcess = array('http://vshkole.com/',
                             'http://vshkole.com/6-klass/reshebniki',
                             'http://vshkole.com/6-klass/reshebniki/matematika',
                             'http://vshkole.com/6-klass/reshebniki/matematika/na-tarasenkova-im-bogatirova-om-kolomiyets-zo-serdyuk-2014',
                             'http://vshkole.com/6-klass/reshebniki/matematika/na-tarasenkova-im-bogatirova-om-kolomiyets-zo-serdyuk-2014/rozdil-3-vidnoshennya-i-proportsiyi/15-podil-chisla-v-danomu-vidnoshenni-masshtab/631',
                             'http://vklasse.org/',
                             'http://vklasse.org/5-klass/reshebniki',
                             'http://vklasse.org/5-klass/reshebniki/matematika',
                             'http://vklasse.org/5-klass/reshebniki/matematika/nya-vilenkin-vi-zhohov-as-chesnokov-si-shvartsburd-2013',
                             'http://vklasse.org/5-klass/reshebniki/matematika/nya-vilenkin-vi-zhohov-as-chesnokov-si-shvartsburd-2013/3-umnozhenie-i-delenie-naturalnyh-chisel/13-delenie-s-ostatkom/530');

    foreach ($serversStatus as $server => $status) {
      $currentCheckFailed = FALSE;
      foreach ($pagesForProcess as $p) {
        if ($server == 'main') {
          $p = str_replace('http://', 'http://690fb44ec79eb67fcac1635a43674398.', $p);
        }
        $time_start = microtime(true);
        $result = $this->sendRequest($p, 30);
        $time_end = microtime(true);
        $total_time = $time_end - $time_start;
        if(!$result['response'] || strpos($result['response'], $textCheck) === FALSE) {
          if($status == 'up') {
            ManagerHolder::get('Email')->send($emails, 'vshkole/vklasse ' . $server . ' server has gone away..', '<a href="' . $p . '">' . $p . '</a> is down!<br />Total execution time: ' . $total_time . '<br />Http code: ' . $result['http_code']);
            $status = 'down';
            $serversStatus[$server] = $status;
            file_put_contents($pingerFilePath, json_encode($serversStatus));
          }
          $currentCheckFailed = TRUE;
        } elseif($total_time > 10) {
          if($status == 'up') {
            ManagerHolder::get('Email')->send($emails, 'vshkole/vklasse ' . $server . ' server is slow right now..', '<a href="' . $p . '">' . $p . '</a> is slow right now!<br />Total execution time: ' . $total_time . '<br />Http code: ' . $result['http_code']);
            $status = 'down';
            $serversStatus[$server] = $status;
            file_put_contents($pingerFilePath, json_encode($serversStatus));
          }
          $currentCheckFailed = TRUE;
        }
      }
      if($status == 'down' && $currentCheckFailed == FALSE) {
        $serversStatus[$server] = 'up';
        file_put_contents($pingerFilePath, json_encode($serversStatus));
        $httpPref = $server=='main'?'http://690fb44ec79eb67fcac1635a43674398.':'http://';
        $message  = '<a href="' . $httpPref . 'vshkole.com/">' . $httpPref . 'vshkole.com/</a><br/><a href="' . $httpPref . 'vklasse.org/">' . $httpPref . 'vklasse.org/</a>';
        ManagerHolder::get('Email')->send($emails, 'vshkole/vklasse ' . $server . ' server is currently availiable', $message);
      }
    }
  }

  /**
   * sendRequest by CURL
   */
  private function sendRequest($url, $timeout = 0, $return = TRUE) {
    $result = array('response' => null, 'http_code' => null);
    $curlHandle = curl_init();
    curl_setopt($curlHandle, CURLOPT_URL, $url);
    $options = array(CURLOPT_RETURNTRANSFER => $return,
                     CURLOPT_ENCODING => "UTF-8",
                     CURLOPT_FAILONERROR => FALSE,
                     CURLOPT_FOLLOWLOCATION => FALSE,
                     CURLOPT_CONNECTTIMEOUT => $timeout,
                     CURLOPT_TIMEOUT => $timeout);
    curl_setopt_array($curlHandle, $options);
    $result['response'] = curl_exec($curlHandle);
    $result['http_code'] = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
    curl_close($curlHandle);
    return $result;
  }

	/**
	 * resendEmailConfirmation
	 */
  public function resendEmailConfirmation($protection_code){
	  if ($protection_code != self::PROTECTION_CODE) {
		  show_404();
	  }

	  $dateOneHourAgo = date(DOCTRINE_DATE_FORMAT, strtotime('-1 hour'));
		$users = ManagerHolder::get('User')->getAllWhere(array('auth_info.created_at BETWEEN' => '2017-10-19 00:00:00 AND ' . $dateOneHourAgo),'e.*, auth_info.*');
		foreach ($users as $user){
			if ($user['resended_email'] != 1 && empty($user['email_confirm_date'])) {
				ManagerHolder::get('TriggeredBroadcast')->sendSingleTriggeredLetter(TRIGGERED_BROADCAST_AFTER_CONFIRM, $user);
				ManagerHolder::get('User')->updateWhere(array('id' => $user['id']), 'resended_email', 1);
			}
		}
  }
}