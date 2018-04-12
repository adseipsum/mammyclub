<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (ENV == 'PROD') {
  $config['broadcast_app']['api_endpoint'] = 'http://broadcast.mammyclub.com/';
} else {
  $config['broadcast_app']['api_endpoint'] = 'http://vm.mammyclub.com';
}
