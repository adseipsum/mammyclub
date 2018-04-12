<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

class GeoIpManager {

  private $config = array();

  private $connection;

  public function GeoIpManager() {

    $this->config['hostname'] = "localhost";
    if (ENV == 'DEV') {
      $this->config['username'] = "root";
      $this->config['password'] = "root";
      $this->config['database'] = "geoip";
    }
    if (ENV == 'TEST') {
      $this->config['username'] = "itirra_user_db";
      $this->config['password'] = "zil1DVJUT30Et6Xp";
      $this->config['database'] = "geoip";
    }
    if (ENV == 'PROD') {
      $this->config['username'] = "mammyclub";
      $this->config['password'] = "9t7U5eCvTvQ5nXrf";
      $this->config['database'] = "geoip";
    }

    $this->connection = mysql_connect($this->config['hostname'], $this->config['username'], $this->config['password']);
    mysql_select_db($this->config['database'], $this->connection);
  }

  /**
   * Get country by ip
   * @param string $ip
   * @return string
   */

  public function get_country_by_ip($ip) {
    $query = "SELECT country_code FROM ip WHERE INET_ATON('$ip') BETWEEN begin_ip_num AND end_ip_num LIMIT 1";
    $result = mysql_query($query);
    if (!empty($result)) {
      $result = mysql_fetch_array($result);
      return $result['country_code'];
    } else {
      return FALSE;
    }
  }

}