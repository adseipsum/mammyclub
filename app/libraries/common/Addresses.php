<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Addresses class.
 *
 * I'm Fucking Amazing!
 *
 * Documentation:
 * https://developers.google.com/places/documentation/autocomplete?hl=ru
 *
 * @version 1.0
 * @since 28.02.14
 * @author Andrey Busalov (Itirra - www.itirra.com)
 */
class Addresses {

  /**
   * Addresses Class Options.
   * @var array
   */
  private $options = array(
		'url' => 'https://maps.googleapis.com/maps/api/place/autocomplete/',     // The URL to the API. WITH A TRAILING /
  	'responce_type' => 'json',     // Responce type (json, xml)  
    'types' => 'geocode', // 			 
		'cities' => '(route)', //
		'sensor' => 'false', // Whether the request is from a device with a GPS sensor. Always FALSE	
//	  'components' => 'country:ua',
//    'api_key' => '', // Google API KEY https://developers.google.com/places/documentation/?hl=ru#Authentication
  );

  /**
   * Constructor.
   * @param $apiKey
   * @param array $options
   */
  public function Addresses($apiKey, $options = array()) {
    $this->options['api_key'] = $apiKey;

    if (!empty($options)) {
      $this->options = array_merge($this->options, $options);
    }
  }

  /**
   * Get address list.
   * @param string $query
   * @return array
   * @throws Exception
   */
  public function get_addresses($query) {
    $addresses = array();
    
    $url = $this->options['url'] . $this->options['responce_type'] . '?input=' . urlencode($query);
    if ($this->options['types']) {
      $url .= '&types=' . $this->options['types'];
    }
    if ($this->options['cities']) {
      $url .= '&cities=' . $this->options['cities'];
    }
    if ($this->options['components']) {
      $url .= '&components=' . $this->options['components'];
    }
    if ($this->options['api_key']) {
      $url .= '&key=' . $this->options['api_key'];
    }
    $url .= '&sensor=' . $this->options['sensor'];
    $ch = curl_init();
    //log_message('error', 'Addresses URL = ' . $url);
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $curlout = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($curlout, true);

    //log_message('error', 'Addresses RESPONCE = ' . print_r($response, TRUE));
    $msg = $this->check_status($response['status']);

    if (empty($msg)) {
      $results = $response['predictions'];
      foreach ($results as $result) {
        $addresses[] = $result['terms'][0]['value'];
      }
    } else {
      throw new Exception($msg[0], $msg[1]);
    }
    return $addresses;
  }

  /**
   * Set options.
   * @param array $options
   */
  public function set_options($options) {
    $this->options = array_merge($this->options, $options);
  }


  /**
   * Function to check the status
   * @param string $status
   * @return empty string if OK, if not the error message
   */
  private function check_status($status) {
    if (empty($status)) return 'The status variable is emtpy!';
    if (strtoupper($status) == "OK") {
      return array();
    }
    if (strtoupper($status) == "ZERO_RESULTS") {
      return array();
    }
    if (strtoupper($status) == "OVER_QUERY_LIMIT") {
      return array("You are over your quota.", 200);
    }
    if (strtoupper($status) == "REQUEST_DENIED") {
      return array("Your request was denied, generally because of lack of a sensor parameter.", 300);
    }
    if (strtoupper($status) == "INVALID_REQUEST") {
      return array("The query (address) is missing.", 400);
    }
  }

}