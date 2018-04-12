<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/** 
 * The class that loads an image using the Bing API
 * Uses a library Fileoperations !
 * Uses a config constants 'document_root', 'upload_path' (config.php)!
 * @author Andrew
 */
class bingImages {

  const BING_IMG_API_URL        = "http://api.bing.net/";  
  const API_VERSION             = "2.2";
  const API_TYPE                = "image";
  const API_DATA                = "json";
  const API_HYDRATE_RESULT      = "raw";
  const MIN_RESPONSE_RESULT     = 1; 
  const MAX_RESPONSE_RESULT     = 5;      
  const OFFSET_RESPONSE_RESULT  = 0;

  const BING_API_NAME    = 'bing';
  const GOOGLE_API_NAME  = 'google';
          
  /*
   * Original CodeIgniter object 
   */
  private $ci;
  
  /**
   * Current API
   */
  private $api;
    
  /*
   * Developer key for Bing API
   */
  private $api_id;

  /*
   * Returns site base URL
   */
  private $base_url;

  /*
   * Image upload directory
   */
  private $upload_path;
  
  /**
   * Allowed API
   */
  private $allowed_api  = array(self::BING_API_NAME, self::GOOGLE_API_NAME);
    
  /*
   * Allowed to upload image format
   */
  private $options        = array('width' => 10, 'size' => 10000);
  
  /*
   * Allowed formats for downloading data 
   */
  private $allow_formats  = array('jpg', 'jpeg', 'jpe', 'png', 'gif', 'bmp', 'tiff', 'tif');  
      
  /**
   * Constructor bingImages     
   * @param array $api_id - developer key for Bing API
   */
  function bingImages($api_id) {    
    try {
      $this->ci = &get_instance();
      $this->ci->config->load('uploads');
      $this->ci->load->library('common/Fileoperations');
      $this->mimes    = $this->ci->config->item('mimes');
      $this->base_url = $this->ci->config->item('base_url');           
      $this->upload_path  = $this->ci->config->item('upload_path_images');     
      $this->api    = strtolower($api_id['api']);
      $this->api_id = $api_id['key'];      
      if(!in_array($this->api, $this->allowed_api)) { throw new Exception('This api does not. Support select another!');  }
      if(empty($this->api_id)) { throw new Exception('Please insert your Application ID'); }
    } catch(Exception $e) {
            echo "An error has been detected: " . $e->getMessage();
            die(); 
    }
  }   
    
  /**   
   * Download image by its url and return info about image
   * @access private 
   * @param  string $url
   * @param  string $option   
   * @throws Exception
   */
  private function downloadImgByUrl($url, $option) {
    $ext = null;    
    if(empty($option['folder'])) throw new Exception('Error folder name');                 
    foreach ($this->allow_formats as $format) {
      if(strstr(strtolower($url), $format)) $ext = $format;
    }
    if($ext) {
      $img_name = $this->getImgName($url);      
      if($img_name) {                
        $download_path   =  $this->upload_path . $option['folder'] . '/';                             
        if (!file_exists($download_path)) mkdir($download_path, DIR_WRITE_MODE, true);
        $img_download_path =  $download_path . $img_name;        
        $this->downloadImgProccess($url, $img_download_path);       
        if(file_exists($img_download_path)) {                                                
          $this->ci->fileoperations->get_file_info($img_download_path);
          return $this->ci->fileoperations->file_info;            
        }
      }
    }
  }
  
  /**   
   * Parse url, fix and return image name
   * @access private
   * @param  string $url
   * @return image name - image1.jpg 
   */
  private function getImgName($url) {
    $resource = parse_url($url);
    if(!empty($resource['path'])) {
      $arr = explode('/', $resource['path']);
      $img_name = $arr[count($arr) - 1];
      if(!empty($img_name)) {        
        $img_name = urldecode($img_name);
        $img_name = to_translit($img_name);
        return str_replace(' ', '_', $img_name);
      }  else return FALSE;
    } else {
      return FALSE;
    }
  }
  
  /**   
   * downloadImgProccess
   * @access private
   * @param  string $url
   * @param  string $download_path
   * @param  string $folder
   */
  private function downloadImgProccess($url, $img_download_path) {
    try {
      $ch = curl_init($url);
      $fp = fopen($img_download_path, FOPEN_WRITE_CREATE_DESTRUCTIVE);
      curl_setopt($ch, CURLOPT_FILE, $fp);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_REFERER, $this->base_url);
      curl_exec($ch);
      curl_close($ch);
      fclose($fp);
    } catch(Exception $e) {
         echo "Error in the process of downloading images: " . $e->getMessage();            
    }        
  } 
  
  /**   
   * Direct appeal to the api  
   * @access private 
   * @param string $name - name image
   * @param string $start -	 who apply to a resource
   */
  private function getResponseResult($url) {
    $body = null;  
    if(empty($url)) return $body;         
    try {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_REFERER, $this->base_url);
      $body = curl_exec($ch);
      curl_close($ch);
    } catch(Exception $e) {
         echo "Error in receiving data from the Bing service: " . $e->getMessage();            
    }
    return json_decode($body);
  }
  
  /**     
   * Get url such as : 
   * http://api.bing.net/json.aspx?
   *   	AppId=[my key]&sources=image&version=2.2&query=[search term]&image.count=4&
   *  	 adult=strict&Image.Filters=Style:Photo&Image.Filters=Face:Face  
   * @access public 
   * @param  string $img_name
   * @param  string $source_url
   * @param  int    $count
   * @param  int    $offset
   */
  public function getRequestUrlBING($img_name, $source_url, $count = self::MAX_RESPONSE_RESULT, $offset = self::OFFSET_RESPONSE_RESULT) {
    $img_name   = urlencode($img_name);
    $site       = (!empty($source_url)) ? '%20site:' . $source_url : '';             
    $get_param 	=	 'AppId='        . $this->api_id['key'] . '&' .
    							 'Version='      . self::API_VERSION    . '&' .    
                   'query='        . $img_name . $site    . '&' .
                   'Sources='			 . self::API_TYPE       . '&' .
                   'image.Count='  . $count               . '&' .
            			 'image.Offset=' . $offset              . '&' .
            			 'JsonType=' 		 . self::API_HYDRATE_RESULT;        
    return self::BING_IMG_API_URL . self::API_DATA . ".aspx?" . $get_param;    
  }
  
  /**
   * TEMP FOR GOOGLE
   */
  public function getRequestUrlGOOGLE($img_name, $source_url, $count = self::MAX_RESPONSE_RESULT, $offset = self::OFFSET_RESPONSE_RESULT) {
    $img_name   = urlencode($img_name);
    return "http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=" . $img_name;       
  }
  
  /**
   * TEMP FOR GOOGLE
   */
  function test(&$var, $doTrim = true) {
    if (!empty($var)) { // not empty object
      if ($doTrim && is_string($var)) { // if object is string checking for spaces and do trimming
        $var = trim($var);
        return $var != "";
      } else {
        return TRUE;
      }
      return TRUE;
    } else if ($var == "0") { // if "0" goes from POST - we should
      return TRUE;
    }
    return FALSE;
  }
  
  
  /**   
   * Set images options
   * @access public
   * @param  array $options
   */
  public function setOptions($options) {    
    $this->options = $options;    
    return $this->options;
  }
  
  /**   
	 * Download images on its url   
	 * @access public
   * @param  array $urls
   * @param  array $options -  array('width' => '', height => '', size => '', folder => '')
   * @return array with information about download image
   * Example: [file_name] => test.jpg
            	[extension] => .jpg
            	[file_path] => C:/AppServ/www/allbud/web/uploads/material/
            	[web_path] => uploads/material/
            	[size] => 3
            	[created_date] => 2011-12-14
            	[width] => 150
            	[height] => 112
            	[mime_type] => image/jpeg

   */   
  public function downloadImages($urls,$options) {          
    $loaded_img = array();
    try {
      foreach ($urls as $url) {
        if(!empty($url)) {          
          if($this->verifyImageInfoByUrl($url, $options)) {            
            $loaded_img[] = $this->downloadImgByUrl($url, $options);
          }
        }
      }
    } catch(Exception $e) {
      echo "An error has been detected when download image: " . $e->getMessage();
    }    
    return $loaded_img;
  }
  
  /**   
   * Verify information about the image on a remote server
   * @access private
   * @param string $url     - url on which there is a image
   * @param string $options - desired image size
   */
  private function verifyImageInfoByUrl($url, $options) {
    try {      
      list($width, $height, $type, $attr) = @getimagesize($url);      
      if(empty($width) || empty($height)) {        
        return FALSE;
      }      
      $headers_info = get_headers($url,1);
      if(empty($headers_info['Content-Length'])) {        
        return FALSE; 
      }                            
    } catch (Exception $e) {
      echo "Error checking information about the remote image: " . $e->getMessage();
    }       
    return $this->testSizeImage($width, $height, $headers_info['Content-Length'], $options);    
  }      
    
  /**   
   * Get urls to images.
   * @access private
   * @param string $name - name image
   * @param string $source_url - who apply to a resource
   * @param int $count - count images
   * @param array $options - attributes for downloading images
   */
  public function getImages($name, $source_url, $count, $options = array()) {        
    switch ($this->api) {
      case self::GOOGLE_API_NAME:  return $this->getGoogleImages($name, $source_url, $count, $options); break;
      case self::BING_API_NAME:    return $this->getBingImages($name, $source_url, $count, $options); break;
    }         
  }
  
  private function getGoogleImages($name, $source_url, $count, $options) {    
  $options = $this->setOptions($options);
    $url = $this->getRequestUrlGOOGLE($name, $source_url, $count);
    $res = $this->getResponseResult($url);    
    if ($this->testResponseResultGOOGLE($res)) {
       return $this->getImagesUrlGOOGLE($res, $options);
    } else {
      return FALSE;  
    }
  }
  
  /**   
   * Return image urls
   * @param string $name
   * @param string $source_url
   * @param int $count
   * @param array $options
   */
  private function getBingImages($name, $source_url, $count, $options) {
  $options = $this->setOptions($options);
    $url = $this->getRequestUrlBING($name, $source_url, $count);
    $res = $this->getResponseResult($url);
    trace($res);
    trace('11');    
    if ($this->testResponseResultBING($res)) {               
       return $this->getImagesUrlBING($res, $options);                    
    } else {
      return FALSE;  
    }
  }

  
  /**
   * TEMP FOR GOOGLE
   */
  private function testResponseResultGOOGLE($data) { 
    if(empty($data)) return FALSE;          
    if ($this->test($data->responseData) && $this->test($data->responseData->results) && sizeof($data->responseData->results) > 0) { 
      return TRUE; 
    }   
    return FALSE; 
  }
  
  
  /**   
   * Check response result.
   * @access private   
   * @param mixed $data
   */
  private function testResponseResultBING($data) { 
    if(empty($data)) return FALSE;       
    if(!isset($data->SearchResponse) || empty($data->SearchResponse)) {
      return FALSE;
    } else {      
      } if(!isset($data->SearchResponse->Image->Results) || 
              count($data->SearchResponse->Image->Results) < self::MIN_RESPONSE_RESULT) {
        return FALSE;   
      }    
    return TRUE; 
  }
  
  
  
 /**   
   * TEMP FOR GOOGLE
   */
  private function getImagesUrlGOOGLE($data, $options = array()) {
    $urls = array();
    foreach ($data->responseData->results as $value) {
      if(!empty($value->unescapedUrl)) {
        $urls[] = $value->unescapedUrl;
      }
    }
    return $urls;
  }
  
  
  /**   
   * Return response urls as array
   * @access private
   * @param mixed $data
   * @param array $options
   */
  private function getImagesUrlBING($data, $options = array()) {
    $urls = array();         
    foreach ($data->SearchResponse->Image->Results as $value) {
      if(!empty($value->MediaUrl)) {
        if(isset($value->Width) && isset($value->Height) && isset($value->FileSize)) {
          if($this->testSizeImage($value->Width, $value->Height, $value->FileSize, $options)) {
               $urls[] = $value->MediaUrl;                 
          }
        }                       
      }              
    }     
    return $urls;
  }   
    
  
  /**   
   * Check response image dimension    
   * @access private
   * @param int $response_img_w - image width
   * @param int $response_img_h - image height
   * @param int $response_img_s - image file size
   * @param array  $options
   * @return boolean
   */
  private function testSizeImage($response_img_w,  $response_img_h, $response_img_s,  $options) {
    if(isset($options['width']) && !empty($options['width'])) {
       if($response_img_w < $options['width']) {
         return FALSE;
       }      
    }
    if(isset($options['height']) && !empty($options['height'])) {
      if($response_img_h < $options['height']) {
         return FALSE;
       }      
    }
    if(isset($options['size']) && !empty($options['size'])) {
      if($response_img_s > $options['size']) {
         return FALSE;
       }      
    }
    return TRUE; 
  }  
}


/*
 * 
 * 
 Example of how to work with the library:
 * 
 *  
 */
/* 
  public function download_img() {
    set_time_limit(0);
    //$mis_entities     = array('Instrument', 'Service', 'Material');
    $mis_entities     = array('Material');
    $resource_entity  = 'Resource';    
    $param = array('key' => BING_API_ID);
    $this->load->library("BingImages", $param);
    foreach ($mis_entities as $entity) {
      $material = ManagerHolder::get($entity)->getAllWhere(array("published" => TRUE, 'image_id' => NULL), '*', 30);
      $result = array();
      foreach ($material as $value) {
        if(!empty($value['name'])) {
          $result[$value['id']] = $this->getBingSearchResult($value['name'], '', 3);
        }
      }
      $download_imgs = array();
      foreach ($result as $key => $res) {
        $download_imgs[$key] = $this->bingimages->downloadImages($res, array('folder' => 'material', 'width' => 10, 'size' => 1000000));
      }
      if(!empty($download_imgs)) {
        foreach ($download_imgs as $key => $imgs_info) {
          if(!empty($imgs_info)) {
            foreach ($imgs_info as $img) {
              if(!empty($img['width']) && !empty($img['height'])) {
                try {
                  $resource = new Resource();
                  $resource->fromArray($img);
                  $id = ManagerHolder::get($resource_entity)->insert($resource);
                  trace($id);
                  if(empty($id)) {
                    //ManagerHolder::get($resource_entity)->delete(); // get last id from Resource entity
                  } else {
                    ManagerHolder::get($entity)->updateById($key, 'image_id', $id);
                  }
                } catch (Exception $e) {
                  log_message('error', $e->getMessage());
                  show_error(get_class($this) . ": error insert image info to database " . $e->getMessage());
                }
                break;
              }
            }
          }
        }
      }
    }
  }


private function  getBingSearchResult($name, $site, $count) {
  $result = array();
  $arr  = explode(" ", $name);
  if(!empty($arr) && count($arr) > 0) {
    for($i=0; $i < count($arr) - 1; $i++) {
      $name = str_replace($arr[count($arr)-1-$i], "", $name);
      $result = ($this->bingimages->getImages($name, $site, $count));
      if(!empty($result)) {
        $break;
      }
    }
  }
  return $result;
}
*/