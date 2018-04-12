<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Library Sitemap
 * Generates sitemap for db entites, specified in config/sitemap.php
 */
class Sitemap {

  const SITEMAP_XML_HEADER = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
  const SITEMAP_IMG_XML_HEADER = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
  const SITEMAP_XML_FOOTER = '</urlset>';

  const SITEMAP_INDEX_XML_HEADER = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
  const SITEMAP_INDEX_XML_FOOTER = '</sitemapindex>';

  /* sitemap config file */
  private $config;

  /* sitemap name */
  private $sitemapName = 'sitemap';

  /**
   * Sitemap library constructor
   */
  public function Sitemap($array) {
    if(isset($array['sitemapName'])) {
      $this->sitemapName = $array['sitemapName'];
    }

    $this->CI =& get_instance();
    $this->CI->load->helper('file');
    $this->CI->load->helper('url');
    $this->CI->load->config($this->sitemapName);
    $this->config = $this->CI->config->item('sitemap');
  }

  /**
   * Generates sitemap.xml and children maps for entities, specified in config
   * @return site url of sitemap.xml
   */
  public function generate() {
    $entities = $this->config['entities'];
    if(empty($entities)) {
    	//do not create any files
    	return NULL;
    }

    $this->deleteAllEntitiesSitemaps();

    $xml = self::SITEMAP_INDEX_XML_HEADER;
    foreach ($entities as $e) {
      $urls = $this->generateForEntity($e['name'], isset($e['image'])?TRUE:FALSE);
      foreach ($urls as $u) {
        $xml .= '<sitemap>';
        $xml .= '<loc>' . $u . '</loc>';
        $xml .= '</sitemap>';
      }
    }
    $xml .= self::SITEMAP_INDEX_XML_FOOTER;
    return $this->saveSitemapIndex($xml);
  }

  /**
   * Generate sitemap file for specified entity
   * @param $entityName - entity (manager) name
   * @return aray of generated files names
   */
  public function generateForEntity($entityName) {
    $entities = ManagerHolder::get($entityName)->getAllForSitemap();
    if(empty($entities)) {
    	//do not create any files
    	return Array();
    }

    // Generated files names array
    $filesNames = Array();

    // base size of xml file
    $baseSize = strlen(self::SITEMAP_XML_HEADER) + strlen(self::SITEMAP_XML_FOOTER);

    // file constraints counters
    $recordNum = 1;
    $fileSize = $baseSize;

    // file sequencial number
    $fileNum = 1;

    // Get date segments for lastmod
    $dateSegmets = explode(' ', date(DOCTRINE_DATE_FORMAT));

    // nedd to include images into sitemap?
    $includeImage = FALSE;

    // generating XML
    $xmlUrls = '';
    foreach($entities as $e) {
      $xmlUrl  = '<url>';
      $xmlUrl .= '<loc>' . htmlspecialchars(site_url($e['loc'])) . '</loc>';
      if(isset($this->config['lastmod']['time_zone_designator'])) {
        $xmlUrl .= '<lastmod>' . $dateSegmets[0] . 'T' . $dateSegmets[1] . $this->config['lastmod']['time_zone_designator'] . '</lastmod>';
      }
      if(isset($this->config['changefreq']) && !empty($this->config['changefreq'])) {
        $xmlUrl .= '<changefreq>' . $this->config['changefreq'] . '</changefreq>';
      }
      if (isset($e['img']) && !empty($e['img'])) {
        $xmlUrl .= '<image:image>';
        $xmlUrl .= '<image:loc>' . $e['img']['loc'] . '</image:loc>';
        if (isset($e['img']['title'])) {
          $xmlUrl .= '<image:title>' . $e['img']['title'] . '</image:title>';
        }
        $xmlUrl .= '</image:image>';
        $includeImage = TRUE;
      }
      if (isset($e['priority']) && !empty($e['priority'])) {
        $xmlUrl .= '<priority>' . $e['priority'] . '</priority>';
      }
      $xmlUrl .= '</url>';
      if ($recordNum >= $this->config['max_url_count'] ||
          $fileSize  >= $this->config['max_file_size']) {
        // if file constraints met, save data to file an start new one
        $xml = self::SITEMAP_XML_HEADER . $xmlUrls . self::SITEMAP_XML_FOOTER;
        $filesNames[] = $this->saveEntitySitemap($xml, $entityName, $fileNum);
        $recordNum = 1;
        $fileNum++;
        $xmlUrls = $xmlUrl;
        $fileSize = $baseSize;
      } else {
        // concat new record to list
        $xmlUrls .= $xmlUrl;
        $fileSize += strlen($xmlUrl);
        $recordNum++;
      }
    }
    // save remainng records
    $sitemapXmlHeader = self::SITEMAP_XML_HEADER;
    if ($includeImage == TRUE) {
      $sitemapXmlHeader = self::SITEMAP_IMG_XML_HEADER;
    }
    $xml = $sitemapXmlHeader . $xmlUrls . self::SITEMAP_XML_FOOTER;
    $filesNames[] = $this->saveEntitySitemap($xml, $entityName, $fileNum);

    return $filesNames;
  }

  // ------------------------------ File operations ------------------------------------------

  /**
   * Save entity sitemap. and return it's URL
   * Save location is $children_location/sitemap-$entityName-$number.xml
   * @param $xml - xml string to save
   * @param $entityname - entity name to form file name
   * @param $number - sequence number to form file name
   * @return file's site url
   */
  private function saveEntitySitemap($xml, $entityName, $number) {
    $file_name = $this->config['children_path'] . '/' . "sitemap-$entityName-$number.xml";

    if ( ! write_file($file_name, $xml) ) {
      throw new Exception("cannot open file " . $file_name);
    }
    return site_url($file_name);
  }

  /**
   * Save sitemap index file and return it's URL
   * @param $xml - file contents
   * @return file's site URL
   */
  private function saveSitemapIndex($xml) {
    $file_name = $this->sitemapName . ".xml";
    if ( ! write_file($file_name, $xml) ) {
      throw new Exception("cannot open file " . $file_name);
    }
    return site_url($file_name);
  }

  /**
   * Delete all entities sitemaps
   * Location is $children_location/sitemap-$entityName-$number.xml
   */
  private function deleteAllEntitiesSitemaps () {
    $file_names = scandir($this->config['children_path']);
    $files_name = array_flip($file_names);
    unset($files_name['.'], $files_name['..']);
    foreach ($files_name as $k => $v) {
      unlink($this->config['children_path'] . '/' . $k);
    }
  }

  // ------------------------------------ Misc -----------------------------------------------

  /**
   * Notify search engine for new sitemap.xml
   * Sends GET request, specific to search engine to notify it that sitemap was changed and ust be reread.
   * @param $sitemap - full url of sitemap.xml file on site
   * @param $service - search engine name: 'bing', 'ask' or 'google'
   *
   */
  public function pingSE($sitemap,$service){

    switch ($service) {
      case 'bing':
        $ping = "http://www.bing.com/webmaster/ping.aspx?siteMap=$sitemap";
        break;
      case 'ask':
        $ping = "http://submissions.ask.com/ping?sitemap=$sitemap";
        break;
      case 'google':
        $ping = "http://www.google.com/webmasters/sitemaps/ping?sitemap=$sitemap";
        break;
      default:
        return false;
    }

    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$ping);
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
    $buffer = curl_exec($curl_handle);
    curl_close($curl_handle);

    if (empty($buffer))
    {
      echo "<p>Sorry, submission failed for $service.<p>";
      die();
    }
    else
    {
      echo "<p>Notifying $service successful.</p>";
    }

  }

}