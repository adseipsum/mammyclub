<?php

/**
 * MangerHolder
 * A class to hold and give out Manager Instances.
 * @author Alexei Chizhmakov (Itirra - http://itirra.com)
 */
class ManagerHolder {
  
  /** Manager Modes. */
  const MODE_ADMIN = 'admin';
  const MODE_FRONT = 'front';

	/** Manager instances pool. */
	private static $instances = array();

	/**
	 * Manager Modes.
	 * @var string
	 */
	private static $mode = self::MODE_FRONT;
	
	/**
	 * Language.
	 * @var string
	 */
	private static $language;	
	
  /** Constructor . */
  private function __construct(){}

  /**
   * get
   * @param null $className
   * @param null $constructorArg
   * @return BaseManager|BaseMongoDBManager
   */
  public static function get($className = null, $constructorArg = null) {
		if(is_null($className)) {
			trigger_error("ManagerHolder: Missing class information", E_USER_ERROR);
		}
		$className .= "Manager";
		if (file_exists(APPPATH  . "logic/" . $className . ".php")) {
		  require_once APPPATH . "logic/" . $className . ".php";
		} else {
		  require_once APPPATH . "logic/common/" . $className . ".php";
		}
		
		if(!array_key_exists($className, self::$instances)) {
			if ($constructorArg) {
			  self::$instances[$className] = new $className($constructorArg);
			} else {
				self::$instances[$className] = new $className();
			}
			if (method_exists(self::$instances[$className], 'setMode')) {
		    self::$instances[$className]->setMode(self::$mode);
			}
			if (method_exists(self::$instances[$className], 'setLanguage') && self::$language) {
		    self::$instances[$className]->setLanguage(self::$language);
			}			
		}
		return self::$instances[$className];
	}

  // -----------------------------------------------------------------------------------------
  // ------------------------------------- Mode Operations -----------------------------------
  // -----------------------------------------------------------------------------------------	
  
	/**
	 * Set mode.
	 * @param string mode
	 */
	public static function setMode($mode) {
	  self::$mode = $mode;
	}
	
	/**
	 * Get mode.
	 * @return string mode
	 */
	public static function getMode() {
	  return self::$mode;
	}	
	
  // -----------------------------------------------------------------------------------------
  // ---------------------------------- Language Operations ----------------------------------
  // -----------------------------------------------------------------------------------------
  	
	/**
	 * Set Language.
	 * @param string
	 */
	public static function setLanguage($language) {
	  self::$language = $language;
	}
	
	/**
	 * Get Language.
	 * @return string
	 */
	public static function getLanguage() {
	  return self::$language;
	}		

	/**
	 * Forbid cloning of this object.
	 */
	public final function __clone() {
		trigger_error("ManagerHolder: Cannot clone instance of Singleton pattern", E_USER_ERROR);
	}
	
}