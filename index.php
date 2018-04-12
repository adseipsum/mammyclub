<?php

if ($_SERVER['PATH_INFO'] == '/index.php' && isset($_SERVER['VAGRANT']) ) {
  $_SERVER['PATH_INFO'] = '/';
}

/*
 |---------------------------------------------------------------
| PHP ERROR REPORTING LEVEL
|---------------------------------------------------------------
|
| By default CI runs with error reporting set to ALL.  For security
| reasons you are encouraged to change this when your site goes live.
| For more info visit:  http://www.php.net/error_reporting
|
*/
error_reporting(E_ALL);

/*
 |---------------------------------------------------------------
| SYSTEM FOLDER NAME
|---------------------------------------------------------------
|
| This variable must contain the name of your "system" folder.
| Include the path if the folder is not in the same  directory
| as this file.
|
| NO TRAILING SLASH!
|
*/
$system_folder = "./lib";

/*
 |---------------------------------------------------------------
| APPLICATION FOLDER NAME
|---------------------------------------------------------------
|
| If you want this front controller to use a different "application"
| folder then the default one you can set its name here. The folder
| can also be renamed or relocated anywhere on your server.
| For more info please see the user guide:
| http://codeigniter.com/user_guide/general/managing_apps.html
|
|
| NO TRAILING SLASH!
|
*/
$application_folder = "./app";

/*
 |===============================================================
| END OF USER CONFIGURABLE SETTINGS
|===============================================================
*/


/*
 |---------------------------------------------------------------
| SET THE SERVER PATH
|---------------------------------------------------------------
|
| Let's attempt to determine the full-server path to the "system"
| folder in order to reduce the possibility of path problems.
| Note: We only attempt this if the user hasn't specified a
| full server path.
|
*/
if (strpos($system_folder, '/') === FALSE)
{
  if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
  {
    $system_folder = realpath(dirname(__FILE__)).'/'.$system_folder;
  }
}
else
{
  // Swap directory separators to Unix style for consistency
  $system_folder = str_replace("\\", "/", $system_folder);
}

/*
 |---------------------------------------------------------------
| DEFINE APPLICATION CONSTANTS
|---------------------------------------------------------------
|
| EXT		- The file extension.  Typically ".php"
| SELF		- The name of THIS file (typically "index.php")
| FCPATH	- The full server path to THIS file
| BASEPATH	- The full server path to the "system" folder
| APPPATH	- The full server path to the "application" folder
|
*/
define('EXT', '.php');
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('FCPATH', str_replace(SELF, '', __FILE__));
define('BASEPATH', $system_folder.'/');

if (is_dir($application_folder))
{
  define('APPPATH', $application_folder.'/');
}
else
{
  if ($application_folder == '')
  {
    $application_folder = 'app';
  }

  define('APPPATH', BASEPATH.$application_folder.'/');
}



/*
 |--------------------------------------------------------------------------
| Enviroment
|--------------------------------------------------------------------------
|
*/
require_once "env.php";


/*
|--------------------------------------------------------------------------
| Error displaying
|--------------------------------------------------------------------------
|
*/
if(ENV == 'PROD') {
  if(!empty($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == '93.73.111.38') {
    ini_set('display_errors', 1);
  } else {
	ini_set('display_errors', 0);
  }
} else {
	ini_set('display_errors', 1);
}



/*
 |---------------------------------------------------------------
| CHECK SUBDOMAIN
|---------------------------------------------------------------
|
| Check subdomain
|
*/
$subdomain = NULL;
$isDomainsConfigLoaded = @include_once APPPATH . 'config/domains' . EXT;

if($isDomainsConfigLoaded) {
  if(isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {
    if(preg_match("/(.+)" . preg_quote(CURRENT_DOMAIN, '/') . "/", $_SERVER['HTTP_HOST'], $matches)) {
      $subdomain = rtrim($matches[1], '.');
    }
  }
}
if (ENV == 'TEST' && strpos($_SERVER['PATH_INFO'], '/shop') !== FALSE) {
  $subdomain = 'shop';
}
define('SUBDOMAIN', $subdomain);
unset($subdomain);

/*
|---------------------------------------------------------------
| Session cookies
|---------------------------------------------------------------
|
*/
if($isDomainsConfigLoaded) {
  session_set_cookie_params(0, '/', '.' . CURRENT_DOMAIN);
}

// Composer
require __DIR__.'/lib/vendor/autoload.php';

/*
 |---------------------------------------------------------------
| LOAD THE FRONT CONTROLLER
|---------------------------------------------------------------
|
| And away we go...
|
*/
require_once BASEPATH.'codeigniter/CodeIgniter'.EXT;

/* End of file index.php */
/* Location: ./index.php */