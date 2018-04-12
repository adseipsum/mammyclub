<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
*/

/** required */
$config['ip'] = "127.0.0.1";
/** required */
$config['port'] = 9312;
/** add '*' to word with length more the 'prefix_len',   required */
$config['prefix_len'] = 1;
/** add '*' to word with length more the 'prefix_len',   required */
$config['sql_attr_uint'] = "source_id";


/**
    EXAMPLE:
    $config['indexes'][<index_name>] = array();
    $config['indexes'][<index_name>]['sql_attr_uint_value'] = <value_set_in_sphinx_select_query>;
    $config['indexes'][<index_name>]['manager'] = <app_logic_manager_name>;
    $config['indexes'][<index_name>]['weights'] = <array_of_fields_weight>;
 */

$config['indexes'] = array();
