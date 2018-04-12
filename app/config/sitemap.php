<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Sitemap
| -------------------------------------------------------------------------
| This file lets you define "urls" for generating sitemap
|
| $config['sitemap']['entities'][]=Array('name' => 'Article');
*/


/*path to child sitemap files realtively to root_path (relative to site root path) */
$config['sitemap']['children_path'] = 'web/sitemap';

/* protection code for URL parameter */
$config['sitemap']['protection_code'] = 'e0369198392ba57735cafd43d2d7dfd3';

/* maximum url count in site.xml */
$config['sitemap']['max_url_count'] = 50000;
/* maximum file size of site.xml */
$config['sitemap']['max_file_size'] = 10000000;
/* search engine to ping */
$config['sitemap']['search_engine'] = 'google';

/* Entities */
$config['sitemap']['entities'][] = Array('name' => 'Article');
$config['sitemap']['entities'][] = Array('name' => 'PregnancyArticle');
// $config['sitemap']['entities'][] = Array('name' => 'Question');
// $config['sitemap']['entities'][] = Array('name' => 'Product'); This will be separate

/* setting changefreq */
$config['sitemap']['changefreq'] = 'weekly';

/* setting changefreq */
$config['sitemap']['lastmod']['time_zone_designator'] = '+02:00';

/* End of file sitemap.php */