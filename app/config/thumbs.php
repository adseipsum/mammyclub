<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Thumbnail names and sizes
|--------------------------------------------------------------------------
|
| Example:
| $config["thumbs"]["user"]["_tiny"]["width"] = 67;
| $config["thumbs"]["user"]["_tiny"]["height"] = 67;
| $config["thumbs"]["user"]["_small"]["width"] = 80;
| $config["thumbs"]["user"]["_small"]["height"] = 80;
|
*/


// These thumbs will be created for ALL images.
// They are needed for displaing in the Admin-dashboard (image preview and in the "window")
$config['all']['_admin']["width"] = 150;
$config['all']['_admin']["height"] = 100;

$config["thumbs"]["article"]["_medium"]["width"] = 200;
$config["thumbs"]["article"]["_medium"]["height"] = 150;

$config["thumbs"]["article"]["_small"]["width"] = 100;
$config["thumbs"]["article"]["_small"]["height"] = 100;
$config["thumbs"]["article"]["_small"]['smart_crop'] = TRUE;

$config["thumbs"]["articlecategory"]["_medium"]["width"] = 370;
$config["thumbs"]["articlecategory"]["_medium"]["height"] = 370;

$config["thumbs"]["articlecategory"]["_small"]["width"] = 220;
$config["thumbs"]["articlecategory"]["_small"]["height"] = 220;

$config["thumbs"]["articleexample"]["_small"]["width"] = 224;
$config["thumbs"]["articleexample"]["_small"]["height"] = 143;
$config["thumbs"]["articleexample"]["_small"]['smart_crop'] = TRUE;

$config["thumbs"]["articleexample"]["_large"]["width"] = 700;
$config["thumbs"]["articleexample"]["_large"]["height"] = 400;
$config["thumbs"]["articleexample"]["_large"]['smart_crop'] = TRUE;

$config["thumbs"]["user"]["_small"]["width"] = 45;
$config["thumbs"]["user"]["_small"]["height"] = 45;
$config["thumbs"]["user"]["_small"]['smart_crop'] = TRUE;

$config["thumbs"]["defaultavatar"]["_small"]["width"] = 45;
$config["thumbs"]["defaultavatar"]["_small"]["height"] = 45;
$config["thumbs"]["defaultavatar"]["_small"]['smart_crop'] = TRUE;

$config["thumbs"]["defaultavatar"]["_medium"]["width"] = 100;
$config["thumbs"]["defaultavatar"]["_medium"]["height"] = 100;
$config["thumbs"]["defaultavatar"]["_medium"]['smart_crop'] = TRUE;

$config["thumbs"]["team"]["_medium"]["width"] = 105;
$config["thumbs"]["team"]["_medium"]["height"] = 105;
$config["thumbs"]["team"]["_medium"]['smart_crop'] = TRUE;

$config["thumbs"]["product"]["_small"]["width"] = 120;
$config["thumbs"]["product"]["_small"]["height"] = 120;
$config["thumbs"]["product"]["_small"]['smart_crop'] = TRUE;

$config["thumbs"]["product"]["_medium"]["width"] = 248;
$config["thumbs"]["product"]["_medium"]["height"] = 250;
$config["thumbs"]["product"]["_medium"]['smart_crop'] = TRUE;

$config["thumbs"]["product"]["_tiny"]["width"] = 45;
$config["thumbs"]["product"]["_tiny"]["height"] = 45;
$config["thumbs"]["product"]["_tiny"]['smart_crop'] = TRUE;

$config["thumbs"]["product"]["_tinycart"]["width"] = 80;
$config["thumbs"]["product"]["_tinycart"]["height"] = 80;
$config["thumbs"]["product"]["_tinycart"]['smart_crop'] = TRUE;

$config["thumbs"]["comment"]["_medium"]["width"] = 500;
$config["thumbs"]["comment"]["_medium"]["height"] = 300;
$config["thumbs"]["comment"]["_medium"]['smart_crop'] = TRUE;

$config["thumbs"]["productbrand"]["_medium"]["width"] = 150;
$config["thumbs"]["productbrand"]["_medium"]["height"] = 150;
$config["thumbs"]["productbrand"]["_medium"]['smart_crop'] = TRUE;

/***************************/
$config["thumbs"]["product"]["_medium_one"]["width"] = 248;
$config["thumbs"]["product"]["_medium_one"]["height"] = 250;

$config["thumbs"]["product"]["_medium_list"]["width"] = 348;
$config["thumbs"]["product"]["_medium_list"]["height"] = 351;
$config["thumbs"]["product"]["_medium_list"]['smart_crop'] = TRUE;

$config["thumbs"]["product"]["_small_one"]["width"] = 60;
$config["thumbs"]["product"]["_small_one"]["height"] = 60;
$config["thumbs"]["product"]["_small_one"]['smart_crop'] = TRUE;

$config["thumbs"]["product"]["_huge"]["width"] = 500;
$config["thumbs"]["product"]["_huge"]["height"] = 500;
$config["thumbs"]["product"]["_huge"]['smart_crop'] = TRUE;

$config["thumbs"]["productparamimage"]["_huge"]["width"] = 500;
$config["thumbs"]["productparamimage"]["_huge"]["height"] = 500;
$config["thumbs"]["productparamimage"]["_huge"]['smart_crop'] = TRUE;

// hack for parameter group
$config["thumbs"]["parametergroup"]["_tinycart"]["width"] = 80;
$config["thumbs"]["parametergroup"]["_tinycart"]["height"] = 80;
$config["thumbs"]["parametergroup"]["_tinycart"]['smart_crop'] = TRUE;

$config["thumbs"]["parametergroup"]["_small"]["width"] = 120;
$config["thumbs"]["parametergroup"]["_small"]["height"] = 120;
$config["thumbs"]["parametergroup"]["_small"]['smart_crop'] = TRUE;

$config["thumbs"]["parametergroup"]["_small_one"]["width"] = 60;
$config["thumbs"]["parametergroup"]["_small_one"]["height"] = 60;
$config["thumbs"]["parametergroup"]["_small_one"]['smart_crop'] = TRUE;

$config["thumbs"]["parametergroup"]["_huge"]["width"] = 500;
$config["thumbs"]["parametergroup"]["_huge"]["height"] = 500;
$config["thumbs"]["parametergroup"]["_huge"]['smart_crop'] = TRUE;

// $config["thumbs"]["parametergroup"]["_tinycart"]['max_size'] = 80;
// $config["thumbs"]["parametergroup"]["_small"]['max_size'] = 120;
// $config["thumbs"]["parametergroup"]["_huge"]['max_size'] = 500;

