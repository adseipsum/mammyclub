<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Share scripts configuration
|--------------------------------------------------------------------------
if(ENV == 'PROD' || ENV == 'TEST') {
  $config['share']['facebook'] = true;
  $config['share']['google'] = true;
  $config['share']['linkedin'] = false;
  $config['share']['twitter'] = true;
  $config['share']['vkontakte'] = true;
  $config['share']['odnoklassniki'] = true;
} else {
  $config['share']['facebook'] = false;
  $config['share']['google'] = false;
  $config['share']['linkedin'] = false;
  $config['share']['twitter'] = false;
  $config['share']['vkontakte'] = false;
  $config['share']['odnoklassniki'] = false;  
}
*/


/*
|--------------------------------------------------------------------------
| Misc scripts configuration
|--------------------------------------------------------------------------
*/
if(ENV == 'PROD'){
  $config['scripts']['google_analytics'] = true;
  $config['scripts']['yandex_metrika'] = true;
} else {
  $config['scripts']['google_analytics'] = false;
  $config['scripts']['yandex_metrika'] = false;
}