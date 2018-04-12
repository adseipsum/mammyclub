<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: http://shop.localhost.com');
  header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
  header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
  header('Access-Control-Allow-Credentials: true');
  die();
} else {
  header('Access-Control-Allow-Origin: http://shop.localhost.com');
  header('Access-Control-Allow-Credentials: true');
}
error_reporting(E_ALL);
define('BASEPATH', 'app/');

validate_get(array('seconds', 'pv_id'));

$dbLink = getDbLink();
if(!$dbLink) {
  die();
}

$query = 'UPDATE page_visit SET time_on_page = ' . $_GET['seconds'] . ' WHERE id = ' . $_GET['pv_id'];

@mysql_query($query, $dbLink);
@mysql_close($link);

header("Pragma: public");
header("Cache-Control: no-cache");
header("Expires: -1");
header("Status: 200");

// The DIE :)
die();

/**
 * The Database connect function
 */
function getDbLink () {
  require_once "../env.php";
  require '../app/config/database.php';
  $link = @mysql_pconnect($db["default"]["hostname"], $db["default"]["username"], $db["default"]["password"]);
  if (!$link) {
    return FALSE;
  }
  $db_selected = @mysql_select_db($db["default"]["database"], $link);
  if (!$db_selected) {
    @mysql_close($link);
    return FALSE;
  }
  $query = "SET NAMES utf8;";
  @mysql_query($query, $link);
  return $link;
}

/**
 * Validate get
 */
function validate_get ($params) {
  foreach ($params as $p) {
    if(!isset($p) || empty($p)) {
      die();
    }
  }
}

?>