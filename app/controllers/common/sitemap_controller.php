<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Sitemap controller.
 *
 * Generates sitemap.xml file, subfiles for entites and notifies search engines about update.
 * To be called from cron.
 * @author Itirra - http://itirra.com
 */

/**
 * Format for configuration file config/sitemap.php
 * ----
  // DB entities for sitemap generation.
  // Entity manager must contain function getAllForSitemap(), which returns urls list as entity[i]['loc'] elements
  //example:
  $config['sitemap']['entities'][]=Array('name' => 'Article');
	$config['sitemap']['entities'][]=Array('name' => 'ArticlesCategory');

	//path to child sitemap files realtively to site root path
	$config['sitemap']['children_path'] = 'web/sitemap';
  // Maximum url count in site.xml
  $config['sitemap']['max_url_count'] = 50000;
  // Maximum file size of site.xml
  $config['sitemap']['max_file_size'] = 10000000;
  // Search engine to ping
  $config['sitemap']['search_engine'] = 'google';
	
 * ----
 *
 * Cron job that runs every night at 2:00 and updates sitemap:
 * ----
   0   2    *   *   *   wget -q -O - http://sitename.mew/generate_sitemap/30ad3a3a1d2c7c63102e09e6fe4bb253 >> /tmp/generate_sitemap.log
 
 * ----
 *
 * config/routes.php entry:
 * ----
   // sitemap.xml generator
   $route['generate_sitemap/(.*)'] = "sitemap_controller/generate_sitemap/$1";
   
 * ----
 */
class Sitemap_Controller extends Base_Controller {
  
  /* Security code. Ensures that nobody runs this controller, but cron */
  const PROTECTION_CODE = 'fd749b8e-9a09-49c2-b321-19ba39e55f26';
    

  /**
   * Constructor.
   */
  public function Sitemap_Controller() {
    parent::Base_Controller();
    $this->load->library('common/DoctrineLoader');
    $this->load->library('common/Sitemap');
  }
  
  /**
   * Index.
   */
  public function generate_sitemap($protection_code) {
    $config = $this->config->item('sitemap');
    
    if (isset($config['protection_code'])) {
      if ($protection_code != $config['protection_code']){
        show_404();
      }
    } else if ($protection_code != self::PROTECTION_CODE) {
      show_404();
    }
    
    echo 'Generating sitemap... <br>';
    $this->sitemap->generate();
    echo 'Sitemap generation done <br>';
    
    // Notify search engine
    if(ENV == 'PROD') {
      $this->sitemap->pingSE(site_url('sitemap.xml'), $config['search_engine']);
    }
  }
}