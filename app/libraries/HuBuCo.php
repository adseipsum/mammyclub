<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Real Time Email Verification API
 *
 * Verify single email addresses in real time. E.g: when users sign up on your website.
 * Real Time API has a daily limit of 20,000 email verifications.
 * Please contact support if you would like us to increase this limit for you.
 * For cleaning databases programmatically we recommend using Bulk API.
 *
 *
 */


class HuBuCo {

	/*
	 * Verify emails by HuBuCo
	 */
	public function hubucoValidationEmail($email){
		$apiLink = 'https://api.hubuco.com/api/v3/?api=npN49OLRoODSG8AcuqJhOxIYH&email=' . $email;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$apiLink);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$curlResult = curl_exec($ch);
		$curlResult = json_decode($curlResult, true);
		curl_close($ch);
		if ($curlResult['resultcode'] == 1 || $curlResult['resultcode'] == 2 || $curlResult['resultcode'] == 3){
			return true;
		}
		return false;
	}

}