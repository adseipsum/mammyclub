<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config["templates"] = array();

$config["templates"]["Product"] = array('fields' => array('header.title',
                                                          'header.description'),
                                        'keymap' => array('product_name' => 'name'));