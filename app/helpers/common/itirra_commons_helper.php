<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fix dots in get
 * URL: /?a.b=c
 * Native PHP GET: [a_b] = c
 * Fixed GET: [a.b] = c
 */
if (!function_exists('fix_dots_in_get')) {
  function fix_dots_in_get() {
    if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
      $kvPairs = explode('&', $_SERVER['QUERY_STRING']);
      foreach ($kvPairs as $kvPair) {
        if(strpos($kvPair, '=') === FALSE) {
          continue;
        }
        list($key, $value) = explode('=', $kvPair);
        $key = urldecode($key);

        if (strpos($key, '.') !== FALSE) {
          if (strpos($key, '[') !== FALSE) {
            $array = explode('[', $key);
            $key = $array[0];
            if (isset($_GET[str_replace('.', '_', $key)])) {
              $value = $_GET[str_replace('.', '_', $key)];
            }
          }

          if (isset($_GET[str_replace('.', '_', $key)])) {
            unset($_GET[str_replace('.', '_', $key)]);
            $_GET[$key] = isset($value) ? $value : '';
          }
        }
      }
    }
  }
}

/**
 * Fix dots in post
 * DOES NOT WORK WITH MULTIPART FORMS !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * POST: test.test = c
 * Native PHP POST: [test_test] = c
 * Fixed POST: [test.test] = c
 */
if (!function_exists('fix_dots_in_post')) {
  function fix_dots_in_post() {
    if (!empty($_POST)) {
      $rawPostStr = file_get_contents('php://input');
      if (!empty($rawPostStr)) {
        $rawPostArr = explode('&', $rawPostStr);
        $rawPostKeys = array();
        foreach ($rawPostArr as $rawKey) {
          $rawPostKeys[] = array_shift(explode('=', $rawKey));
        }
        foreach ($rawPostKeys as $rawKey) {
          if (strpos($rawKey, '.') !== FALSE) {
            $pseudoKey = str_replace('.', '_', $rawKey);
            if (isset($_POST[$pseudoKey])) {
              $_POST = array_change_key_name($pseudoKey, $rawKey, $_POST);
            }
          }
        }
      }
    }
  }
}

if (!function_exists('get_ids_from_array')) {
  function get_ids_from_array($arr, $keyName = 'id') {
    $result = array();
    foreach ($arr as $item) if (isset($item[$keyName])) $result[] = $item[$keyName];
    return $result;
  }
}


/**
 * Is not empty.
 * RETURN ISSET AND NOT EMPTY
 * @param array $value
 * @return bool
 *
 * WARNING!!!
 * If you pass $array['key'], where 'key' doesn't exist in $array,
 * it will be created!
 *
 */
if (!function_exists('is_not_empty')) {
  function is_not_empty(&$value) {
    return isset($value) && !empty($value);
  }
}

/**
 * Array change key
 * Change an array key without changing position
 * Input = 'b', 'a', ['b' => 'c']
 * Output = ['a' => 'c']
 */
if (!function_exists('array_change_key_name')) {
  function array_change_key_name($orig, $new, &$array) {
    foreach ($array as $k => $v) {
      $return[$k === $orig ? $new : $k] = (is_array($v) ? array_change_key_name($orig, $new, $v) : $v);
    }
    return $return;
  }
}


/**
 * Surround With Slashes
 * Input = test/test
 * Output = /test/test/
 */
if (!function_exists('surround_with_slashes')) {
  function surround_with_slashes($string, $trim = TRUE) {
    if ($trim == TRUE) {
      $string = trim($string);
    }
    if (empty($string) || $string == "/") {
      return "/";
    }
    if ($string[strlen($string) - 1] != "/") {
      $string = $string . "/";
    }
    if ($string[0] != "/" && strstr($string, "http") === FALSE) {
      $string = "/" . $string;
    }
    return $string;
  }
}

/**
 * Add slash
 * Input = test/test
 * Output = test/test/
 */
if (!function_exists('add_slash')) {
  function add_slash($string) {
    $string = trim($string);
    if (empty($string) || $string == "/") {
      return "/";
    }
    if ($string[strlen($string) - 1] != "/") {
      $string = $string . "/";
    }
    return $string;
  }
}

/**
 * Url Contains
 */
if (!function_exists('url_contains')) {
  function url_contains($what, $url = null) {
    if (!$url) {
      $url = uri_string();
    }
    if (strstr($what, base_url()) === FALSE) {
      $what = str_replace(base_url(), "", $what);
      $url = surround_with_slashes($url);
    }
    // Cutting out leading / if exists
    $what = preg_replace("/^\//", "", $what);
    $what = str_replace("/", "\/", $what);
    // matches "/$what$" or "/$what/"
    return preg_match("/\/$what(\/|$)?/", $url);
  }
}

/**
 * Url Equals
 */
if (!function_exists('url_equals')) {
  function url_equals($what, $url = null) {
    if (!$url) {
      $url = uri_string();
    }
    if (strstr($what, base_url()) !== FALSE) {
      $what = str_replace(base_url(), "", $what);
    }
    $what = surround_with_slashes($what);
    $url = surround_with_slashes($url);
    $result = TRUE;
    if ($url != $what) {
      $result = FALSE;
    }
    return $result;
  }
}


/**
 * Url starts with
 */
if (!function_exists('url_starts_with')) {
  function url_starts_with($what, $url = null) {
    if (!$url) {
      $url = uri_string();
    }
    if (strstr($what, base_url()) === FALSE) {
      $what = str_replace(base_url(), "", $what);
      $url = surround_with_slashes($url);
    }
    // Cutting out leading / if exists
    $what = preg_replace("/^\//", "", $what);
    $what = str_replace("/", "\/", $what);

    // matches "/$what"
    return preg_match("/^\/$what(.*)/", $url);
  }
}


/**
 * Redirect to referral
 */
if (!function_exists('redirect_to_referral')) {
  function redirect_to_referral($defaultRedirect = '') {
    $referrer = get_referrer();
    if ($referrer) {
      redirect($referrer);
    } else {
      redirect($defaultRedirect);
    }
  }
}


/**
 * Get referrer
 */
if (!function_exists('get_referrer')) {
  function get_referrer() {
    $CI =& get_instance();
    $CI->load->library('user_agent');
    if ($CI->agent->is_referral()) {
      return $CI->agent->referrer();
    }
    return null;
  }
}


/**
 * is_local_referrer
 */
if (!function_exists('is_local_referrer')) {
  function is_local_referrer() {
    return preg_match("/" . preg_quote(site_url(), '/') . "/", get_referrer());
  }
}


/**
 * Get get params
 */
if (!function_exists('get_get_params')) {
  function get_get_params($ignoreParams = array()) {
    if (!empty($_GET)) {
      $requestUri = "?";
      $count = 0;
      foreach ($_GET as $key => $value) {
        if (in_array($key, $ignoreParams)) {
          continue;
        }
        if ($count > 0) $requestUri .= "&";
        if (is_array($value)) {
          $countArr = 0;
          foreach ($value as $k => $v) {
            if(is_array($v)) {
              foreach ($v as $kk => $vv) {
                if ($countArr > 0) $requestUri .= "&";
                $requestUri .= $key . '[' . $k . ']' . '[' . $kk . ']' . "=" . $vv;
                $countArr++;
              }
            } else {
              if ($countArr > 0) $requestUri .= "&";
              $requestUri .= $key . '[' . $k . ']' . "=" . $v;
              $countArr++;
            }
          }
        } else {
          $value = urlencode($value);
          $requestUri .= $key . "=" . $value;
        }
        $count++;
      }
      return $requestUri;
    }
    return '';
  }
}


/**
 * Get domain
 */
if (!function_exists('get_domain')) {
  function get_domain($url) {
    $domain = "";
    preg_match("/^(http:\/\/)?([^\/]+)/i", $url, $matches);
    if (isset($matches[2])) {
      $domain = $matches[2];
    }
    $domain = str_replace("www.", "", $domain);
    return $domain;
  }
}


if (!function_exists('in_array_by_id')) {
  function in_array_by_id($needleId, $haystack) {
    foreach ($haystack as $key => $val) {
      if ($val['id'] == $needleId) {
        return TRUE;
      }
    }
    return FALSE;
  }
}

if (!function_exists('array_unique_by_second_key')) {
  function array_unique_by_second_key($array, $secondKey) {
    $result = $array;
    $index = 0;
    foreach ($array as $key => $val) {
      $index++;
      $vl = $val[$secondKey];
      if ($index >= count($array) - 1) {
        break;
      }
      $slArray = array_slice($array, $index, count($array) - 1);
      foreach ($slArray as $k => $v) {
        if ($v[$secondKey] == $vl) {
          if (is_numeric($k) && isset($array[0])) {
            unset($array[$k + $index]);
          } else {
            unset($result[$k]);
          }
        }
      }
      if (is_numeric($key) && isset($array[0])) {
        $array = array_values($array);
      }
    }
    return $result;
  }
}

if (!function_exists('get_array_vals_by_second_key')) {
  function get_array_vals_by_second_key($array, $secondKey, $thirdKey = null) {
    $result = array();
    foreach ($array as $val) {
      if ($thirdKey) {
        if (isset($val[$secondKey][$thirdKey])) {
          $result[] = $val[$secondKey][$thirdKey];
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

if (!function_exists('get_array_vals_count_by_second_key')) {
  function get_array_vals_count_by_second_key($array, $secondKey) {
    if (!is_array($secondKey)) {
      $secondKey = array($secondKey);
    }
    $result = array();
    foreach ($array as $val) {
      foreach($secondKey as $sk) {
        if (isset($val[$sk])) {
          if (isset($result[$sk][$val[$sk]])) {
            $result[$sk][$val[$sk]] += 1;
          } else {
            $result[$sk][$val[$sk]] = 1;
          }
        }
      }
    }
    return $result;
  }
}

if (!function_exists('filter_array_by_where')) {
 function filter_array_by_where($array, $where) {
    $result = array();
    foreach ($array as $val) {
      if (val_where($val, $where)) {
        $result[] = $val;
      }
    }
    return $result;
  }
}


if (!function_exists('val_where')) {
  function val_where($val, $where) {
    $result = FALSE;
    $countOk = 0;
    foreach ($where as $k => $v) {
      if (isset($val[$k])) {
        if (is_array($v)) {
          if (isset($v[$val[$k]])) {
            $countOk++;
          }
        } else {
          if ($val[$k] == $v) {
            $countOk++;
          }
        }
      }
    }
    if ($countOk == count($where)) {
      $result = TRUE;
    }
    return $result;
  }
}


if (!function_exists('trim_post')) {
  function trim_post() {
    if (!empty($_POST)) {
      foreach ($_POST as $k => $v) {
        if (!is_array($v)) {
          $_POST[$k] = trim($v);
        }
      }
    }
  }
}

/**
 * Prints preformatted $obj debug info.
 * @param $obj
 * @param bool $ret Should return string.
 * @return bool
 */
if (!function_exists('trace')) {
  function trace($obj, $ret = FALSE, $style = '') {
    if (!empty($style)) {
      $style = 'style="' . $style . '"';
    }
    $res = "<pre $style>";
    if (is_array($obj)) {
      $res .= print_r($obj, TRUE);
    } else {
      ob_start();
      var_dump($obj);
      $res .= ob_get_clean();
    }
    $res .= "</pre>";
    if ($ret) return $res;
    else print trim($res);
  }

  function tracecmpd($obj1, $obj2) {
    trace($obj1, FALSE, 'float:left; margin: 0;');
    trace($obj2, FALSE, 'float:left; margin: 0;');
    die();
  }

  function traced($obj) {
    trace($obj);
    die();
  }

  function itrace($obj) {
    if (ENV == "DEV") {
      $html = trace($obj, true);
      if (!isset($_SESSION['itrace'])) $_SESSION['itrace'] = array();
      $_SESSION['itrace'][] = array("html" => $html, "time" => date("H:i:s"));
    }
  }

  function itrace_out() {
    $html = "";
    if (ENV == "production") return "";
    if (isset($_SESSION['itrace']) && count($_SESSION['itrace']) > 0) {
      $html = '<a href="#" onclick="$(\'#itrace\').slideToggle();" style="position: absolute; top: 0; right:0; padding: 5px; background: red; color: white">log (' . count($_SESSION['itrace']) . ')</a>';
      $html .= '<ul id="itrace" style="list-style: none; z-index: 10000; display: none; position: absolute; top:10px; right: 0; background: black; opacity: 0.8; color: white; padding: 10px; font-size: 11px;">';
      for ($i = count($_SESSION['itrace']); $i > 0; $i--) {
        if (isset($_SESSION['itrace'][$i]['time'])) $html .= '<li><span style="color: green;">' . $_SESSION['itrace'][$i]['time'] . ' - ' . $_SERVER['REQUEST_URI'] . ':</span> ' . $_SESSION['itrace'][$i]['html'] . '</li>';
      }
      $html .= '</ul>';
    }
    return $html;
  }

}

/**
 * Make plain array out of nested array. Recursion!
 * $array[key1][key2][key3] will become $array[key1.key2.key3].
 * @param array $array
 * @return array
 */
function array_make_plain_with_dots($array) {
  if (is_array($array) && !empty($array)) {
    foreach ($array as $key => &$value) {
      if (is_int($key)) continue;
      if (is_array($value) && !empty($value)) {
        $value = array_make_plain_with_dots($value);
        foreach ($value as $subkey => $subvalue) {
          if (is_int($subkey)) continue;
          $array[$key . '.' . $subkey] = $subvalue;
          unset($array[$key][$subkey]);
        }
        if (empty($array[$key])) unset($array[$key]);
      }
    }
  }
  return $array;
}


function get_nested_array_value_by_key_with_dots($array, $keyWithDots) {
  $result = null;
  if (!empty($array) && !empty($keyWithDots) && strpos($keyWithDots, '.') !== FALSE) {
    $keyArray = explode('.', $keyWithDots);
    $tmp = $array;
    foreach ($keyArray as $k) {
      if (isset($tmp[$k])) {
        $tmp = $tmp[$k];
      } else {
        return $result;
      }
    }
    $result = $tmp;
  }
  return $result;
}


/**
 * Create nested arrays out of keys with dots.
 * @param array $array
 * @return array
 */
if (!function_exists('array_make_nested')) {
  function array_make_nested($array) {
    if (!is_array($array)) return $array;
    foreach ($array as $key => $value) {
      if (strstr($key, '.') !== FALSE) {
        $keyArray = explode('.', $key);
        $toAdd = array(end($keyArray) => $value);
        for ($i = count($keyArray) - 2; $i >= 0; $i--) {
          $newArray = array($keyArray[$i] => $toAdd);
          $toAdd = $newArray;
        }
        $array = array_merge_recursive_distinct($array, $toAdd);
        unset($array[$key]);
      } else {
        $array[$key] = $value;
      }
    }
    return $array;
  }

  function &array_merge_recursive_distinct(array &$array1, &$array2 = null, $overwrite = TRUE) {
    $merged = $array1;
    if (is_array($array2)) {
      foreach ($array2 as $key => $val) {
        if (is_array($array2[$key])) {
          $merged[$key] = (isset($merged[$key]) && is_array($merged[$key])) ? array_merge_recursive_distinct($merged[$key], $array2[$key], $overwrite) : $array2[$key];
        } else {
          if (array_key_exists($key, $merged)) {
            if ($overwrite) {
              $merged[$key] = $val;
            }
          } else {
            $merged[$key] = $val;
          }
        }
      }
    }
    return $merged;
  }
}

/**
 * Creates associated array from value array.
 * Example: array('apple', 'banana') => array('apple' => 'prefix.apple', 'banana' => 'prefix.banana').
 * @param array $array
 * @param string $valuePrefix
 * @return array
 */
if (!function_exists('assoc_array_from_values')) {
  function assoc_array_from_values($array, $valuePrefix = '') {
    if (!is_array($array) || empty($array)) return array();
    $result = array();
    foreach ($array as $value) {
      $result[$value] = $valuePrefix . $value;
    }
    return $result;
  }
}

/**
 * Get array key by value.
 * @param mixed $needle
 * @param array $haystack
 * @return mixed
 */
if (!function_exists('array_key_by_value')) {
  function array_key_by_value($needle, $haystack) {

    /** TODO THIS IS THE SAME AS - array_search */

    if (empty($haystack) || empty($needle) || !is_array($haystack)) return null;
    $result = array();
    foreach ($haystack as $key => $value) {
      if ($value == $needle) {
        $result[] = $key;
      }
    }
    if (empty($result)) $result = null;
    if (count($result) == 1) $result = $result[0];
    return $result;
  }
}

/**
 * Flatten array.
 * @param array
 * @return array
 */
if (!function_exists('flatten_array')) {
  function flatten_array($array) {
    $flat_array = array();
    $size = sizeof($array);
    $keys = array_keys($array);
    for($x = 0; $x < $size; $x++) {
      $element = $array[$keys[$x]];
      if(is_array($element)) {
        $results = flatten_array($element);
        $sr = sizeof($results);
        $sk = array_keys($results);
        for($y = 0; $y < $sr; $y++) {
//           $flat_array[$sk[$y]] = $results[$sk[$y]];
          $flat_array[] = $results[$sk[$y]];
        }
      } else {
//         $flat_array[$keys[$x]] = $element;
        $flat_array[] = $element;
      }
    }
    return $flat_array;
  }
}


/**
 * Array pop by key.
 * @param $array
 * @param $key
 */
if (!function_exists('array_pop_by_key')) {
  function array_pop_by_key(&$array, $key) {
    $result = null;
    if (isset($array[$key])) {
      $result = $array[$key];
      unset($array[$key]);
    }
    return $result;
  }
}

/**
 * Admin site URL.
 * @param $url
 * @return admin url
 */
if (!function_exists("admin_site_url")) {
  function admin_site_url($url) {
    $ci = & get_instance();
    $ci->config->load("admin", true);
    $adminBaseUrl = $ci->config->item("base_route", "admin");
    return site_url($adminBaseUrl . "/" . $url);
  }
}

/**
 * Print Doctrine SQL query with params. Do not fully rely on it.
 * @param $query
 * @param $shouldReturn
 * @return string
 */
if (!function_exists("print_doctrine_sql")) {
  function print_doctrine_sql($query, $shouldReturn = false) {
    $params = $query->getParams();
    $str = str_replace('?', "'%s'", $query->getSqlQuery());
    $result = vsprintf($str, $params["where"]);
    if ($shouldReturn) {
      return $result;
    } else {
      echo $result;
    }
  }
}

/**
 * Make a string's first character uppercase for UTF-8 string
 * @param $str - string to upcase
 * @param $encoding - string encoding
 * @return string - string with upcase first character
 */
if (!function_exists("capitalize_first_utf8")) {
  function capitalize_first_utf8($str, $encoding = 'utf-8') {
    $firstChar = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
    return $firstChar . mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
  }
}

/**
 * Sorts an array by second key
 * @param array $array
 * @param string $on
 * @return $array
 */
if (!function_exists("array_sort")) {
  function array_sort($array, $on, $order = SORT_ASC) {
    $new_array = array();
    $sortable_array = array();
    if (count($array) > 0) {
      foreach ($array as $k => $v) {
        if (is_array($v)) {
          foreach ($v as $k2 => $v2) {
            if ($k2 == $on) {
              $sortable_array[$k] = $v2;
            }
          }
        } else {
          $sortable_array[$k] = $v;
        }
      }
      switch ($order) {
        case SORT_ASC:
          asort($sortable_array);
          break;
        case SORT_DESC:
          arsort($sortable_array);
          break;
      }
      foreach ($sortable_array as $k => $v) {
        $new_array[$k] = $array[$k];
      }
    }
    return $new_array;
  }
}

/**
 * Kprintf
 * A function to replace {key} with value!
 * @param string $line - the
 * @param array $keyValArray
 * @param $alsoRemoveSurroundingTag EXAMPLE "p"
 * @return string
 */
if (!function_exists("kprintf")) {
  function kprintf($line, $keyValArray, $maxChars = 200, $stripTags = TRUE, $alsoRemoveSurroundingTag = '') {
    $keyValArray = array_make_plain_with_dots($keyValArray);
    $result = $line;
    $matches = array();
    if (!empty($alsoRemoveSurroundingTag)) {
      preg_match_all('/<' . $alsoRemoveSurroundingTag . '>{[^}]*}<\/' . $alsoRemoveSurroundingTag . '>/', $line, $matches);
      if (isset($matches[0]) && empty($matches[0])) {
        $alsoRemoveSurroundingTag = '';
        preg_match_all('/{[^}]*}/', $line, $matches);
      }
    } else {
      preg_match_all('/{[^}]*}/', $line, $matches);
    }
    if (!empty($matches)) {
      $matches = $matches[0];
      foreach ($matches as $match) {
        if (!empty($alsoRemoveSurroundingTag)) {
          $key = trim(str_replace(array('<' . $alsoRemoveSurroundingTag . '>{', '}</' . $alsoRemoveSurroundingTag . '>'), '', $match));
        } else {
          $key = str_replace(array('{', '}'), '', $match);
        }
        if (isset($keyValArray[$key])) {
          if ($stripTags) {
            $keyValArray[$key] = strip_tags($keyValArray[$key]);
          }
          if ($maxChars > 0 && strlen($keyValArray[$key]) > $maxChars) {
            $keyValArray[$key] = substr($keyValArray[$key], 0, $maxChars);
          }
          $result = str_replace($match, $keyValArray[$key], $result);
        }

      }
    }
    return $result;
  }
}

/**
 * Full name.
 * @param $entity
 * @return string
 */
if (!function_exists('full_name')) {
  function full_name($entity) {
    $result = '';
    if (isset($entity['first_name'])) $result .= $entity['first_name'] . ' ';
    if (isset($entity['last_name'])) $result .= $entity['last_name'];
    trim($result);
    return $result;
  }
}

/**
 * Is Ajax request.
 * @return bool
 */
if (!function_exists('is_ajax')) {
  function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
  }
}

/**
 * Is CLI.
 * @return bool
 */
if (!function_exists('is_cli')) {
  function is_cli() {
    return php_sapi_name()=='cli';
  }
}

/**
 * obfuscate
 * @param string to obfuscate
 */
if (!function_exists('obfuscate')) {
  function obfuscate($string) {
    $result = "";
    foreach (str_split($string) as $letter) {
      $result .= '&#' . ord($letter) . ';';
    }
    return $result;
  }
}


/**
 * obfuscate_email
 * @param $email
 */
if (!function_exists('obfuscated_email_link')) {
  function obfuscated_email_link($email) {
    $email = obfuscate($email);
    $mailtoStr = obfuscate('mailto');
    $text = '<a href="' . $mailtoStr . ':' . $email . '">' . $email . '</a>';
    $result = "<script class=\"email-script\">var arr = new Array(";
    $chars = str_split($text);
    foreach ($chars as $char) {
      $result .= "'" . $char . "', ";
    }
    $result = rtrim($result, ', ');
    $result .= '); document.write(arr.join("")); $(".email-script").remove();</script>';
    return $result;
  }
}


/**
 * Insert Assoc KV
 * Inserts a key value pair into a specific position of array
 * @param array $array - intitial array
 * @param string $newKey - new key
 * @param mixed $newValue - new values
 * @param integer $pos - position (start from 0)
 */
if (!function_exists('insert_assoc_kv')) {
  function insert_assoc_kv($array, $newKey, $newValue, $pos) {
    $chunk = array_splice($array, 0, $pos);
    $array = $chunk + array($newKey => $newValue) + $array;
    return $array;
  }
}


/**
 * Merge Entity With Array
 * Merges an entity with an array.
 * @param Doctrine_Record $entity
 * @param array $array
 * @return Doctrine_Record
 */
if (!function_exists('merge_entity_with_array')) {
  function merge_entity_with_array($entity, $array, $prevKeys = array()) {
    foreach ($array as $k => $v) {
      if (is_array($v)) {
        $prevKeys[] = $k;
        $entity = merge_entity_with_array($entity, $v, $prevKeys);
      } else {
        if (!empty($prevKeys)) {
          $vl = $entity[$prevKeys[0]];
          $prevKeys = array_slice($prevKeys, 1, count($prevKeys) - 1);
          foreach ($prevKeys as $prevKey) {
            $vl = $vl[$prevKey];
          }
          $vl[$k] = $v;
        } else {
          $entity[$k] = $v;
        }
      }
    }
    return $entity;
  }
}

/**
 * Array compare
 * Recursive function to get the difference between two arrays
 * @param array $array1
 * @param array $array2
 * @return array
 */
if (!function_exists('array_compare')) {
  function array_compare($array1, $array2) {
    $diff = FALSE;
    // Left-to-right
    foreach ($array1 as $key => $value) {
      if (!array_key_exists($key, $array2)) {
        $diff[0][$key] = $value;
      } elseif (is_array($value)) {
        if (!is_array($array2[$key])) {
          $diff[0][$key] = $value;
          $diff[1][$key] = $array2[$key];
        } else {
          $new = array_compare($value, $array2[$key]);
          if ($new !== FALSE) {
            if (isset($new[0])) $diff[0][$key] = $new[0];
            if (isset($new[1])) $diff[1][$key] = $new[1];
          };
        };
      } elseif ($array2[$key] !== $value) {
        $diff[0][$key] = $value;
        $diff[1][$key] = $array2[$key];
      };
    };
    // Right-to-left
    foreach ($array2 as $key => $value) {
      if (!array_key_exists($key, $array1)) {
        $diff[1][$key] = $value;
      };
      // No direct comparsion because matching keys were compared in the
      // left-to-right loop earlier, recursively.
    };
    return $diff;
  }
}

if (!function_exists("normilize_tags")) {
  function normilize_tags(&$tags, $countName = "questions_count", $weightName = "tag_weight", $weightsCount = 10) {
    $counts = get_array_vals_by_second_key($tags, $countName);
    if (empty($counts)) return;
    $max = max($counts);
    $min = min($counts);
    $stepX = 1 / $weightsCount;
    function normalization_alg($y) {
      return $y * $y;
    }

    foreach ($tags as &$tag) {
      $normalized = intval($tag[$countName]) / $max;
      $score = normalization_alg($normalized);
      $aa = round(1000 * $score) % round(1000 * $stepX);
      $tag[$weightName] = intval($score / $stepX) + (($aa == 0) ? 0 : 1);
    }
  }
}


/**
 * array_copy_by_keys
 * Example:
 *
 * $sizes = array('small' => '10px', 'medium' => '12px', 'large' => '13px');
 * $chosen = array_copy_by_keys($sizes, array('small', 'large', 'xxl'));
 *
 * The result will be:
 * $chosen = array('small' => '10px', 'large' => '13px');
 */
if (!function_exists('array_copy_by_keys')) {
  function array_copy_by_keys($array, $keys) {
    $newArray = array_fill_keys($keys, NULL);
    $newArray = array_intersect_key($array, $newArray);
    return $newArray;
  }
}


/**
 * hsc - shortcut for the 'htmlspecialchars' function
 * @param $string
 * @param $quote_style
 * @param $charset
 * @param $double_encode
 * @return string
 */
if (!function_exists('hsc')) {
  function hsc($string, $quote_style = ENT_COMPAT, $charset = null, $double_encode = null) {
    if (!is_string($string)) {
      return $string;
    }
    return htmlspecialchars($string, $quote_style, $charset, $double_encode);
  }
}

/**
 * is_php_version_later_than
 * Considers only major and minor versions
 * E.g. 5.1, 5.3
 * @param $version
 * @return boolean
 * Using: if( is_php_version_later_than("5.3") ) {...
 */
if (!function_exists('is_php_version_later_than')) {
  function is_php_version_later_than($version) {
    $currentVersion = (float)PHP_VERSION;
    $version = (float)$version;
    return $currentVersion >= $version;
  }
}


/**
 * generate_UUID
 * @return string
 */
if (!function_exists('generate_UUID')) {
  function generate_UUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

      // 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),

      // 16 bits for "time_mid"
      mt_rand(0, 0xffff),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,

      // 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
  }
}


/**
 * Log memmory
 */
if (!function_exists('log_memory')) {
  function log_memory($message = NULL, $alsoTrace = FALSE) {
    $memory = 'Memory = ' . memory_get_usage(TRUE) / 1024 / 1024 . ' MB';
    if(!empty($message)) {
      $memory = $message . '<br />' . $memory;
    }
    log_message('error', $memory);
    if ($alsoTrace) {
      trace(date("Y-m-d H:i:s") . ' ' . $memory);
    }
  }
}

/**
 * Trim post
 */
if (!function_exists('trim_post')) {
  function trim_post() {
    if (!empty($_POST)) {
      foreach ($_POST as $k => $v) {
        if (!is_array($v)) {
          $_POST[$k] = trim($v);
        }
      }
    }
  }
}

/**
 * preprocess_phone
 * @param $phone
 * @return string
 */
if (!function_exists('preprocess_phone')) {
  function preprocess_phone($phone) {
    $phone = preg_replace("/[\D]/", "", $phone);
    $phone = substr($phone, -PHONE_LENGTH);
    return $phone;
  }
}