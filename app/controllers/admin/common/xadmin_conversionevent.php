<?php
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';

/**
 * Xadmin controller.
 * @author Alexei Chizhmakov (Itirra - http://itirra.com)
 */
class xAdmin_ConversionEvent extends Base_Admin_Controller {

  /** Additional Actions. */
  protected $additionalActions = array('results');

  /** Filter. Row example: "column_name" => default_value. Default value may be null. */
  protected $filters = array('conversion.id' => '');

  /** Date Filters. Row example: array("created_at"). */
  protected $dateFilters = array('created_at');
  
  /** Export */
  protected $export = TRUE;

  /** Is delete all action allowed */
  protected $isDeleteAllAllowed = FALSE;


  /**
   * Constructor.
   * Put loads here. Everything else should be in init().
   */
  public function xAdmin_ConversionEvent() {
    parent::Base_Admin_Controller();
  }

  /**
   * setViewParamsIndex
   * @param  $entities
   * @param  $pager
   * @param  $hasSidebar
   */
  protected function setViewParamsIndex(&$entities, &$pager, $hasSidebar) {
    $this->actions = array();
    $this->load->helper('common/itirra_date');
    $months = generate_months('ru', TRUE);
    foreach($entities as &$e) {
      $m = date('m', strtotime($e['created_at']));
      $month = $months[$m];
      $e['created_at'] = date('j', strtotime($e['created_at'])) . ' ' . $month . ' ' . date('Y', strtotime($e['created_at'])) . 'г' . ' в ' . date('G:i', strtotime($e['created_at']));
      $e['page'] = rawurldecode($e['page']);
    }
    // Remove empty columns
    $counts = array();
    foreach($entities as &$e) {
      $en = array_make_plain_with_dots($e);
      foreach ($this->listParams as $param) {
        if (in_array($param, array_keys($en))) {
          if (is_null($en[$param]) || trim($en[$param]) == '') {
            if (!isset($counts[$param])) {
              $counts[$param] = 1;;
            } else {
              $counts[$param]++;
            }
          }
        }
      }
    }
    foreach ($counts as $k => $v) {
      if ($v == count($entities)) {
        unset($this->listParams[array_search($k, $this->listParams)]);
      }
    }
    
    parent::setViewParamsIndex($entities, $pager, $hasSidebar);
  }
  
  
  // CREATE PUBLIC METHOD -> POPULATE DATA CACHE !!!! 
  
  public function populateDataCache($dateFrom, $dateTo) {
    $today = date('Y-m-d');

    $dateInterval = $this->date_interval($dateFrom, $dateTo);
    $conversions = ManagerHolder::get('Conversion')->getAll('id, name');
    foreach ($dateInterval as $di) {
      if (strtotime($di) < strtotime($today)) {
        foreach ($conversions as $c) {
          $exist = ManagerHolder::get('ConversionDataCache')->existsWhere(array('conversion_id' => $c['id'], 'date' => $di));
          if (!$exist) {
            $dayInetrval = day_interval($di);
            $dayCount = ManagerHolder::get('ConversionEvent')->getCountWhere(array('conversion_id' => $c['id'], 'created_at BETWEEN' => $dayInetrval[0] . ' AND ' . $dayInetrval[1]));
            $cache = array('date' => $di, 'event_count' => $dayCount, 'conversion_id' => $c['id']);
            ManagerHolder::get('ConversionDataCache')->insert($cache);
          }
        }
      }
    }
  }
  
  

  /**
   * RESULTS
   */
  public function results() {
    
    if (isset($_GET['created_at_from']) && isset($_GET['created_at_to'])) {
      
      if (empty($_GET['created_at_from']) || empty($_GET['created_at_to'])) {
        redirect($this->adminBaseRoute . '/conversionevent/results');
      }
      $this->load->helper('common/itirra_date');
      
      $today = date('Y-m-d');
      $todayInterval = day_interval($today);
      
      if (strtotime($_GET['created_at_from']) <= strtotime($_GET['created_at_to']) && strtotime($todayInterval[0]) >= strtotime($_GET['created_at_from'])) {
        $dateInterval = $this->date_interval($_GET['created_at_from'], $_GET['created_at_to']);
        $result = array();
        
        /* 
         * Search today in date interval
         * Unset dates after today if user input them
         */
        $todayTimeStamp = strtotime($todayInterval[0]);
        $todayPos = FALSE;
        if ($todayTimeStamp <= strtotime($dateInterval[count($dateInterval) - 1])) {
          foreach ($dateInterval as $i => $di) {
            if (strtotime($di) == $todayTimeStamp) {
              $todayPos = $i;
            } elseif (strtotime($di) > $todayTimeStamp) {
              unset ($dateInterval[$i]);
            }
          }
        }

        $this->populateDataCache($dateInterval[0], $dateInterval[count($dateInterval) - 1]);
        
        $conversions = ManagerHolder::get('Conversion')->getAll('id, name, has_page_table');
        
        /* 
         * Data for graphics
         */
        foreach ($conversions as $c) {
          /* Zero will be shown in graphic when no conversion data  */
          foreach ($dateInterval as $di) {
            $result[$c['id']][$di] = 0;
          }
          
          $conversionDataCache = ManagerHolder::get('ConversionDataCache')->getAllWhere(array('date BETWEEN' => $dateInterval[0]  . ' AND ' . $dateInterval[count($dateInterval) - 1], 'conversion_id' => $c['id']), '*');
          foreach ($conversionDataCache as $cdc) {
            $result[$c['id']][$cdc['date']] = $cdc['event_count'];
          }
          
          if ($todayPos !== FALSE) {
            $todayCount = ManagerHolder::get('ConversionEvent')->getCountWhere(array('conversion_id' => $c['id'], 'created_at BETWEEN' => $todayInterval[0] . ' AND ' . $todayInterval[1]));
            $result[$c['id']][$today] = $todayCount;
          }
        }
        
        /* Data for tables */
        $tablesData = array();
        foreach ($conversions as $c) {
          if ($c['has_page_table'] == 1) {
            if (count($dateInterval) > 1) {
              $firstDay = day_interval($dateInterval[0]);
              $lastDay = day_interval($dateInterval[count($dateInterval) - 1]);
              $tablesData[$c['id']] = ManagerHolder::get('ConversionEvent')->getCountGroupBy('page', array('conversion_id' => $c['id'], 'created_at BETWEEN' => $firstDay[0] . ' AND ' . $lastDay[1]));
            } else {
              $interval = day_interval($dateInterval[0]);
              $tablesData[$c['id']] = ManagerHolder::get('ConversionEvent')->getCountGroupBy('page', array('conversion_id' => $c['id'], 'created_at BETWEEN' => $interval[0] . ' AND ' . $interval[1]));
            }
          }
        }
        
        $this->layout->set("dateInterval", $dateInterval);
        $this->layout->set("conversions", $conversions);
        $this->layout->set("result", $result);
        $this->layout->set("tablesData", $tablesData);
      } else {
        set_flash_error('Введите правильно даты');
        redirect($this->adminBaseRoute . '/conversionevent/results');
      }
    }
    $this->layout->set("backUrl", $this->adminBaseRoute . '/conversionevent');
    $this->layout->view('parts/conversion_event'); 
  }

  /**
   * 
   * @param unknown $dateFrom
   * @param unknown $dateTo
   * @return multitype:unknown string
   */
  private function date_interval($dateFrom, $dateTo) {
    $result = array($dateFrom);
    $day = $dateFrom;
    while ($day != $dateTo) {
      $dayTime = strtotime('+1 day', strtotime($day));
      $day = date('Y-m-d', $dayTime);
      $result[] = $day;
    }
    return $result;
  }

  /**
   * 
   * @param string $date
   * @param string $format
   * @return string
   */
  private function yesterday($date = null, $format = 'Y-m-d') {
    $today = $date;
    if (!$today) {
      $today = date($format);
    }
    $yesterdayTime = strtotime('-1 day', strtotime($today));
    $yesterday = date($format, $yesterdayTime);
    return $yesterday;
  }


}