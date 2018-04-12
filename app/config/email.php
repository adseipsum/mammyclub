<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email configureation
| Used in Email libarary and EmailManager.
|--------------------------------------------------------------------------
*/

$config['email']['from_email'] = "noreply@mammyclub.com";

$config['email']['from_name'] = iconv('utf-8', 'windows-1251', "Mammyclub");

$config['email']['encode_subject'] = 'windows-1251';
$config['email']['encode_message'] = 'windows-1251';

$config['email']['settings'] = array('useragent' => 'Itirra',
                              			 'protocol' => 'sendmail',
                                     'mailpath' => '/usr/sbin/sendmail',
                                     'wordwrap' => false,
                                     'wrapchars' => 76,
                                     'mailtype' => 'html',
                                     'charset' => 'windows-1251',
                                     'validate' => false,
                                     'priority' => 5,
                                     'newline' => "\n",
                                     'bcc_batch_mode' => false,
                                     'bcc_batch_size' => 200);
