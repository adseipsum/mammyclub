<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 | -------------------------------------------------------------------------
| Domains
| -------------------------------------------------------------------------
|
*/
if(ENV == 'DEV') {
  define('CURRENT_DOMAIN', 'localhost.com');
} elseif(ENV == 'TEST') {
  define('CURRENT_DOMAIN', 'dev.itirra.com');
} elseif(ENV == 'PROD') {
  define('CURRENT_DOMAIN', 'mammyclub.com');
}


/*
| -------------------------------------------------------------------------
| Not allowed subdomains
| -------------------------------------------------------------------------
|
*/
$config['subdomains']['not_allowed'][] = 'www';
