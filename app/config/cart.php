<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Cart config
 */

/*
|--------------------------------------------------------------------------
| Cart item fields
|--------------------------------------------------------------------------
|
| Maps cart item field names with appropriate fields of a product entity
|
| Example:
|
| Let's assume, Product has a column 'name'
| and Product's price is kept in the joined entity 'ProductVariant', like this:
|
|    Product = Array  (
|        [id] => 241
|        [name] => Google Nexus One
|        ...
|        [product_variant] => Array
|            (
|                [id] => 7524
|                [price] => 5000.00
|                ...
|            )
|    )
|
|	Then we should map it in this way:
| 
| $config['cart']['fields']['title'] = 'name';
| $config['cart']['fields']['price'] = 'product_variant.price';
|
*/

$config['cart']['fields']['id'] = 'id';
$config['cart']['fields']['price'] = 'price';
$config['cart']['fields']['title'] = 'mis_name';
$config['cart']['fields']['quantity'] = 'quantity';
$config['cart']['fields']['type'] = 'type';



/*
|--------------------------------------------------------------------------
| Unique fields combination 
|--------------------------------------------------------------------------
|
| Which fields present the unique combination ?
|
| If your project has only one product entity, e.g. Product then
| $config['cart']['unique_fields'] = array('id');
| would be okay
|
| in the other case:
| $config['cart']['unique_fields'] = array('id', 'type');  
|  
*/
$config['cart']['unique_fields'] = array('id', 'type');


/*
|--------------------------------------------------------------------------
| Cart item options field
|--------------------------------------------------------------------------
|
| Which product fields should be added to cart item options array 
|
| Example:
| $config['cart']['options'][] = 'price_unit';
| $config['cart']['options'][] = 'description';
|
*/
$config['cart']['options']['product_page_url'] = 'mis_page_url';
$config['cart']['options']['product_image'] = 'mis_image';
$config['cart']['options']['company_page_url'] = 'company.page_url';
$config['cart']['options']['company_name'] = 'company.name';
$config['cart']['options']['company_phone'] = 'company.phone';
$config['cart']['options']['company_address'] = 'company.address';
$config['cart']['options']['company_id'] = 'company.id';
$config['cart']['options']['company_subdomain'] = 'company.subdomain';
$config['cart']['options'][] = 'old_price';


/*
|--------------------------------------------------------------------------
| Multiple price units
|--------------------------------------------------------------------------
|
| Whether cart items can have different price units
|
| WARNING: if this option is set to true, be sure to set the 'price_unit' field in fields section
|
*/
$config['cart']['multiple_price_units'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cart backend storage
|--------------------------------------------------------------------------
|
| Cart may be saved in session or in the database 
| values: 'cookies_db', 'session'
|
*/
$config['cart']['backend'] = 'session';




/*************************************** SESSION *******************************************/

/*
|--------------------------------------------------------------------------
| Cart session key (when using 'session')
|--------------------------------------------------------------------------
|
*/
$config['cart']['session_key'] = 'budget_cart';




/************************************* COOKIES + DB ****************************************/

/*
|--------------------------------------------------------------------------
| Cart entity name (when using 'cookies + DB')
|--------------------------------------------------------------------------
|
*/
$config['cart']['entity_name'] = 'CartEntity';


/*
|--------------------------------------------------------------------------
| Cart id cookies name (when using 'cookies + DB')
|--------------------------------------------------------------------------
|
*/
$config['cart']['cookies_name'] = 'cartid';


/*
|--------------------------------------------------------------------------
| Cart id cookies name (when using 'cookies + DB')
|--------------------------------------------------------------------------
|
*/
$config['cart']['cookies_expire_period'] = 60*60*24*30*12; // 1 year


