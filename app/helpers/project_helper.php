<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('js_data_fields_product')) {
  function js_data_fields_product(array $product, $pos = 1)
  {
    $fields = array(
      'id',
      'name',
      'brand.name',
      'category.name',
      'price',
    );
    $result = array(
      'data-position=' . $pos,
      'data-url=' . shop_url($product['page_url'])
    );
    foreach ($fields as $f) {
      if (strpos($f, '.') !== false) {
        $fArr = explode('.', $f);
        if (isset($product[$fArr[0]][$fArr[1]])) {
          $result[] = 'data-' . $fArr[0] . '="' . $product[$fArr[0]][$fArr[1]] . '"';
        }
      } else {
        if (isset($product[$f])) {
          $result[] = 'data-' . $f . '="' . $product[$f] . '"';
        }
      }
    }
    return implode(' ', $result);
  }
}

if(!function_exists('add_utf_params_to_shop_links')) {
  function add_utf_params_to_shop_links(&$html, $utmContent) {
    $shopUrl = ENV == 'PROD' ? shop_url() : 'https://shop.mammyclub.com/';
    preg_match_all("'<a.*?href=\"(http[s]*://[^>\"]*?|//[^>\"]*?)\"[^>]*?>(.*?)</a>'si", $html, $matches);
    if (isset($matches[1][0]) && isset($matches[2][0])) {
      foreach (array_unique($matches[1]) as $i => $url) {
        if (strpos($url, $shopUrl) === 0) {
          $search = 'href="' . $url . '"';
          $replace = 'href="' . $url . '?utm_source=mammyclub.com&utm_medium=refferal&utm_content=' . urlencode($utmContent) . '&utm_term=' . urlencode(strip_tags($matches[2][$i])) .'"';
          $html = str_replace($search, $replace, $html);
        }
      }
    }
  }
}

if (!function_exists('get_array_vals_by_third_key')) {
  function get_array_vals_by_third_key($array, $secondKey, $thirdKey = null, $fourhKey = null)
  {
    $result = array();
    foreach ($array as $val) {
      if ($thirdKey && $fourhKey) {
        if (isset($val[$secondKey][$thirdKey][$fourhKey])) {
          $result[] = $val[$secondKey][$thirdKey][$fourhKey];
        }
      } else {
        if (isset($val[$secondKey])) {
          $result[] = $val[$secondKey];
        }
      }
    }
    return $result;
  }
}

if (!function_exists('bot_detected')) {
  function bot_detected() {
    if (!isset($_SERVER['HTTP_USER_AGENT']) || preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
      return TRUE;
    }
    return FALSE;
  }
}

if (!function_exists('log_trace')) {
  function log_trace($msg, $disableTrace = FALSE) {
    $callers = debug_backtrace();
    $parentMethod = $callers[1]['function'];
    if($parentMethod == 'log_traced') {
      $parentMethod = $callers[2]['function'];
    }
    $msg = '[' . $parentMethod . '] - ' . $msg;
    log_message('debug', $msg);
    if($disableTrace == FALSE) {
      trace($msg);
    }
  }
}

if (!function_exists('log_traced')) {
  function log_traced($msg, $disableTrace = FALSE) {
    log_trace($msg, $disableTrace);
    die();
  }
}

/**
 * process_siteorder_id_rand
 */
if (!function_exists('process_siteorder_id_rand')) {
  function process_siteorder_id_rand($id, $removeRand = FALSE) {
    $randSeparator = '-';
    if ($removeRand == TRUE) {
      if (strpos($id, $randSeparator) === FALSE) {
        return $id;
      }
      $idSegments = explode($randSeparator, $id);
      return $idSegments[0];
    }
    $rand = substr(md5(rand(1, 10000)), 0, 5);
    return $id . $randSeparator . $rand;
  }
}

/**
 * check_url_for_404
 */
if (!function_exists('check_url_for_404')) {
  function check_url_for_404($url) {
    $result = FALSE;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if($httpCode == 404) {
      $result = TRUE;
    }
    if($httpCode == 301) {
      $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
      $result = check_url_for_404($redirectUrl);
    }
    curl_close($ch);
    return $result;
  }
}

/**
 * string_clean_up
 */
if (!function_exists('string_clean_up')) {
  function string_clean_up($string) {
    return trim(preg_replace('/[^A-Za-z0-9\-\/ ]/', '', fix_white_spaces($string)), ' ');
  }
}

/**
 * get_np_cities_view_array
 * @return array
 */
if (!function_exists('get_np_cities_view_array')) {
  function get_np_cities_view_array() {
    $result = array();
    $npJson = file_get_contents('web/np.json');
    if(!empty($npJson)) {
      $npArray = json_decode($npJson, TRUE);
      foreach ($npArray['response'] as $a) {
        if(!isset($result[$a['cityRu']])) {
          $result[$a['cityRu']] = $a['cityRu'];
        }
      }
      asort($result);
    }
    return $result;
  }
}

/**
 * get_np_warehouse_nubers_by_city
 * @return array
 */
if (!function_exists('get_np_warehouse_nubers_by_city')) {
  function get_np_warehouse_nubers_by_city($city) {
    $result = array();
    $npJson = file_get_contents('web/np.json');
    if(!empty($npJson)) {
      $npArray = json_decode($npJson, TRUE);
      foreach ($npArray['response'] as $a) {
        if($a['cityRu'] == $city) {
          if(!isset($result[$a['number']])) {
            $result[$a['number']]['name'] = '№' . $a['number'];
            $result[$a['number']]['ref'] = $a['ref'];
            if(!is_array($a['addressRu'])) {
              $result[$a['number']]['name'] .= ' (' . $a['addressRu'] . ')';
            }
          }
        }
      }
      asort($result);
    }
    return $result;
  }
}

/**
 * shop_url
 * @param string url
 * @return int
 */
if (!function_exists('shop_url')) {
  function shop_url($url = '') {
    $protocol = 'http://'; // default
    $scheme = parse_url(site_url(), PHP_URL_SCHEME);
    if (!empty($scheme)) {
      $protocol = $scheme . '://';
    }
    if(ENV == 'TEST') {
      $shopUrl = site_url('shop');
      if(!empty($url)) {
        $shopUrl .= '/' . trim($url, '/');
      }
      return $shopUrl;
    }
    return str_replace($protocol, $protocol . 'shop.', site_url($url));
  }
}

/**
 * is_shop
 * @return int
 */
if (!function_exists('is_shop')) {
  function is_shop() {
    return SUBDOMAIN == 'shop';
  }
}



/**
 * Calculate age in weeks
 * @param string $birthDate
 * @return int
 */
if (!function_exists('calculate_age_in_weeks')) {
  function calculate_age_in_weeks($birthDate) {
    $date1 = new DateTime($birthDate);
    $date2 = new DateTime(date('Y-m-d'));
    $days = $date2->diff($date1)->format("%a");
    $fullWeeks = ceil($days/7);
    if($fullWeeks == 0 || $days%7==0) {
      $fullWeeks++;
    }
    return $fullWeeks;
  }
}

/**
 * prepare_viewdata_not_ua
 * @param array $broadcast
 * @return text
 */
if (!function_exists('prepare_viewdata_not_ua')) {
  function prepare_viewdata_not_ua($broadcast) {
    $message = $broadcast['email_main_text'];
    if(!empty($broadcast['email_short_text'])) {
      $message = $broadcast['email_short_text'];
    }
    return $message;
  }
}


/**
 * process week tag
 * @param array $text
 * @param array $user
 * @return array
 */
if (!function_exists('process_week_tag')) {
  function process_week_tag($text, $user) {
    if(strpos($text, '{PREGNANCY_WEEK}') !== FALSE) {
      $pregnancyWeekTitle = '';
      if(!empty($user['pregnancyweek_current'])) {
        $prefix = 'На';
        if(strpos(strip_tags($text), '{PREGNANCY_WEEK}') !== 0) {
          $prefix = 'на';
        }
        $pregnancyWeekTitle = $prefix . ' ' . $user['pregnancyweek_current']['number'] . ' неделе беременности';
      }
      $text = str_replace('{PREGNANCY_WEEK}', $pregnancyWeekTitle, $text);
    }
    return $text;
  }
}

/**
 * process brand text
 * @param array $product reference
 */
if (!function_exists('process_brand_text')) {
  function process_brand_text(&$product) {
    if(strpos($product['description_short2'], '{BRAND_TEXT}') !== FALSE) {
      $replaceText = '';
      if(is_not_empty($product['brand']['description'])) {
        $replaceText = $product['brand']['description'];
      }
      $product['description_short2'] = str_replace('<p>{BRAND_TEXT}</p>', $replaceText, $product['description_short2']);
    }
  }
}

/**
 * shuffle assoc arr
 */
if (!function_exists('shuffle_assoc')) {
  function shuffle_assoc($list) {
    if (!is_array($list)) return $list;
    $keys = array_keys($list);
    shuffle($keys);
    $random = array();
    foreach ($keys as $key) {
      $random[$key] = $list[$key];
    }
    return $random;
  }
}

if (!function_exists('get_ip')) {
  function get_ip() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if($ip == '127.0.0.1') {
      // 62.231.187.137 - UA | Ukraine | Kyiv City | Kiev | Europe
      // 193.160.225.13 - UA | Ukraine | Odessa | Izmail | Europe
      // 62.176.28.81   - RU | Russia | Moscow | Moscow | Europe
      // 188.134.63.0   - RU | Russia | St.-Petersburg | Saint Petersburg | Europe
      // 78.109.137.225 - RU | Russia | Altai Krai | Barnaul | Europe
      // 84.42.3.3      - RU | Russia | Tverskaya oblast' | Tver | Europe
      // 81.30.210.118  - RU | Russia | Bashkortostan | Ufa | Europe
      // 46.29.78.20    - RU | Russia | Samarskaya Oblast' | Samara | Europe
      // 192.80.158.154 - US | United States | Nevada | Henderson
      // 91.185.215.141 - SI | Slovenia | ? | ?
      $ip = '62.176.28.81';
    }
    return $ip;
  }
}

/**
 * process phone number
 * @param string $phone_number
 * @param string $country
 * @return array
 */
if (!function_exists('process_phone_number')) {
  function process_phone_number($phone_number, $country = 'UA') {
    $prefix = '';
    if($country == 'RU') {
      $prefix == '7';
    }
    $phone_number = str_replace(array('(', ')', ' ', '-'), '', $phone_number);
    return $prefix . $phone_number;
  }
}

if (!function_exists('simple_resize_image')) {
  function simple_resize_image($file, $width, $height, $maxSize, $ext) {
    if($ext == 'png') {
      $myImage = @imagecreatefrompng($file);
    } else {
      $myImage = @imagecreatefromjpeg($file);
    }
    if($myImage == FALSE) {
      return FALSE;
    }

    // Calculate aspect ratio
    $wRatio = $maxSize / $width;
    $hRatio = $maxSize / $height;

    // Calculate a proportional width and height no larger than the max size.
    if (($width <= $maxSize) && ($height <= $maxSize)) {
      return $myImage;
    } elseif (($wRatio * $height) < $maxSize) {
      // Image is horizontal
      $tHeight = ceil($wRatio * $height);
      $tWidth = $maxSize;
    } else {
      // Image is vertical
      $tWidth = ceil($hRatio * $width);
      $tHeight = $maxSize;
    }

    // Creating the thumbnail
    $thumb = imagecreatetruecolor($tWidth, $tHeight);
    imagecopyresampled($thumb, $myImage, 0, 0, 0, 0, $tWidth, $tHeight, $width, $height);
    imagedestroy($myImage);
    return $thumb;
  }
}


/**
 * href_replace
 */
if (!function_exists('href_replace')) {
  function href_replace($search, $replace, $html) {

    $search = urldecode($search);
    $replace = urldecode($replace);

    // 1. double quotes with trailing slash
    $searchTemp = 'href="' . $search . '/"';
    $replaceTemp = 'href="' . $replace . '"';
    $html = str_replace($searchTemp, $replaceTemp, $html);

    // 2.double quotes without trailing slash
    $searchTemp = 'href="' . $search . '"';
    $replaceTemp = 'href="' . $replace . '"';
    $html = str_replace($searchTemp, $replaceTemp, $html);

    // 3. single quotes with trailing slash
    $searchTemp = "href='" . $search . "/'";
    $replaceTemp = "href='" . $replace . "'";
    $html = str_replace($searchTemp, $replaceTemp, $html);

    // 4.single quotes without trailing slash
    $searchTemp = "href='" . $search . "'";
    $replaceTemp = "href='" . $replace . "'";
    $html = str_replace($searchTemp, $replaceTemp, $html);

    return $html;
  }
}

/**
 * Send Request by CURL
 */
if (!function_exists('send_curl_request')) {
  function send_curl_request($url, $timeout = 0) {
    $curlHandle = curl_init();
    curl_setopt($curlHandle, CURLOPT_URL, $url);
    $options = array(CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_FAILONERROR => FALSE,
        CURLOPT_FOLLOWLOCATION => FALSE,
        CURLOPT_CONNECTTIMEOUT => $timeout,
        CURLOPT_TIMEOUT => $timeout);
    curl_setopt_array($curlHandle, $options);
    $result = curl_exec($curlHandle);
    curl_close($curlHandle);
    return $result;
  }
}