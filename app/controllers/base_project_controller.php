<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/base/base_controller.php';
require_once APPPATH . 'logic/common/ConversionObserver.php';


/**
 * BaseProjectController;
 * @property CI_Session $session
 * @property Fileoperations $fileoperations
 * @property Auth $auth
 */
class Base_Project_Controller extends Base_Controller {

  /** Configs to load. */
  protected $configs = array();


  /** Libraries to load.*/
  protected $libraries = array('common/DoctrineLoader', 'Session', 'Auth');

  /** Helpers to load.*/
  protected $helpers = array('url',
  													 'common/itirra_language',
  													 'common/itirra_resources',
  													 'common/itirra_messages',
                             'common/itirra_text',
                             'common/itirra_ajax',
                             'cookie',
                             'project');

  /** Settings.*/
  protected $settings;

  /** Is logged In.*/
  protected $isLoggedIn = FALSE;

  /** AuthEntity.*/
  protected $authEntity;

  /** Weeks.*/
  protected $weeks;

  /** Country.*/
  protected $country;

  /**
   * Constructor
   */
  public function Base_Project_Controller() {
    parent::Base_Controller();
    if (substr(uri_string(), -1) == '/') {
      $fullUrl = substr(uri_string(), 0 , -1);
      if (SUBDOMAIN === 'shop') {
        $fullUrl = str_replace('/shop', '', $fullUrl);
        redirect(shop_url($fullUrl));
      }
      redirect($fullUrl);
    }

    $this->auth->refresh();
    $this->authEntity = $this->auth->getAuthEntity();
    $this->isLoggedIn = $this->auth->isLoggedIn();

    // Check for login key
    $this->quickLogin();
    $this->layout->set('authEntity', $this->authEntity);
    $this->layout->set('isLoggedIn', $this->isLoggedIn);

    // Check country
    $this->country = ManagerHolder::get('User')->detectCountry();
    $this->layout->set('country', $this->country);

    $this->layout->setLayout('main');
    $this->layout->setModule('main');

    // Check for admin
    $admin = $this->session->userdata('LOGGED_IN_ADMIN_SESSION_KEY');
    $this->layout->set('admin', $admin);

    // Get forms
    $forms = ManagerHolder::get('Form')->getActiveForms();
    $this->layout->set('forms', $forms);

    // Get campaigns
    $this->campaign = ManagerHolder::get('Campaign')->getActiveCampaign($this->country);
    $this->layout->set('campaign', $this->campaign);

    // Page visit tracking
    if($this->isLoggedIn == TRUE) {
      $pageVisitId = ManagerHolder::get('PageVisit')->insertPageVisitEvent($this->authEntity['id']);
      $this->layout->set('pageVisitId', $pageVisitId);

			// _ga parse cookie
	    $cid = array();
	    if (isset($_COOKIE['_ga'])) {
		    list($version,$domainDepth, $cid1, $cid2) =  preg_split('[\.]', $_COOKIE["_ga"],4);
		    $contents = array('version' => $version,
											    'domainDepth' => $domainDepth,
											    'cid' => $cid1.'.'.$cid2);
		    $cid = $contents['cid'];
	    }

	    //check if exist _ga client ID from user
	    $response = TRUE;
	    if (!empty($this->authEntity['client_id_ga'])){
		    $clientIds = unserialize($this->authEntity['client_id_ga']);
			    foreach ($clientIds as $clientId){
				    if ($clientId === $cid){
					    $response = FALSE;
				    }
		      }
	      }
	    $this->layout->set('existUserClientId', $response);
    }

    ManagerHolder::get('PregnancyWeek')->setOrderBy('number ASC');
    $this->weeks = ManagerHolder::get('PregnancyWeek')->getAll('id, name, number');
    $this->layout->set('weeks', $this->weeks);

    $settings = ManagerHolder::get('Settings')->getAllKV();
    $this->settings = $settings;
    $this->layout->set('settings', $settings);
  }

  /**
   * Set headers
   * @param array $entity
   * @param string $pageName
   */
  protected function setHeaders($entity = null, $pageName = null) {
    if ($entity && isset($entity['header'])) {
      $header = $entity['header'];
    } elseif ($pageName) {
      $settings = ManagerHolder::get('Settings')->getAllKV();
      $header = array();
      if (isset($settings[$pageName . '_title'])) {
        $header['title'] = $settings[$pageName . '_title'];
      }
      if (isset($settings[$pageName . '_description'])) {
        $header['description'] = $settings[$pageName . '_description'];
      }
    }

    if(isset($header['title'])){
      $header['title'] = htmlspecialchars($header['title']);
      if (isset($this->settings['common_title_postfix'])) {
        $header['title'] .= htmlspecialchars($this->settings['common_title_postfix']);
      }
    }
    if(isset($header['description']) && !empty($header['description'])){
      $header['description'] = htmlspecialchars($header['description']);
    } elseif(isset($header['title'])){
      $header['description'] = $header['title'];
    }

    if(isset($header)){
      $this->layout->set('header', $header);
    }
  }

  /**
   * createArticleContents
   * @param string $articleContent
   */
  function createArticleContents(&$articleContent, $hideContents = FALSE, $hideGoToTopLinks = FALSE) {
    $pattern = "/<(h2|h3) ?.*>(.*)<\/(h2|h3)>/";
    preg_match_all($pattern, $articleContent, $hMatches);

    $contentsLinks = array();
    if (!empty($hMatches)) {
      $hTags = $hMatches[0];
      $hTagNames = $hMatches[1];
      $hTagContents = $hMatches[2];
      $count = 0;
      foreach ($hTagContents as $hTagContent) {
        if(!empty($hTagContent)) {
          $hTagContent = trim(strip_tags($hTagContent), ' &nbsp;');
        } elseif(!empty($hTags[$count])) { // In cases when there are additional tags inside h2|h3
          $hTagContent = trim(strip_tags($hTags[$count]), ' &nbsp;');
        } else {
          continue;
        }
        if (!$hideContents) {
          if ($hTagNames[$count] == 'h3') {
            $contentsLinks[] = '<a class="ajaxp-exclude link sub-link" href="' . current_url() . '#' . trim(lang_url($hTagContent, null, TRUE), '/') . '"><span>' . trim($hTagContent) . '</span></a>';
          } else {
            $contentsLinks[] = '<a class="ajaxp-exclude link" href="' . current_url() . '#' . trim(lang_url($hTagContent, null, TRUE), '/') . '"><span>' . trim($hTagContent) . '</span></a>';
          }
        }
        $hNewTag = '<' . $hTagNames[$count] . '>' . strip_tags($hTags[$count]) . '</' . $hTagNames[$count] . '>';
        $hNewTag = str_replace('<' . $hTagNames[$count], '<' . $hTagNames[$count] . ' id="' . trim(lang_url($hTagContent, null, TRUE), '/') . '" ', $hNewTag);

        if(strpos($hNewTag, 'class="') !== FALSE) {
          $hNewTag = str_replace('class="', 'class="title-to-top ', $hNewTag);
        } else {
          $hNewTag = str_replace('<' . $hTagNames[$count], '<' . $hTagNames[$count] . ' class="title-to-top" ', $hNewTag);
        }

        preg_match('<' . $hTagNames[$count] . '[^<>]*>', $hNewTag, $matches);
        if(!empty($matches)) {
          $firstPartTag = '<' . $matches[0] . '>';
        }
        $hNewTag = str_replace($firstPartTag, $firstPartTag . '<span class="t-1">', $hNewTag);
        if (!$hideGoToTopLinks) {
          $hNewTag = str_replace('</' . $hTagNames[$count] . '>', '</span><span class="top-link js-go-to-top"></span></' . $hTagNames[$count] . '>', $hNewTag);
        }
        $articleContent = str_replace($hTags[$count], $hNewTag, $articleContent);
        $count++;
      }
    }
    if (!$hideContents) {
      $contentsHtml = '<div class="cont-small-box"><h3>Содержание:<span class="close"> (скрыть)</span></h3><div class="js-contents">'  . implode('', $contentsLinks) . '</div></div>';
      $h2pos = strpos($articleContent, '<h2');
      $h3pos = strpos($articleContent, '<h3');

      if($h2pos !== FALSE) {

        if($h2pos == 0) {
          $articleContent = $contentsHtml . $articleContent;
        } else {
          $articleContent = substr($articleContent, 0, $h2pos - 1) . $contentsHtml . substr($articleContent, $h2pos);
        }

      } elseif($h3pos !== FALSE) {

        if($h3pos == 0) {
          $articleContent = $contentsHtml . $articleContent;
        } else {
          $articleContent = substr($articleContent, 0, $h3pos - 1) . $contentsHtml . substr($articleContent, $h3pos);
        }

      }
    }
  }

  /**
   * Quick login
   * @param string $loginKey
   */
  public function quickLogin() {
    if (isset($_GET[LOGIN_KEY]) && !empty($_GET[LOGIN_KEY])) {
      $user = ManagerHolder::get('User')->getOneWhere(array('login_key' => $_GET[LOGIN_KEY]), 'e.*, auth_info.*');
      if (empty($user)) {
        show_404();
      }
      if ($this->isLoggedIn == FALSE) {
        $this->auth->login($user, FALSE);
      } elseif ($this->authEntity['login_key'] != $_GET[LOGIN_KEY]) {
        $this->auth->logout();
        $this->auth->login($user, FALSE);
      }
      unset($_GET[LOGIN_KEY]);

      $currentUrlWithGet = trim(uri_string(), '/');
      $currentUrlWithGet .= !empty($_GET) ? '?' : '';
      $counter = 1;
      foreach ($_GET as $key => $value) {
        $currentUrlWithGet .= $key . '=' . $value;
        $currentUrlWithGet .= $counter < count($_GET) ? '&' : '';
        $counter++;
      }
      if(SUBDOMAIN == 'shop') {
        $currentUrlWithGet = shop_url($currentUrlWithGet);
      }
      redirect($currentUrlWithGet);
    }
  }

  /**
   * Image from Tiny Mce process
   * @param unknown $entity
   */
  public function imageFromTinyMceProcess(&$entity) {
    preg_match_all('/\<img .+?\/\>/i', $entity, $imgMatches);

    foreach ($imgMatches[0] as $imgM) {
      if (strpos($imgM, 'tinymce') === FALSE) {
        preg_match('/src="([^"]*)"/', $imgM, $srcMatches);
        $replace = '<a class="js-fancybox-image" href="' . str_replace('_medium', '', $srcMatches[1]) . '"><img src="' . $srcMatches[1] . '" /></a>';
        $entity = str_replace($imgM, $replace, $entity);
      }
    }
  }

  /**
   * processAdSlots
   * @param text $content
   * @param array $campaign
   */
  protected function processAdSlots(&$content, $campaign) {
    $ttAdSlots = array('slot_tt1' => '{TT1}',
                       'slot_tt2' => '{TT2}',
                       'slot_tt3' => '{TT3}');
    foreach ($ttAdSlots as $k => $tag) {
      // Check if tag exists in article
      if(strpos($content, $tag) === FALSE) {
        continue;
      }
      // Check if this field exists in campaign - if not - remove this tag from article
      if(!isset($campaign[$k])) {
        $content = str_replace('<p>' . $tag . '</p>', '', $content);
        continue;
      }
      $data = array('banner' => $campaign[$k]);
      $adHtml = $this->layout->render('includes/ad_slots/tt_banner', $data, TRUE);
      $content = str_replace('<p>' . $tag . '</p>', $adHtml, $content);
    }
  }

}