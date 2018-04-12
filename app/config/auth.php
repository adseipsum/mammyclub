<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| General
| -------------------------------------------------------------------
*/
$config['auth']['entity_name'] = 'User';
$config['auth']['entity_session_key'] = 'mammyclub_auth_entity';

// IMPORTANT: LOGIC DEPENDS ON THIS:
$config['auth']['email_confirmation']	= TRUE;
// OR
$config['auth']['phone_confirmation']	= FALSE;
// CANNOT BE "TRUE" AND "TRUE"

$config['auth']['do_login_after_register'] = TRUE;

$config['auth']['generate_password_on_register'] = TRUE;

$config['auth']['save_session_keys_on_logout'] = array('LOGGED_IN_ADMIN_SESSION_KEY');

/*
| -------------------------------------------------------------------
| SMS Configuration
| -------------------------------------------------------------------
*/
$config['auth']['sms_url'] = '';
$config['auth']['sms_data'] = array();

/*
| -------------------------------------------------------------------
| Views
| -------------------------------------------------------------------
*/
$config['auth']['module']	= 'auth';
$config['auth']['layout']	= 'main';
$config['auth']['view_register'] = 'register';
$config['auth']['view_login'] = 'login';
$config['auth']['view_email_confirm'] = 'email_confirm';
$config['auth']['view_forgot_password'] = 'forgot_password';

/*
| -------------------------------------------------------------------
| Redirects
| NOTE: emtpy value = refferer
| -------------------------------------------------------------------
*/
$config['auth']['redirect_after_register'] = '/личный-кабинет';
$config['auth']['redirect_after_login'] = '';
$config['auth']['redirect_after_logout'] = '/';

/*
| -------------------------------------------------------------------
| Urls
| -------------------------------------------------------------------
*/
$config['auth']['url_register'] = 'регистрация';
$config['auth']['url_login'] = 'вход';
$config['auth']['url_email_confirm'] = 'подтверждение-емейла';
$config['auth']['url_email_confirm_process'] = 'подтвердить-емейл';
$config['auth']['url_forgot_password'] = 'забыли-пароль';

/*
| -------------------------------------------------------------------
| AuthAttempts and Captcha
| auth_attempts = null means no captcha
| -------------------------------------------------------------------
*/
$config['auth']['auth_attempts'] = 3;
$config['auth']['captcha_path'] = BASEPATH . 'captcha/';
$config['auth']['captcha_fonts_path'] = $config['auth']['captcha_path'] . 'fonts/texb.ttf';
$config['auth']['captcha_width'] = 100;
$config['auth']['captcha_height'] = 30;
$config['auth']['captcha_font_size'] = '13';
$config['auth']['captcha_grid'] = TRUE;
$config['auth']['captcha_expire'] = 180;
$config['auth']['captcha_case_sensitive'] = TRUE;
$config['auth']['captcha_symbols_count'] = 4;

/*
| -------------------------------------------------------------------
| Remember me
| -------------------------------------------------------------------
*/
$config['auth']['rememberme_checkbox_name'] = "remember_me";
$config['auth']['rememberme_cookie_name'] = "mammyclub_rememberme";
$config['auth']['rememberme_cookie_expire'] = 60*60*24*7*4; // 4 weeks

/*
| -------------------------------------------------------------------
| Registration
| -------------------------------------------------------------------
*/
$config['auth']['register']['fields'] = array("email" => array("required", "maxLength" => 255, "email"),
                                              "name" => array("maxLength" => 255),
                                              "pregnancyweek_id" => array());

/*
| -------------------------------------------------------------------
| Login
| -------------------------------------------------------------------
*/
$config['auth']['login']['fields'] = array("email" => array("required", "maxLength" => 255, "email"),
										                       "password" => array("required", "maxLength" => 255));

/*
| -------------------------------------------------------------------
| Email confirmation
| -------------------------------------------------------------------
*/
$config['auth']['email_confirm']['fields'] = array("activation_key" => array("required", "maxLength" => 255));

/*
| -------------------------------------------------------------------
| Phone confirmation
| -------------------------------------------------------------------
*/
$config['auth']['phone_confirm']['fields'] = array("activation_key" => array("required", "maxLength" => 255));

/*
| -------------------------------------------------------------------
| Forgot password
| -------------------------------------------------------------------
*/
$config['auth']['forgot_password']['fields'] = array("phone" => array("required", "maxLength" => 15));

/*
| -------------------------------------------------------------------
| Logout
| -------------------------------------------------------------------
*/
$config['auth']['save_session_keys_on_logout'][] = 'LOGGED_IN_ADMIN_SESSION_KEY';

/*
| -------------------------------------------------------------------
| Facebook module configuration
| -------------------------------------------------------------------
*/
/* Application id and secret */
if(ENV == 'DEV'){
  $config['auth']['facebook']['app_id'] = '255792891113665';
  $config['auth']['facebook']['app_secret'] = 'cf032b3f0550f82b198e0d09f551bef0';
} elseif(ENV == 'TEST') {
  $config['auth']['facebook']['app_id'] = '249463358409950';
  $config['auth']['facebook']['app_secret'] = 'd3ce2e2e4861adca0cbc3afe3a1e4506';
} elseif(ENV == 'PROD') {
  $config['auth']['facebook']['app_id'] = '202915039793534';
  $config['auth']['facebook']['app_secret'] = '67a9bd8ee8b6a2ea1b0bd05244ff215e';
}
/* list of permissions to request */
$config['auth']['facebook']['req_perms'] = Array('email');
/* list of information fields to request */
$config['auth']['facebook']['req_info'] = Array('first_name', 'last_name', 'email');

/*
| -------------------------------------------------------------------
| Vkontakte module configuration
| -------------------------------------------------------------------
*/
/* Application id and secret */
if(ENV == 'DEV'){
  $config['auth']['vkontakte']['app_id'] = '2437604';
  $config['auth']['vkontakte']['app_secret'] = 'pDK8zUF5h2kTeOu6nIYS';
} elseif(ENV == 'TEST') {
  $config['auth']['vkontakte']['app_id'] = '2441476';
  $config['auth']['vkontakte']['app_secret'] = 'uWRKX46KX93RJYwFHHqW';
} elseif(ENV == 'PROD') {
	$config['auth']['vkontakte']['app_id'] = '2715684';
	$config['auth']['vkontakte']['app_secret'] = '9DP5fNji8yj3elFOeqYb';
}

/*
| -------------------------------------------------------------------
| mail.ru module configuration
| -------------------------------------------------------------------
*/
/* Application id and secret */
if(ENV == 'DEV'){
  $config['auth']['mailru']['app_id'] = '641950';
  $config['auth']['mailru']['app_private_key'] = 'ab29f8cbfac64d3e872ea46b49f4072f';
  $config['auth']['mailru']['app_secret_key'] = '8689fcf1e9ddc51c6d8869f470887282';
} elseif(ENV == 'TEST') {
  $config['auth']['mailru']['app_id'] = '641969';
  $config['auth']['mailru']['app_private_key'] = 'd4f160bb664e5cc89332739dc5ea04d1';
  $config['auth']['mailru']['app_secret_key'] = '8357215991249101d3d866560dbb29d0';
} elseif(ENV == 'PROD') {
  $config['auth']['mailru']['app_id'] = '656149';
  $config['auth']['mailru']['app_private_key'] = 'b593a828c7b419546fe5ad4c9316734d';
  $config['auth']['mailru']['app_secret_key'] = 'faaa9725b8336ee660326f5be7fa49dc';
}

/*
| -------------------------------------------------------------------
| Google(OpenID) module configuration
| -------------------------------------------------------------------
*/
/* Contains user info field mapping from database names to OpenID AX schema. */
//TODO move to authModules/gmailAuth.php and extend with other inforation in case of use
// not only with google, or if google will start to give more info about user.
$config['auth']['gmail']['info_fields_map'] = array(/*'name' => 'http://axschema.org/namePerson',*/
                                                    'first_name' => 'http://axschema.org/namePerson/first',
                                                    'last_name'  => 'http://axschema.org/namePerson/last',
                                                    'email' => 'http://axschema.org/contact/email',
                                                    /*'gender' => 'http://axschema.org/person/gender'*/);

/* list of infrmation fields to request from Google */
$config['auth']['gmail']['info_to_request'] = Array('first_name', 'last_name', 'email');