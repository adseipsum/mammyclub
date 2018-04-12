<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Maxmind GeoIP library
 * MaxMind GeoIP Web Services
 * For location detection through IP
 *
 * USAGE EXAMPLE:
 * if (isset($_SERVER['REMOTE_ADDR']) && empty($_SERVER['REMOTE_ADDR'])) {
 *   $this->load->library('Maxmind', array('55555', 's32iZ0ypsyW0'));
 *   $this->maxmind->detect($_SERVER['REMOTE_ADDR']);
 * }
 *
 * Itirra - http://itirra.com
 * @author Alexei Boyko (Itirra - www.itirra.com)
 */
class Maxmind {

  /** User ID. */
  private $userID = null;

  /** License Key. */
  private $licenseKey = null;

  /**
   * Constructor.
   * @param $userID - User ID of MaxMind Service
   * @param $licenseKey - License Key of MaxMind Service
   */
  public function Maxmind () {

  }

  /**
   * Detection
   *
   * @param $ip - IP address of the visitor
   * @param $type - What to detect [city/country]
   * @return array
   */
  public function detect($ip, $type = 'city') {

    $query = 'https://' . $this->userID . ':' . $this->licenseKey . '@geoip.maxmind.com/geoip/v2.1/' . $type . '/' . $ip;

    $curl = curl_init();
    curl_setopt_array($curl, array(CURLOPT_URL => $query,
                                   CURLOPT_USERAGENT => 'MaxMind PHP Sample',
                                   CURLOPT_RETURNTRANSFER => true,
                                   CURLOPT_SSL_VERIFYPEER => false,
                                   CURLOPT_SSL_VERIFYHOST => false
                                  ));
    $geoData = json_decode(curl_exec($curl), TRUE);

    $this->validateGeoData($geoData);

    $result = $this->prepareResult($geoData, $type);

    return $result;
  }

  /**
   * setOptions
   * @param array $data
   */
  public function setOptions ($data) {
    $this->userID = $data[0];
    $this->licenseKey = $data[1];
  }

  /**
   * validateGeoData
   * @param array $geoData
   */
  private function validateGeoData($geoData) {

    // Check for queries remaining
    $queriesCount = 0;
    if(isset($geoData['maxmind']['queries_remaining'])) {
      $queriesCount = $geoData['maxmind']['queries_remaining'];
      if($queriesCount <= 3000 && ($queriesCount % 300 == 0 || $queriesCount == 0)) {
        ManagerHolder::get('EmailNotice')->send_notice_to_admins_about_queries_remaining($questionCount);
      }
    }

    // Check for error
    if(isset($geoData['error'])) {
      throw new Exception($geoData['error']);
    }
  }

  /**
   * prepareResult
   * @param array $geoData
   * @param string $type
   */
  private function prepareResult($geoData, $type) {
    if($type == 'country') {
      $result = 'UA';
      if(isset($geoData[$type])) {
        $result = $geoData[$type]['iso_code'];
      }
    }
    return $result;
  }

}
?>