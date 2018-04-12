<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Application contants
| example: define('DOCTRINE_DATE_FORMAT', 'Y-m-d H:i:s');
|--------------------------------------------------------------------------
*/
$config = array();
define('DOCTRINE_DATE_FORMAT', 'Y-m-d H:i:s');
define('DOCTRINE_DUPLICATE_ENTRY_EXCEPTION_CODE', 23000);

// Phone format regexp for form validation
define('PHONE_FORMAT', '/^\(\d\d\d\) *\d\d\d-\d\d-\d\d$/');

// LIMITS
define('META_DESCRIPTION_TRUNCATE_LENGTH', 150);

// TRIGGERED BROADCAST
define('TRIGGERED_BROADCAST_WELCOME', 'welcome_email');
define('TRIGGERED_BROADCAST_WELCOME_FACEBOOK', 'welcome_email_facebook_leads');
define('TRIGGERED_BROADCAST_AFTER_CONFIRM', 'after_email_confirm');

// RETURNING BROADCASTS
define('RETURNING_BROADCAST_FIRST_ID', 1);
define('RETURNING_BROADCAST_SECOND_ID', 2);
define('RETURNING_BROADCAST_FIRST', 'returning_first_broadcast');
define('RETURNING_BROADCAST_SECOND', 'returning_second_broadcast');

// TY BROADCAST
define('TY_BROADCAST', 'ty_broadcast');
define('ORDER_BROADCAST', 'order_broadcast');
define('ORDER_CONFIRMED_BROADCAST', 'order_confirmed_broadcast');

// SITEORDER STATUSES
define('SITEORDER_STATUS_NEW', 'new');
define('SITEORDER_STATUS_DELIVERED', 'complete');
define('SITEORDER_STATUS_SHIPPED', 'delivering');
define('SITEORDER_STATUS_WAIT', 'payment_pending');
define('SITEORDER_STATUS_CONFIRMED_SUPPLIER', 'confirmed_supplier');
define('SITEORDER_STATUS_CONFIRMED_STOCK', 'confirmed_stock');
define('SITEORDER_STATUS_CANCELED', 'canceled');
define('SITEORDER_STATUS_RETURNED', 'returned');
define('SITEORDER_STATUS_CLIENT_CONFIRMED', 'client-confirmed');

if (ENV == 'DEV') {
  define('SUPPLIER_REQUEST_STATUS_SHIPPED_ID', 1);
} else {
  define('SUPPLIER_REQUEST_STATUS_SHIPPED_ID', 3);
}

if (ENV == 'DEV') {
  define('SUPPLIER_REQUEST_STATUS_DELIVERED_TO_POST_ID', 1);
} else {
  define('SUPPLIER_REQUEST_STATUS_DELIVERED_TO_POST_ID', 5);
}


// SMS TEMPLATES
define('SMS_TEMPLATE_TTN', 'ttn');
define('SMS_TEMPLATE_PAYMENT', 'payment');

// FORMS
define('FORM_FULLSCREEN', 'full_screen');
define('FORM_STATIC_SUBSCRIBE', 'static_subscribe');
define('FORM_SUBSCRIBE', 'subscribe');
define('FORM_SHARE', 'share');

/** $commentsPerPage  */
// COMMENTS PER PAGE
define('COMMENTS_SHOP_PER_PAGE', 10);

// COOKIE NAMES
define('COOKIE_FORM_FULLSCREEN_CLOSED', 'mammyclub_fullscreen_form_closed');

// STORE
if (ENV == 'DEV') {
  define('ZAMMLER_STORE_ID', 2);
} else {
  define('ZAMMLER_STORE_ID', 5);
}

// STORE
if (ENV == 'DEV') {
  define('MC_STORE_ID', 4);
} elseif (ENV == 'TEST') {
  define('MC_STORE_ID', 103);
} else {
  define('MC_STORE_ID', 163);
}

// BROADCAST APP
define('BROADCAST_APP_PIPELINE_ENABLED', 1);
define('BROADCAST_APP_SLUG_PREGNANCY_WEEK', 'pregnancy_week');
define('BROADCAST_APP_SLUG_PODUCT', 'product');
define('BROADCAST_APP_SLUG_REC_PROD', 'recommended_products');
define('BROADCAST_APP_SLUG_USEFUL_TIPS', 'useful_tips');
define('BROADCAST_APP_SLUG_FYB', 'first_year');
define('BROADCAST_APP_TYPE_RETURNING', 'returning');
