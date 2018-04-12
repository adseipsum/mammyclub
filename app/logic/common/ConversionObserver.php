<?php

/**
 * ConversionObserver
 * @author Alexei Chizhmakov (Itirra - http://itirra.com)
 */
class ConversionObserver {
  
  /** Constructor . */
  private function __construct(){}
  
	/**
	 * TiggerEvent.
	 * @param string $conversionKey
	 * @param string $page
   * @param string $comment
	 * @return bool - tracked ?
	 */
	public static function triggerEvent($conversionKey, $page = null, $comment = null) {
	  $ip = null;
	  if (isset($_SERVER['REMOTE_ADDR'])) {
	    $ip = $_SERVER['REMOTE_ADDR'];
	  }
	  // HAS Cookies?
	  if (isset($_COOKIE['PHPSESSID']) || isset($_COOKIE['conv_c_' . $conversionKey])) {
  	  if (isset($_COOKIE['conv_c_' . $conversionKey]) && $_COOKIE['conv_c_' . $conversionKey] == 'true') {
  	    return FALSE;
  	  } else {
  	    $conversion = ManagerHolder::get('Conversion')->getOneWhere(array("internal_key" => $conversionKey), 'id, internal_key');
  	    if ($conversion['id']) { 
          self::insertConversionEvent($conversion['id'], $ip, $page, $comment);
  	      setcookie('conv_c_' . $conversionKey, 'true', time()+60*60*24*7);
  	      return TRUE;
  	    } else {
  	      trigger_error("ConversionObserver::tiggerEvent cannot find conversion with KEY = " . $conversionKey, E_USER_ERROR);
  	    }
  	  }
	  } else {
	    $conversion = ManagerHolder::get('Conversion')->getOneWhere(array("internal_key" => $conversionKey), 'id, internal_key');
	    if (!ManagerHolder::get('ConversionEvent')->existsWhere(array('conversion_id' => $conversion['id'], 'ip' => $ip))) {
	      self::insertConversionEvent($conversion['id'], $ip, $page, $comment);
	      return TRUE;
	    } else { 
	      return FALSE;
	    }
	  }
	}
	
	/**
	 * Insert conversion event
	 * @param integer $conversionId
	 * @param string $ip
	 * @param string $page
	 * @param string $comment
	 */
	private static function insertConversionEvent($conversionId, $ip, $page, $comment) {
	  $userAgent = null;
	  $guid = null;
	  if (isset($_SERVER['HTTP_USER_AGENT'])) {
	    $userAgent = $_SERVER['HTTP_USER_AGENT'];
	  }
	  if (isset($_COOKIE['it_user_guid'])) {
	    $guid = $_COOKIE['it_user_guid'];
	  } 
	  ManagerHolder::get('ConversionEvent')->insert(array('conversion_id' => $conversionId, 'ip' => $ip, 'user_agent' => $userAgent, 'guid' => $guid, 'page' => $page, 'comment' => $comment), FALSE);
	}


	/**
	 * Forbid cloning of this object.
	 */
	public final function __clone() {
		trigger_error("ConversionObserver: Cannot clone instance of Singleton pattern", E_USER_ERROR);
	}
}

// --------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------
if (!isset($_COOKIE['it_user_guid']) || empty($_COOKIE['it_user_guid'])) {
  setcookie('it_user_guid', uniqid(md5(rand())), time()+60*60*24*365);
}