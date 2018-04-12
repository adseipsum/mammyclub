<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . 'google/apiClient.php';
require_once BASEPATH . 'google/contrib/apiAnalyticsService.php';

/**
 * GoogleAnalytics library.
 * To use Google Analytics API.
 * @author Itirra - http://itirra.com
 * @property Session $session
 */
class GoogleAnalytics {
  
  /** Google Analytics Service. */
  private $service;
  
  /** Google Api client. */
  private $client;
  
  /** Session class object. */
  private $session;
  
  /** Website data array. */
  private $website;
    
  
  /**
   * Constructor.
   * @param array $params
   * $params['postAuthCallback' => URL FOR CALLBACK AFTER AUTH]
   */
  public function GoogleAnalytics($params) {
    $this->client = new apiClient();
    $this->client->setApplicationName("Google Analytics PHP Starter Application");
    
    // Visit https://code.google.com/apis/console?api=analytics to generate your
    // client id, client secret, and to register your redirect uri.
    $this->client->setClientId('335029627610.apps.googleusercontent.com');
    $this->client->setClientSecret('kqNRXJFYrKoFcKPnqnRBH0ye');
    $this->client->setRedirectUri($params['postAuthCallback']);
    
    $this->service = new apiAnalyticsService($this->client);
    
//    $this->client->setDeveloperKey('insert_your_developer_key');
    
    $ci = &get_instance();
    $this->session = &$ci->session;
  }
  
  
  public function authenticate($code = null) {
   if ($this->session->userdata('token')) {
     $this->client->setAccessToken($this->session->userdata('token'));
     return TRUE;
   } else {
     if ($code) {
       $this->client->authenticate();
       $token = $this->client->getAccessToken();
       $this->client->setAccessToken($token);
       $this->session->set_userdata('token', $token);
       return TRUE;
     }
   }
   return FALSE;
  }
  
  
  public function getAuthUrl() {
    $authUrl = $this->client->createAuthUrl();
    return $authUrl;
  }
  
  public function logout() {
    $this->session->unset_userdata('token');
  }
  
  
  public function loadWebsiteData($domain) {
    if ($this->session->userdata('website_data')) {
      $this->website = $this->session->userdata('website_data');
      return TRUE;
    } else {
      $managmentAccounts = $this->listManagementAccounts();
      if (!empty($managmentAccounts) && !empty($managmentAccounts['items'])) {
        foreach ($managmentAccounts['items'] as $item) {
          if ($item['kind'] == 'analytics#account') {
            $websitesData = $this->listManagementWebproperties($item['id']);
            foreach ($websitesData['items'] as $witem) {
              if (get_domain($witem['websiteUrl']) == $domain) {
                $websiteProfile = $this->listManagementProfiles($witem['accountId'], $witem['id']);
                if (!empty($websiteProfile) && !empty($websiteProfile['items'])) {
                  $witem['profile'] = $websiteProfile['items'][0];
                  $this->website = $witem;
                  $this->session->set_userdata('website_data', $this->website);
                  return TRUE;
                }
              }
            }
          }
        }
      }
    }
    return FALSE;
  }
  
  public function clearWebsiteDateCache() {
    $this->session->unset_userdata('website_data');
  }

  
  
  private function listManagementProfiles($accountId, $webPropertyId) {
    return $this->service->management_profiles->listManagementProfiles($accountId, $webPropertyId);
  }
  
  private function listManagementAccounts() {
    return $this->service->management_accounts->listManagementAccounts();
  }
  
  
  private function listManagementWebproperties($id) {
    return $this->service->management_webproperties->listManagementWebproperties($id);
  }
  
  
  public function getData($dateFrom, $dateTo, $metrics, $dimensions) {
    if (!empty($this->website)) {
      $result = $this->service->data_ga->get('ga:' . $this->website['profile']['id'], $dateFrom, $dateTo, $metrics, $dimensions);
      return $this->processResult($result);
    } else {
      return FALSE;
    }
  }
  
  private function processResult($apiResult) {
    if (!empty($apiResult)) {
      $result = array();
      $keys = array_keys($apiResult['totalsForAllResults']);
      foreach ($apiResult['totalsForAllResults'] as $k => $v) {
        $result['total'][str_replace('ga:', '', $k)] = $v;
      }
      $result['rows'] = array();
      foreach ($apiResult['rows'] as $resRow) {
        $row = array();
        foreach ($keys as $index => $key) {
          $row[str_replace('ga:', '', $key)] = $resRow[$index + 1];
        }
        $result['rows'][$resRow[0]] = $row;
      }
    } else {
      return FALSE;
    }
    return $result;
  }
  
  public function getWebsiteData() {
    return $this->website;
  }

//
//
//$service = new apiAnalyticsService($client);
//
//if (isset($_GET['code'])) {
//  $client->authenticate();
//  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
//}
//
//if (isset($_SESSION['token'])) {
//  $client->setAccessToken($_SESSION['token']);
//}
//
//if ($client->getAccessToken()) {
//
//  $props = $service->management_webproperties->listManagementWebproperties("17404926");
//  print "<h1>Web Properties</h1><pre>" . print_r($props, true) . "</pre>";
//
//  $props = $service->management_profiles->listManagementProfiles("17404926", 'UA-17404926-1');
//  print "<h1>Web Properties</h1><pre>" . print_r($props, true) . "</pre>";
//
//  $props = $service->data_ga->get('ga:34587929', '2012-02-15', '2012-02-15', 'ga:visits,ga:bounces', array('dimensions' => 'ga:medium'));
//  print "<h1>Web Properties</h1><pre>" . print_r($props, true) . "</pre>";
//
////
////  $accounts = $service->management_accounts->listManagementAccounts();
////  print "<h1>Accounts</h1><pre>" . print_r($accounts, true) . "</pre>";
////
////  $segments = $service->management_segments->listManagementSegments();
////  print "<h1>Segments</h1><pre>" . print_r($segments, true) . "</pre>";
////
////  $goals = $service->management_goals->listManagementGoals("~all", "~all", "~all");
////  print "<h1>Segments</h1><pre>" . print_r($goals, true) . "</pre>";
//
//  $_SESSION['token'] = $client->getAccessToken();
//} else {
//  $authUrl = $client->createAuthUrl();
//  print "<a class='login' href='$authUrl'>Connect Me!</a>";
//}
  
 
  
}