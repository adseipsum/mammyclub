<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Cart library
 */
class Cart {

  /** Data */
  private $_data;

  /** Cart item field names */
  private $fields;

  /** Cart item option field names */
  private $optionsFields = array();

  /** CI object */
  private $CI;

  /** Cart config array */
  private $config;

  /** Cart backend */
  private $backend;

  /** Cart entity name */
  private $entityName;

  /** Cart id cookies name */
  private $cookiesName;

  /** Session key */
  private $session_key;


  /**
   *  Constructor
   */
  public function Cart() {
    $this->CI = &get_instance();
    if(!isset($this->CI->session)){
      $this->CI->load->library('Session');
    }
    $this->CI->load->config('cart');
    $this->config = $this->CI->config->item('cart');
    $this->fields = $this->config['fields'];
    $this->backend = $this->config['backend'];
    $this->entityName = isset($this->config['entity_name']) ? $this->config['entity_name'] : '';
    $this->cookiesName = isset($this->config['cookies_name']) ? $this->config['cookies_name'] : '';
    $this->session_key = isset($this->config['session_key']) ? $this->config['session_key'] : '';
    if (isset($this->config['options'])) {
      $this->optionsFields = $this->config['options'];
    }
    $this->_load_data();
  }


  /**
   * add
   * @param $data array
   */
  public function add($data){
    $newItem = $this->_create_item_from_array($data);
    $rowId = $this->_generate_row_id($newItem);
    $alreadyExists = FALSE;
    if(!empty($this->_data)){
      if(isset($this->_data[$rowId])){
        $this->_data[$rowId]['quantity'] += $newItem['quantity'];
        $alreadyExists = TRUE;
      }
    }
    if(!$alreadyExists){
      $this->_data[$rowId] = $newItem;
    }
    
    $this->_save_data();
  }


  /**
   * remove_item
   * @param $id
   */
  public function remove_item($rowId = NULL, $uniqueFieldsData = NULL){
    if(!$rowId) {
      if(!$uniqueFieldsData) return FALSE;
      $rowId = $this->_generate_row_id($uniqueFieldsData);
    }
    if(isset($this->_data[$rowId])){
      unset($this->_data[$rowId]);
      $this->_save_data();
      return TRUE;
    }
    return FALSE;
  }


  /**
   * update_item
   */
  public function update_item($rowId, $data) {
    if(isset($this->_data[$rowId]) && !empty($data)){
      foreach($data as $key => $val) {
        if(isset($this->_data[$rowId][$key])) {
          $this->_data[$rowId][$key] = $val;
        }
      }
      $this->_save_data();
      return TRUE;
    }
    return FALSE;
  }


  /**
   * update_quantity
   * @param $uniqueFields
   * @param $newQuantity
   * @return boolean
   */
  public function update_quantity($newQuantity, $rowId = NULL, $uniqueFieldsData = NULL){
    if(!$rowId) {
      if(!$uniqueFieldsData) return FALSE;
      $rowId = $this->_generate_row_id($uniqueFieldsData);
    }

    if(isset($this->_data[$rowId]) && $newQuantity > 0) {
      $this->_data[$rowId]['quantity'] = $newQuantity;
      $this->_save_data();
      return TRUE;
    }
    return FALSE;
  }


  /**
   * update_quantity_mass
   * @param $newQuantities - array(id => newQuantity)
   * @return boolean
   */
  public function update_quantity_mass($newQuantities){
    $result = FALSE;
    if(!empty($newQuantities)) {
      foreach($newQuantities as $rowId => $newQuantity) {
        $singleResult = $this->update_quantity($newQuantity, $rowId);
        $result = $result || $singleResult;
      }
    }
    return $result;
  }


  /**
   * get_contents
   */
  public function get_contents(){
    return $this->_data;
  }


  /**
   * get_count
   */
  public function get_count(){
    if($this->_data) {
      return count($this->_data);
    }
    return 0;
  }


  /**
   * get_total_quantity
   */
  public function get_total_quantity($items = NULL){
    if(!$items){
      $items = $this->_data;
    }
    $summ = 0;
    if($items) {
      foreach($items as $item){
        $summ += $item['quantity'];
      }
    }
    return $summ;
  }


  /**
   * is_in_cart
   * @param array $uniqueFields
   */
  public function is_in_cart($uniqueFields){
    if($this->_data) {
      $itemRowId = $this->_generate_row_id($uniqueFields);
      $rowIds = array_keys($this->_data);
      foreach($rowIds as $rId) {
        if($rId == $itemRowId) {
          return TRUE;
        }
      }
    }
    return FALSE;
  }


  /**
   * get_total_summ
   * returns either a single number in the case of a single price unit
   * or an array('uah' => $number, 'usd' => $number)
   * if multiple price units is set
   */
  public function get_total_summ($items = NULL){
    if(!$items){
      $items = $this->_data;
    }
    if(!$items) {
      return NULL;
    }
    if($this->config['multiple_price_units']){
      $summs = array();
      foreach($items as $item){
        if(!isset($summs[$item['price_unit']])){
          $summs[$item['price_unit']] = 0;
        }
        $summs[$item['price_unit']] += $item['price'] * $item['quantity'];
      }
      return $summs;

    } else {
      $summ = 0;
      foreach($items as $item){
        $summ += $item['price'] * $item['quantity'];
      }
      return $summ;
    }
  }


  /**
   * clear
   */
  public function clear(){
    $this->_data = array();
    $this->_clear_data();
  }


  /**
   * _generate_row_id
   * @param $uniqueFieldsData
   */
  private function _generate_row_id($uniqueFieldsData) {
    $uniqueFields = $this->config['unique_fields'];

    if(!is_array($uniqueFieldsData) && count($uniqueFields) == 1) {
      // the case with single unique field, e.g. 'id'
      $uniqueFieldsData = array($uniqueFields[0] => $uniqueFieldsData);
    }

    $uniqueStr = '';
    foreach($uniqueFields as $field) {
      if(isset($uniqueFieldsData[$field])) {
        $uniqueStr .= $uniqueFieldsData[$field];
      }
    }
    return md5($uniqueStr);
  }


  /**
   * create_item_from_array
   * @param $array
   * @return cart item array
   */
  private function _create_item_from_array($array){
    $result = array();
    // loop through the fields
    foreach($this->fields as $fieldName => $specificFieldName){
      if(isset($array[$specificFieldName])) {
        $result[$fieldName] = $array[$specificFieldName];
      } elseif(strpos($specificFieldName, '.') !== FALSE) {
        // for joined entities, for example "material.name"
        $value = $this->_get_value_from_array_by_keys($specificFieldName, $array);
        $result[$fieldName] = $value;
      }
    }
    // check the 'options' fields
    $options = array();
    foreach($this->optionsFields as $fieldKey => $f){
      if(isset($array[$f])){
        $options[$f] = $array[$f];
      } elseif(strpos($f, '.') !== FALSE) {
        // for joined entities, for example "material.name"
        $value = $this->_get_value_from_array_by_keys($f, $array);
        $options[$fieldKey] = $value;
      }
    }
    if(!empty($options)){
      $result['options'] = $options;
    }
    return $this->_validate_item($result);
  }


  /**
   * _get_value_from_array_by_keys
   * Keys are passed like: 'key1.key2.key3'
   * @param string
   */
  private function _get_value_from_array_by_keys($keys, $array) {
    $keys = explode('.', $keys);
    $tempValue = $array;
    $isset = TRUE;
    foreach($keys as $key) {
      if(isset($tempValue[$key])) {
        $tempValue = $tempValue[$key];
      } else {
        return FALSE;
      }
    }
    return $tempValue;
  }


  /**
   * _validate_item
   * @param $array
   */
  private function _validate_item($array){
    $uniqueFields = $this->config['unique_fields'];
    foreach($uniqueFields as $field) {
      if(!isset($array[$field])){
        return FALSE;
      }
    }
    if(!isset($array['quantity']) || !$array['quantity']){
      $array['quantity'] = 1;
    }
    return $array;
  }

  /**
   * _save_data
   * saves data to session
   */
  private function _save_data(){
    if($this->backend == 'session') {

      $serializedData = serialize($this->_data);
      $this->CI->session->set_userdata($this->session_key, $serializedData);

    } elseif($this->backend == 'cookies_db') {

      $saved = FALSE;
      if(isset($_COOKIE[$this->cookiesName]) && !empty($_COOKIE[$this->cookiesName])){
        $cartId = $_COOKIE[$this->config['cookies_name']];
        $existsInDb = ManagerHolder::get($this->entityName)->existsWhere(array('cart_id' => $cartId));
        if($existsInDb){
          ManagerHolder::get($this->entityName)->updateWhere(array('cart_id' => $cartId), 'data', serialize($this->_data));
          $saved = TRUE;
        } else {
          // invalid cookies. let's remove them and create another ones
          setcookie($this->config['cookies_name'], '', time() - 3600, '/');
        }
      }

      if(!$saved){
        // creating cookies and save data to db
        $cartId = $this->create_cookies();
        $cart = array('cart_id' => $cartId, 'data' => $this->_data);
        ManagerHolder::get($this->entityName)->insert($cart);
      }

    }
  }


  /**
   * create_cookies
   */
  private function create_cookies(){
    $cartId = md5(uniqid(mt_rand()));
    setcookie($this->config['cookies_name'], $cartId, time()+$this->config['cookies_expire_period'], '/');
    return $cartId;
  }


  /**
   * _load_data
   * loads data from session
   */
  private function _load_data(){
    $data = array();
    if($this->backend == 'session') {

      $data = $this->CI->session->userdata($this->session_key);

    } elseif($this->backend == 'cookies_db') {

      if(isset($_COOKIE[$this->cookiesName]) && !empty($_COOKIE[$this->cookiesName])){
        $cart = ManagerHolder::get($this->entityName)->getOneWhere(array('cart_id' => $_COOKIE[$this->cookiesName]));
        if($cart){
          $data = $cart['data'];
        }
      }
    }

    if(!is_array($data)){
      $this->_data = unserialize($data);
    } else {
      $this->_data = $data;
    }

  }


  /**
   * _clear_data
   * loads data from session
   */
  private function _clear_data(){
    if($this->backend == 'session') {
      $this->CI->session->unset_userdata($this->session_key);
    } elseif($this->backend == 'cookies_db') {
      if(isset($_COOKIE[$this->cookiesName]) && !empty($_COOKIE[$this->cookiesName])){
        // deleting cookie by setting expire date = 1 hour ago
        setcookie($this->config['cookies_name'], '', time() - 3600, '/');
        ManagerHolder::get($this->entityName)->deleteWhere('cart_id', $_COOKIE[$this->cookiesName]);
      }
    }
  }

}