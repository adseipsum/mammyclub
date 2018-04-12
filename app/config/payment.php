<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


//--------- LIQPAY ----------
$config['liqpay']['merchant_id'] = 'i4624975678';
$config['liqpay']['merchant_signature'] = 'D9yctdwouRoAPBbw6dG5DY817Yo';
$config['liqpay']['result_url'] = shop_url('спасибо-за-заказ');
$config['liqpay']['server_url'] = shop_url('liqpay-payment-process');

$config['liqpay']['merchant2_id'] = 'i26527278837';
$config['liqpay']['merchant2_signature'] = 'M7n0JxqArjcrHaIGFQkn3HTJu98px5RHOwI6aibw';
$config['liqpay']['merchant2_server_url'] = shop_url('liqpay-checkout-gate');