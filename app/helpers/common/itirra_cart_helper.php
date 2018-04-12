<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Itirra Cart Helper
 *
 * Itirra - http://itirra.com
 *
 * @author  Itirra
 * @link    http://itirra.com
 * @since   Version 1.0
 */

// ------------------------------------------------------------------------


/**
 * get_cart_count
 */
if (!function_exists('get_cart_count')) {
  function get_cart_count(){
    $CI = &get_instance();
    return $CI->cart->get_count();
  }
}


/**
 * get_cart_quantity
 */
if (!function_exists('get_cart_quantity')) {
  function get_cart_quantity(){
    $CI = &get_instance();
    return $CI->cart->get_total_quantity();
  }
}


/**
 * get_cart_summ
 */
if (!function_exists('get_cart_summ')) {
  function get_cart_summ(){
    $CI = &get_instance();
    return $CI->cart->get_total_summ();
  }
}


/**
 * is_in_cart
 * @param array $fields
 */
if (!function_exists('is_in_cart')) {
  function is_in_cart($fields) {
    $CI = &get_instance();
    return $CI->cart->is_in_cart($fields);
  }
}