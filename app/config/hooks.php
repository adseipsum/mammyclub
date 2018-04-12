<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

// $hook['post_system'] = array('class'    => 'DBLogger',
//                              'function' => 'process',
//                              'filename' => 'DBLogger.php',
//                              'filepath' => 'hooks');

$hook['pre_controller'] = array('class'    => '',
                                'function' => 'fatal_handler',
                                'filename' => 'fatalHandler.php',
                                'filepath' => 'hooks');


/* End of file hooks.php */
/* Location: ./system/application/config/hooks.php */