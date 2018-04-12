<?php

ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

/*** Config ***/

define('FILENAME', 'emphasize.dat');



/******* POST handler *******/

if(!empty($_POST)) {

  //validation rules
  $rules = array('url' => '',
          			 'selector' => '',
  							 'eid' => '/^[0-9a-f]{32}$/' 
  );

  // validating post params
  $isValid = validate_array($rules, $_POST);
  if(!$isValid) {
    die('Wrong params');
  }

  save_click($_POST['url'], $_POST['selector'], $_POST['eid']);
}


/******* GET handler *******/

if(!empty($_GET)) {

  //validation rules
  $rules = array('url' => '',
          			 'selector' => '',
          			 'current_value' => '',
          			 'max_value' => ''
  );

  // validating get params
  $isValid = validate_array($rules, $_GET);
  if(!$isValid) {
    die('Wrong params');
  }

  send_links_values($_GET['url'], $_GET['selector'], $_GET['current_value'], $_GET['max_value']);
}



/******* Functions ********/

/**
 * save_click
 * @param $url
 * @param $selector
 * @param $eid
 */
function save_click($url, $selector, $eid) {
  // retreiving data
  $data = get_stored_data();

  if(!isset($data[$url])) {
    $data[$url] = array();
  }

  if(!isset($data[$url][$selector])) {
    $data[$url][$selector] = array();
  }

  if(isset($data[$url][$selector][$eid])) {
    // incrementing clicks
    $data[$url][$selector][$eid] += 1;
  } else {
    // setting clicks to 1
    $data[$url][$selector][$eid] = 1;
  }

  //saving data
  save_data($data);
}



/**
 * send_links_values
 * @param $url
 * @param $selector
 * @param $currentValue
 * @param $maxValue
 */
function send_links_values($url, $selector, $currentValue, $maxValue) {
  // retreiving data
  $data = get_stored_data();
  
  // validating params
  if(!isset($data[$url][$selector])) {
    return;
  }
  
  $links = calculate_values_for_links($data[$url][$selector], $currentValue, $maxValue);
  
  $result = NULL;
  if($links) {
    $result = $links;
  }

  //sending result
  die(json_encode($result));
}


/**
 * calculate_values_for_links
 * @param $links
 * @param $currentValue
 * @param $maxValue
 */
function calculate_values_for_links($links, $currentValue, $maxValue) {
  if(empty($links)) return array();
  if($currentValue == $maxValue) return array();
  
  $result = array();
  
  // get max clicks
  $maxClicks = 0;
  foreach($links as $clicksNumber) {
    if($maxClicks < $clicksNumber) {
      $maxClicks = $clicksNumber;
    }
  }

  $stepsCount = $maxValue - $currentValue;
  $stepValue = $maxClicks / $stepsCount;
  
  //calculating each link's value
  foreach($links as $eid => $clicks) {
    $valueDelta = $clicks / $stepValue;
    $value = round($currentValue + $valueDelta);
    $result[$eid] = $value;
  }
  
  return $result;
}



/**
 * get_stored_data
 *
 * Stored data has the following format:
 *
 * Array (
 *   [url] => array(
 *       [selector] => array(
 *           [eid] => int (clicks)
 *       )
 *           )
 *
 *   )
 * )
 *
 * @return array
 */
function get_stored_data() {
  if(!file_exists(FILENAME)) {
    // file doesn't exist.
    return array();
  }

  // file exists. reading it.
  $serializedData = file_get_contents(FILENAME);
  $serializedData = base64_decode($serializedData);
  return unserialize($serializedData);
}


/**
 * save_data
 * @param $data
 */
function save_data($data) {
  $dataToWrite = serialize($data);
  $dataToWrite = base64_encode($dataToWrite);
  @file_put_contents(FILENAME, $dataToWrite, LOCK_EX);
}



/**
 * validate_array
 * @param $rules
 * @param $array
 * @return boolean
 */
function validate_array($rules, $array) {
  foreach($rules as $field => $rule) {
    if(!isset($array[$field]) || empty($array[$field]) || $rule && !preg_match($rule, $array[$field])) {
      return false;
    }
  }
  return true;
}