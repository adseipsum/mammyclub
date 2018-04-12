<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved
| routes must come before any wildcard or regular expression routes.
|
*/

//======================= ROUTING CONSTANTS ======================

define('ADMIN_BASE_ROUTE', 'madmin');

// URL Prefixes
define('URL_PREFIX_ARTICLE_LIST', 'статьи');
define('URL_PREFIX_ARTICLE_VIEW', 'статья');

define('PREGNANCY_WEEK_PAGE_ROUTE', 'беременность-по-неделям');

$route['find'] = "main_controller/find";
$route['поиск'] = "search_controller/index";
$route['поиск/(страница\d+)'] = "search_controller/index";

if(ENV == 'TEST') {

  $route['shop/find'] = "main_controller/find";
  $route['shop/поиск'] = "search_controller/index";
  $route['поиск/(страница\d+)'] = "search_controller/index";

  // CART -----------------------------------------
  $route['shop/добавить-в-корзину/(.*)'] = "cart_controller/add/$1";
  $route['shop/обновить-в-корзинe/(.*)'] = "cart_controller/update/$1";
  $route['shop/удалить-из-корзины/(.*)'] = "cart_controller/remove/$1";
  $route['shop/удалить-товар-из-корзины/(.*)'] = "cart_controller/remove_item/$1";
  $route['shop/корзина'] = "cart_controller/view";

  // SHOP -----------------------------------------
  $route['shop/liqpay-checkout/(.*)'] = "shop_controller/liqpay_checkout_process/$1";
  $route['shop/liqpay-checkout-gate'] = "shop_controller/liqpay_checkout_gate";

  $route['shop/get_warehouse_numbers_ajax'] = "shop_controller/get_warehouse_numbers_ajax";
  $route['shop/get_address_ajax'] = "shop_controller/get_address_ajax";

  $route['shop'] = "shop_controller/index";
  $route['shop/продукт/(.*)'] = "shop_controller/product";
  $route['shop/аджакс/пересчитать'] = "cart_controller/ajax_product_recount";
  $route['shop/аджакс/сохранить-параметры'] = "cart_controller/ajax_save_params";
  $route['shop/аджакс/информация-о-производителе/(.*)'] = "shop_controller/ajax_brand_info/$1";
  $route['shop/аджакс/(.*)'] = "shop_controller/ajax_info/$1";
  $route['shop/процесс-оформления'] = "shop_controller/checkout_process";
  $route['shop/оформить-заказ'] = "shop_controller/checkout";
  $route['shop/sale(.*)'] = "shop_controller/sale_category";
  $route['shop/(.*)'] = "shop_controller/category";
  $route['shop/процесс-оплаты/(.*)'] = "shop_controller/process_payment/$1";
  $route['shop/спасибо-за-заказ'] = "shop_controller/ty";
  $route['shop/liqpay-payment-process'] = "shop_controller/liqpay_payment_process";

  $route['shop/аджакс-догрузка-комментариев'] = "ajax_controller/comments_shop_ajax_load";

} else if (SUBDOMAIN === 'shop') {

  $route['liqpay-checkout/(.*)'] = "shop_controller/liqpay_checkout_process/$1";
  $route['liqpay-checkout-gate'] = "shop_controller/liqpay_checkout_gate";

  // CART -----------------------------------------
  $route['добавить-в-корзину/(.*)'] = "cart_controller/add/$1";
  $route['обновить-в-корзинe/(.*)'] = "cart_controller/update/$1";
  $route['удалить-из-корзины/(.*)'] = "cart_controller/remove/$1";
  $route['удалить-товар-из-корзины/(.*)'] = "cart_controller/remove_item/$1";
  $route['корзина'] = "cart_controller/view";

  // SHOP -----------------------------------------
  $route['default_controller'] = "shop_controller/index";
  $route['аджакс-догрузка-комментариев'] = "ajax_controller/comments_shop_ajax_load";

  $route['продукт/(.*)'] = "shop_controller/product";
  $route['аджакс/пересчитать'] = "cart_controller/ajax_product_recount";
  $route['аджакс/сохранить-параметры'] = "cart_controller/ajax_save_params";
  $route['аджакс/информация-о-производителе/(.*)'] = "shop_controller/ajax_brand_info/$1";
  $route['аджакс/регион-не-поддерживается'] = "shop_controller/ajax_region_not_supported";
  $route['аджакс/доставка-гарантия-оплата'] = "shop_controller/ajax_shipping_guarantee_payment";
  $route['аджакс/(.*)'] = "shop_controller/ajax_info/$1";
  //   $route['get_select2_np_cities_ajax'] = "shop_controller/get_select2_np_cities_ajax";
  $route['get_warehouse_numbers_ajax'] = "shop_controller/get_warehouse_numbers_ajax";
  $route['get_address_ajax'] = "shop_controller/get_address_ajax";

  $route['процесс-оформления'] = "shop_controller/checkout_process";
  $route['оформить-заказ'] = "shop_controller/checkout";
  $route['sale/(страница\d+)'] = "shop_controller/sale_category";
  $route['(.*)/(страница\d+)'] = "shop_controller/category";
  $route['sale(.*)'] = "shop_controller/sale_category";
  $route['(.*)'] = "shop_controller/category";
  $route['процесс-оплаты/(.*)'] = "shop_controller/process_payment/$1";
  $route['спасибо-за-заказ'] = "shop_controller/ty";
  $route['liqpay-payment-process'] = "shop_controller/liqpay_payment_process";

  // Analytics employee url
  $route['analytics-employee-secret-page'] = "shop_controller/analytics_employee";
}

if (SUBDOMAIN === null || SUBDOMAIN === 'www') {

  $route['default_controller'] = "main_controller";

  $route['find'] = "main_controller/find";

  $route[URL_PREFIX_ARTICLE_LIST] = "article_controller/index";

  $route['связаться-с-нами'] = "page_controller/contact_us";
  $route['процесс-связи'] = "page_controller/contact_us_process";

  $route['документация'] = "pregnancy_week_controller/pregnancy_article_list";

  $route['загрузить-изображение'] = "comment_controller/upload_image_ajax";
  $route['добавить-комментарий'] = "comment_controller/add_comment";
  $route[URL_PREFIX_ARTICLE_LIST . '/(.*)'] = "article_controller/category";
  $route[URL_PREFIX_ARTICLE_VIEW . '/(.*)'] = "article_controller/article";
  $route['распечатать-статью'] = "article_controller/print_article";

  $route['задать-вопрос'] = "question_controller/add_question";
  $route['добавление-вопроса'] = "question_controller/add_question_process";
  $route['консультации'] = "question_controller/index";
  $route['консультация/(.*)'] = "question_controller/question";

  $route['отписаться-от-товарной-рассылки'] = "shop_controller/unsubscribe_process";
  $route['отписка-от-товарной-рассылки'] = "shop_controller/unsubscribe";
  $route['востановить-подписку-на-товарную-рассылку'] = "shop_controller/resubscribe_process";
  $route['причина-отписки'] = "shop_controller/unsubscribe_reason_process";

  $route['аджакс-догрузка/(.*)'] = "ajax_controller/ajax_load";
  $route['товар-добавлен-в-корзину/(.*)'] = "shop_controller/ajax_show_new_product_in_cart_pop_up/$1";


  // PRIVATE AREA -----------------------------------------
  $route['личный-кабинет'] = "private_area_controller/index";
  $route['личный-кабинет/мои-вопросы'] = "private_area_controller/questions";
  $route['личный-кабинет/прочитанные-статьи'] = "private_area_controller/articles";
  $route['личный-кабинет/редактирование-информации'] = "private_area_controller/edit_profile";
  $route['личный-кабинет/просмотренные-товары'] = "private_area_controller/recently_viewed_products";
  $route['личный-кабинет/беременность-по-неделям'] = "private_area_controller/pregnancy_week";
  $route['личный-кабинет/мой-малыш'] = "private_area_controller/first_year";
  $route['процесс-смены-информации'] = "private_area_controller/edit_info_process";
  $route['процесс-смены-пароля'] = "private_area_controller/change_password_process";
  $route['процесс-смены-рассылок'] = "private_area_controller/change_newsletters_process";
  $route['процесс-смены-доставки-рассылок'] = "private_area_controller/change_broadcast_delivery_process";
  $route['процесс-смены-аватарки/(.*)'] = "private_area_controller/change_avatar_process/$1";
  $route['подписаться-на-рассылку'] = "private_area_controller/subscribtion_process";

  $route['m-cabinet'] = "main_controller/m_cabinet";

  $route[PREGNANCY_WEEK_PAGE_ROUTE] = "pregnancy_week_controller";
  $route['беременность-по-неделям/пример-статьи/(.*)'] = "pregnancy_week_controller/ajax_article_example/$1";
  $route['беременность-по-неделям/(.*)'] = "pregnancy_week_controller/pregnancy_week_article";
  $route['беременность-по-неделям/добавить-отзыв'] = "pregnancy_week_controller/add_review_process";
  $route['беременность-по-неделям/подписаться-на-рассылку'] = "pregnancy_week_controller/newsletters_subscribe_process";
  $route['статья-выслана-вам-на-почту'] = "pregnancy_week_controller/email_was_sent";
  $route['аджакс/подписаться-на-неделю-беременности'] = "pregnancy_week_controller/ajax_subscribe_pregnancy_week";

  // AUTH -----------------------------------------
  $route['регистрация'] = "auth_controller/register";
  $route['процесс-регистрации'] = "auth_controller/register_process";
  $route['подтверждение-емейла'] = "auth_controller/email_confirm";
  $route['переслать-подтверждение-емейла'] = "auth_controller/resend_email_confirm";
  $route['подтвердить-емейл/(.*)'] = "auth_controller/email_confirm_process/$1";
  /*$route['не-правильный-емейл'] = "auth_controller/wrong_email";*/
  $route['забыли-пароль'] = "auth_controller/forgot_password";
  $route['востановление-пароля'] = "auth_controller/forgot_password_process";
  $route['вход'] = "auth_controller/login";
  $route['входим'] = "auth_controller/login_process";
  $route['выход'] = "auth_controller/logout";

  $route['подтвердите-емейл'] = "auth_controller/ajax_email_confirm_message";
  $route['емейл-подтвержден'] = "auth_controller/confirmed_email_page";
  $route['подтверждение-емейла-и-статья'] = "auth_controller/email_confirm_with_pregnancy_week";
  $route['подтвердите-емейл-и-вопрос'] = "auth_controller/email_confirm_new_question";

  // CRON -----------------------------------------
  $route['week-recount/(.*)'] = "cron_controller/pregnancy_week_recount/$1";
  $route['process_mbr_html/(.*)'] = "cron_controller/process_mbr_html/$1";
  $route['showcase_report/(.*)'] = "cron_controller/showcase_report/$1";
  $route['np_get_warehouse_list/(.*)'] = "cron_controller/np_get_warehouse_list/$1";
  $route['siteorder_report/(.*)'] = "cron_controller/siteorder_report/$1";
  $route['remarketing_feed_export/(.*)'] = "cron_controller/remarketing_feed_export/$1";
  // Temporary solution
  $route['google_feed_export/(.*)'] = "cron_controller/remarketingGoogleFeedExport/$1";
  $route['facebook-feed-export/(.*)'] = "cron_controller/remarketingFacebookFeedExport/$1";

  $route['shadow_pinger/(.*)'] = "cron_controller/shadow_pinger/$1";
  $route['content_links_checker/(.*)'] = "cron_controller/content_links_checker";
  $route['resend_email/(.*)'] = "cron_controller/resendEmailConfirmation/$1";

  // Crm Sync
  $route['crm_sync/run_periodic_task/(.*)'] = "crm_sync_controller/run_periodic_task";
  $route['crm_sync/update_zammler_inventory/(.*)'] = "crm_sync_controller/update_zammler_inventory";
  $route['crm_sync/order_sync/(.*)'] = "crm_sync_controller/order_sync";


  // Task schedule
  $route['task_schedule/run/(.*)'] = "task_schedule_controller/run";

  // Ajax save inv channel
  $route['ajax/save_inv_channel'] = "ajax_controller/save_inv_channel";
  $route['ajax/save_ajax_client_id_ga'] = "ajax_controller/save_ajax_client_id_ga";

  // BROADCASTS -------------------------------------------
  // Recommended product broadcast
  define('RPB_CONTROLLER_PATH', 'project_broadcasts/recommended_products_broadcast_controller');

  define('RECOMMENDED_PRODUCTS_BROADCAST_SUBSCRIBE_PROCESS', 'подписаться-на-рассылку-полезные-покупки-для-беременных');
  define('RECOMMENDED_PRODUCTS_BROADCAST_SUBSCRIBE_PAGE', 'подписка-на-рассылку-полезные-покупки-для-беременных');
  define('RECOMMENDED_PRODUCTS_BROADCAST_UNSUBSCRIBE_PROCESS', 'отписаться-от-рассылки-полезные-покупки-для-беременных');
  define('RECOMMENDED_PRODUCTS_BROADCAST_UNSUBSCRIBE_PAGE', 'отписка-от-рассылки-полезные-покупки-для-беременных');
  define('RECOMMENDED_PRODUCTS_BROADCAST_UNSUBSCRIBE_REASON_PROCESS', 'причина-отписки-от-рассылки-полезные-покупки-для-беременных');
  define('RECOMMENDED_PRODUCTS_BROADCAST_RESUBSCRIBE_PROCESS', 'восстановить-подписку-к-рассылке-полезные-покупки-для-беременных');
  define('RECOMMENDED_PRODUCTS_BROADCAST_RESEND_LETTER', 'послать-еще-раз-письмо-из-рассылки-полезные-покупки-для-беременных');

  $route[RECOMMENDED_PRODUCTS_BROADCAST_SUBSCRIBE_PROCESS] = RPB_CONTROLLER_PATH . "/broadcast_subscribe_process";
  $route[RECOMMENDED_PRODUCTS_BROADCAST_SUBSCRIBE_PAGE] = RPB_CONTROLLER_PATH. "/broadcast_subscribe_page";
  $route[RECOMMENDED_PRODUCTS_BROADCAST_UNSUBSCRIBE_PROCESS] = RPB_CONTROLLER_PATH. "/unsubscribe_process";
  $route[RECOMMENDED_PRODUCTS_BROADCAST_UNSUBSCRIBE_PAGE] = RPB_CONTROLLER_PATH . '/unsubscribe';
  $route[RECOMMENDED_PRODUCTS_BROADCAST_UNSUBSCRIBE_REASON_PROCESS] = RPB_CONTROLLER_PATH . '/unsubscribe_reason_process';
  $route[RECOMMENDED_PRODUCTS_BROADCAST_RESUBSCRIBE_PROCESS] = RPB_CONTROLLER_PATH . '/resubscribe_process';
  $route[RECOMMENDED_PRODUCTS_BROADCAST_RESEND_LETTER] = RPB_CONTROLLER_PATH . '/resend_single_letter_from_broadcast';

  // Pregnancy week broadcast
  $route['успешная-подписка'] = "pregnancy_week_controller/subscribe_success_page";
  $route['отписаться-от-рассылки'] = "pregnancy_week_controller/unsubscribe_process";
  $route['отписка'] = "pregnancy_week_controller/unsubscribe";
  $route['востановить-подписку-на-рассылку'] = "pregnancy_week_controller/resubscribe_process";
  $route['причина-отписки'] = "pregnancy_week_controller/unsubscribe_reason_process";

  // First year broadcast
  define('FYB_CONTROLLER_PATH', 'project_broadcasts/first_year_broadcast_controller');
  define('FIRST_YEAR_BROADCAST_SUBSCRIBE_PROCESS', 'первый-год-жизни/подписаться-на-рассылку');
  define('FIRST_YEAR_BROADCAST_SUBSCRIBE_SUCCESS_PAGE', 'первый-год-жизни/успешная-подписка');
  define('FIRST_YEAR_BROADCAST_UNSUBSCRIBE_PROCESS', 'отписаться-от-рассылки-первый-год-жизни');
  define('FIRST_YEAR_BROADCAST_UNSUBSCRIBE_PAGE', 'отписка-от-рассылки-первый-год-жизни');
  define('FIRST_YEAR_BROADCAST_UNSUBSCRIBE_REASON_PROCESS', 'причина-отписки-от-рассылки-первый-год-жизни');
  define('FIRST_YEAR_BROADCAST_RESUBSCRIBE_PROCESS', 'восстановить-подписку-к-рассылке-первый-год-жизни');
  define('FIRST_YEAR_BROADCAST_RESEND_PROCESS', 'послать-еще-раз-письма-из-рассылки-первый-год-жизни');

  $route[FIRST_YEAR_BROADCAST_SUBSCRIBE_PROCESS] = FYB_CONTROLLER_PATH . "/broadcast_subscribe_process";
  $route[FIRST_YEAR_BROADCAST_SUBSCRIBE_SUCCESS_PAGE . '/(.*)'] = FYB_CONTROLLER_PATH . "/subscribe_success_page/$1";
  $route[FIRST_YEAR_BROADCAST_UNSUBSCRIBE_PROCESS] = FYB_CONTROLLER_PATH . "/unsubscribe_process";
  $route[FIRST_YEAR_BROADCAST_UNSUBSCRIBE_PAGE] = FYB_CONTROLLER_PATH . "/unsubscribe";
  $route[FIRST_YEAR_BROADCAST_UNSUBSCRIBE_REASON_PROCESS] = FYB_CONTROLLER_PATH . "/unsubscribe_reason_process";
  $route[FIRST_YEAR_BROADCAST_RESUBSCRIBE_PROCESS] = FYB_CONTROLLER_PATH . "/resubscribe_process";
  $route[FIRST_YEAR_BROADCAST_RESEND_PROCESS . '/(.*)'] = FYB_CONTROLLER_PATH . "/resend_broadcast/$1";
  $route['send-first-year-broadcast/(.*)'] = FYB_CONTROLLER_PATH . "/send_broadcast/$1";

  // Useful tips broadcast
  define('UTB_CONTROLLER_PATH', 'project_broadcasts/useful_tips_broadcast_controller');
  define('USEFUL_TIPS_BROADCAST_UNSUBSCRIBE_PROCESS', 'отписаться-от-рассылки-полезные-советы');
  define('USEFUL_TIPS_BROADCAST_UNSUBSCRIBE_PAGE', 'отписка-от-рассылки-полезные-советы');
  define('USEFUL_TIPS_BROADCAST_UNSUBSCRIBE_REASON_PROCESS', 'причина-отписки-от-рассылки-полезные-советы');
  define('USEFUL_TIPS_BROADCAST_RESUBSCRIBE_PROCESS', 'восстановить-подписку-к-рассылке-полезные-советы');

  $route[USEFUL_TIPS_BROADCAST_UNSUBSCRIBE_PROCESS] = UTB_CONTROLLER_PATH . "/unsubscribe_process";
  $route[USEFUL_TIPS_BROADCAST_UNSUBSCRIBE_PAGE] = UTB_CONTROLLER_PATH . "/unsubscribe";
  $route[USEFUL_TIPS_BROADCAST_UNSUBSCRIBE_REASON_PROCESS] = UTB_CONTROLLER_PATH . "/unsubscribe_reason_process";
  $route[USEFUL_TIPS_BROADCAST_RESUBSCRIBE_PROCESS] = UTB_CONTROLLER_PATH . "/resubscribe_process";
  $route['send-useful-tips-broadcast/(.*)'] = UTB_CONTROLLER_PATH . "/send_broadcast/$1";

  // Returning broadcast
  define('RB_CONTROLLER_PATH', 'project_broadcasts/returning_broadcast_controller');
  define('RETURNING_SUCCESS_PAGE', 'с-возвращением');
  $route['send-returning_broadcast/(.*)'] = RB_CONTROLLER_PATH . "/send_broadcast/$1";
  $route[RETURNING_SUCCESS_PAGE] = RB_CONTROLLER_PATH . "/success_page";

  // AUTO BROADCAST -------------------------------
  $route['send-broadcast/(.*)'] = "auto_broadcast_controller/send_broadcast/$1";
  $route['send-product-broadcast/(.*)'] = "auto_broadcast_controller/send_product_broadcast/$1";

  $route['send-invite-to-recommended-products-broadcast/(.*)'] = RPB_CONTROLLER_PATH . "/send_invite_broadcast/$1";
  $route['send-recommended-products-broadcast/(.*)'] = RPB_CONTROLLER_PATH . "/send_broadcast/$1";

  $route['mandrill-webhook'] = "webhook_controller/mandrill_trigger_webhook";
  $route['facebook-webhook'] = "facebook_webhook_controller/index";

  //------ Sitemap --------
  $route['generate_sitemap/(.*)'] = "sitemap_controller/generate_sitemap/$1";

  $route['secret_dev(.*)'] = "dev_controller$1";

  //------ ADMIN --------
  $route[ADMIN_BASE_ROUTE . '/broadcast'] = "admin/common/xadmin_email_broadcast/index";
  $route[ADMIN_BASE_ROUTE . '/broadcast/(страница\d+)'] = "admin/common/xadmin_email_broadcast/index/$1";
  $route[ADMIN_BASE_ROUTE . '/broadcast/(.*)'] = "admin/common/xadmin_email_broadcast/$1";
  $route[ADMIN_BASE_ROUTE . '/login'] = "admin/common/xadmin/login";
  $route[ADMIN_BASE_ROUTE . '/forgot_password'] = "admin/common/xadmin/forgot_password";
  $route[ADMIN_BASE_ROUTE . '/logout'] = "admin/common/xadmin/logout";
  $route[ADMIN_BASE_ROUTE . '/change_info'] = "admin/common/xadmin/change_info";
  $route[ADMIN_BASE_ROUTE . '/admin'] = "admin/common/xadmin_admin/index";
  $route[ADMIN_BASE_ROUTE . '/admin/(.*)'] = "admin/common/xadmin_admin/$1";
  $route[ADMIN_BASE_ROUTE . '/adminlog'] = "admin/common/xadmin_adminlog/index";
  $route[ADMIN_BASE_ROUTE . '/adminlog/(страница\d+)'] = "admin/common/xadmin_adminlog/index/$1";
  $route[ADMIN_BASE_ROUTE . '/adminlog/(.*)'] = "admin/common/xadmin_adminlog/$1";
  $route[ADMIN_BASE_ROUTE . '/settings'] = "admin/common/xadmin_settings/index";
  $route[ADMIN_BASE_ROUTE . '/settings/(страница\d+)'] = "admin/common/xadmin_settings/index/$1";
  $route[ADMIN_BASE_ROUTE . '/settings/(.*)'] = "admin/common/xadmin_settings/$1";
  $route[ADMIN_BASE_ROUTE . '/settingsgroup'] = "admin/common/xadmin_settingsgroup/index";
  $route[ADMIN_BASE_ROUTE . '/settingsgroup/(страница\d+)'] = "admin/common/xadmin_settingsgroup/index/$1";
  $route[ADMIN_BASE_ROUTE . '/settingsgroup/(.*)'] = "admin/common/xadmin_settingsgroup/$1";
  $route[ADMIN_BASE_ROUTE . '/conversion'] = "admin/common/xadmin_conversion/index";
  $route[ADMIN_BASE_ROUTE . '/conversion/(.*)'] = "admin/common/xadmin_conversion/$1";
  $route[ADMIN_BASE_ROUTE . '/conversionevent'] = "admin/common/xadmin_conversionevent/index";
  $route[ADMIN_BASE_ROUTE . '/conversionevent/(.*)'] = "admin/common/xadmin_conversionevent/$1";
  $route[ADMIN_BASE_ROUTE . '/resource'] = "admin/common/xadmin_resource/index";
  $route[ADMIN_BASE_ROUTE . '/resource/(.*)'] = "admin/common/xadmin_resource/$1";
  $route[ADMIN_BASE_ROUTE . '/(.*)/(.*)/(.*)'] = "admin/xadmin_$1/$2/$3";
  $route[ADMIN_BASE_ROUTE . '/(.*)/(страница\d+)'] = "admin/xadmin_$1/index/$2";
  $route[ADMIN_BASE_ROUTE . '/(.*)/(.*)'] = "admin/xadmin_$1/$2";
  $route[ADMIN_BASE_ROUTE . '/(.*)'] = "admin/xadmin_$1/index";
  $route[ADMIN_BASE_ROUTE] = "admin/common/xadmin/index";

  //------- BROADCAST ----------
  define('BROADCAST_LINK_BASE_URL', 'go-to-url');
  define('BROADCAST_READ_CALLBACK_URL', 'email-image');
  define('BROADCAST_UNSUBSCRIBE_URL', 'unsubscribe');

  $route[BROADCAST_LINK_BASE_URL] = "admin/common/xadmin_email_broadcast/broadcast_link_redirect";
  $route[BROADCAST_READ_CALLBACK_URL] = "admin/common/xadmin_email_broadcast/broadcast_read_callback";
  $route[BROADCAST_UNSUBSCRIBE_URL . '/(.*)'] = "admin/common/xadmin_email_broadcast/broadcast_unsubscribe/$1";

  $route['(.*)'] = "page_controller/index";
}

/* End of file routes.php */
/* Location: ./system/application/config/routes.php */