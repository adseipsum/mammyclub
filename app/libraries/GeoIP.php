<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'exceptions/GeoipRequestFailedException.php';

/**
 * GeoIP library
 * MaxMind GeoIP Web Services
 * For location detection through IP
 * 
 * USAGE EXAMPLE:
 * if (isset($_SERVER['REMOTE_ADDR']) && empty($_SERVER['REMOTE_ADDR'])) {
 *   $this->load->library('GeoIP', array('s32iZ0ypsyW0'));
 *   $remoteIp = $_SERVER['REMOTE_ADDR'];
 *   $this->geoip->detect($remoteIp, 's32iZ0ypsyW0');
 * }
 * 
 * Itirra - http://itirra.com
 * @author Alexei Boyko (Itirra - www.itirra.com) 
 */
class GeoIP {
  
  /** License Key. */
  private $licenseKey = null;

  /**
   * Constructor.
   * @param $licenseKey - License Key of MaxMind Service
   * @return ViewCount
   */
  public function GeoIP ($licenseKey) {
    $CI =& get_instance();
    $this->licenseKey = $licenseKey[0];
  }
  
  /**
   * Country detection
   * 
   * @param $ip - IP address of the visitor
   * @return array
   */
  public function detectCountry($ip) {
    $params = array('l' => $this->licenseKey,
                    'i' => $ip);
    $query = 'https://geoip.maxmind.com/a?' . http_build_query($params);
    
    $curl = curl_init();
    curl_setopt_array($curl, array(CURLOPT_URL => $query,
                                   CURLOPT_USERAGENT => 'MaxMind PHP Sample',
                                   CURLOPT_RETURNTRANSFER => true,
                                   CURLOPT_SSL_VERIFYPEER => false,
                                   CURLOPT_SSL_VERIFYHOST => false
                                  ));
    
    $resp = curl_exec($curl);
    
    if (curl_errno($curl)) {
      throw new GeoipRequestFailedException('GeoIP Request Failed');
    }
    return $resp;
  }
  
}
?>