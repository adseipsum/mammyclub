<?php
/**
 * Language library.
 * @author Itirra - http://itirra.com
 */
class MY_Language extends CI_Language {

	/** Languages. */
	var $languages = array();
  
	/** Special URIs (not localized). */
	private $special = array();
	
	/** Language set from URL flag. */
	private $languageSetFromUrl = FALSE;
  
	/** Config (lang_config) */
	private $config;
	
  /**
   * Constructor.
   * @return MY_Language
   */
  public function MY_Language() {
    parent::CI_Language();
    global $CFG;
    global $URI;
    global $RTR;
    $segment = $URI->segment(1);
    // Get languages from lang_config
    require APPPATH . 'config/lang_config.php';
    $this->config = $config;
    
    $this->languages = $config['languages'];
    
    if (isset($this->languages[$segment])) {
      $language = $this->languages[$segment];
      $CFG->set_item('language', $language);
      $this->languageSetFromUrl = TRUE;
      
      if (isset($config['save_to_cookie']) && $config['save_to_cookie']) {
        $cockieKey = isset($config['lang_cookie_key']) ? $config['lang_cookie_key'] : 'cookie_lang';
        if ( ! isset($_COOKIE[$cockieKey])) {
          setcookie($cockieKey, $segment, time() + (10 * 365 * 24 * 60 * 60), '/'); // Are 10 year is enough?
        }
      }
    }

//    $language = $CFG->item('language');
//    if ($language) {
//      if ($language == 'russian') {
//        setlocale(LC_ALL, "ru_RU", 'rus_RUS', 'rus', 'russian', 'ru_RU.CP1251', 'ru_RU.UTF-8', 'ru_RU.KOI8-R', 'Russian_Russia.1251');
//        setlocale(LC_TIME, "ru_RU", 'rus_RUS', 'rus', 'russian', 'ru_RU.CP1251', 'ru_RU.UTF-8', 'ru_RU.KOI8-R', 'Russian_Russia.1251');
//      }
//    }
  }

  /**
   * Fetch a single line of text from the language array.
   * Will return the key if value was not found.
   * @override CI_Language.
   * @access public
   * @param string $line the language line
   * @return	string
   */
  public function line($line = '', $args = array(), $maxChars = 200, $stripTags = TRUE) {
    if ($this->has($line)) {
      $line = $this->language[$line];
    } else {
      if (isset($this->config['log_undefined_key']) && $this->config['log_undefined_key']) {
        // Hack for html_flash_message (we dont need russian text)
        if ( ! preg_match("/[а-я]+/i", $line)) {
          $lang = isset($this->languageName) ? $this->languageName : '';
          log_message('error', 'UNDEFINED KEY ' . $line . ' FOR LANGUAGE ' . $lang);
        }
      }
    }

    if (!empty($args)) {
      $line = kprintf($line, $args, $maxChars, $stripTags);
    }
    return $line;
  }

  /**
   * Checks if line exists in message properties.
   * @param $line
   * @return bool
   */
  public function has($line) {
    return isset($line) && !empty($line) && isset($this->language[$line]);
  }

  /**
   * Add message.
   * @param $key
   * @param $val
   * @return void
   */
  public function set($key, $val) {
    $this->language[$key] = $val;
  }

  /**
   * Appends or add message
   * @param  $key
   * @param  $val
   * @return void
   */
  function append($key, $val) {
    if (isset($this->language[$key])) {
      $this->language[$key] .= " " . $val;
    } else {
      $this->set($key, $val);
    }
  }

  /**
   * Get current language.
   * @return string
   */
  function lang() {
    global $CFG;
    $language = $CFG->item('language');
    $lang = array_search($language, $this->languages);
    if ($lang) {
      if ($lang == 'ru') {
        setlocale(LC_ALL, 'ru_RU' ,'rus_RUS', 'rus', 'russian');
        setlocale(LC_TIME, "ru_RU");
      }
      if ($lang == 'ua') {
        setlocale(LC_ALL, 'uk_UA' , 'uk_UA', 'uk', 'ukrainian');
        setlocale(LC_TIME, "uk_UA");
      }
      return $lang;
    }
    return null; // This should not happen
  }
  
	
	function is_special($uri) {
	  if ($uri == "") {
      return FALSE;
    }
		$exploded = explode('/', $uri);
		if (in_array($exploded[0], $this->special)) {
			return TRUE;
		}
		if(isset($this->languages[$uri])) {
			return TRUE;
		}
		return FALSE;
	}
  
	// is there a language segment in this $uri?
	function has_language($uri) {
	  if ($uri == "") {
	    return FALSE;
	  }
		$first_segment = NULL;
		$exploded = explode('/', $uri);
		if(isset($exploded[0])) {
			if($exploded[0] != '') {
				$first_segment = $exploded[0];
			}
			else if(isset($exploded[1]) && $exploded[1] != '') {
				$first_segment = $exploded[1];
			}
		}
		if($first_segment != NULL) {
			return isset($this->languages[$first_segment]);
		}
		return FALSE;
	}
	  

	// add language segment to $uri (if appropriate)
	function localized($uri) {
		if($this->has_language($uri)
      || $this->is_special($uri)
      || preg_match('/(.css$|.png$|.jpg$|.gif$|.js$|.ico$)/i', $uri)) {
	      // we don't need a language segment because:
	      // - there's already one
	      // - or it's a special uri (set in $special)
	      // - or that's a link to a file
      } else {
		    $CI =& get_instance();
		    $curUri = $CI->uri->uri_string();
      	if ($this->has_language($curUri)) {
      	  $uri = '/' . $this->lang() . '/' . ltrim($uri, '/');
      	}
      }
		return $uri;
	}

  /**
   * Generate uri to switch language.
   * @param $lang language to switch to
   * @param $definedUrl
   * @return url
   */
  function switch_uri($lang = NULL, $definedUrl = NULL) {
    $langCodeSection = 1;
    $CI =& get_instance();
    
    $uri = $lang;
    
    // Defined url
    // Return http://example.com/en/defined_url
    if (isset($definedUrl)) {
      $uri .= '/' . $definedUrl;
      return site_url($uri);
    }
    
    if (isset($_SERVER['PATH_INFO'])) {
      // Should contain get params
      $uri = $CI->config->site_url($_SERVER['PATH_INFO']);
    }
    
    // Searching for one of lang codes in url
    // If found - replace it with $lang
    $replaced = false;
    foreach($this->languages as $langCode => $langName){
      $uri = preg_replace("/\/$langCode($|\/)/", "/$lang" . ($lang ? '/' : ''), $uri, 1, $count);
      if($count === 1){
        $replaced = true;
        break;
      }
    }

    if(!$replaced){
      // Add lang_code to url
      $uri = str_replace(base_url(), base_url() . $lang . ($lang ? '/' : ''), $uri);
    }

    $uri = rtrim($uri, '/');
    return $uri;
  }

  /**
   * Is language set from url.
   * @return bool
   */
  public function isLanguageSetFromUrl() {
    return $this->languageSetFromUrl;
  }
  
}