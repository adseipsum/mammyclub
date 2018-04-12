<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/mandrill/Mandrill.php';
require_once APPPATH . 'logic/common/EmailManager.php';

/**
 * Email Mandrill Manager.
 * @author Andrey Busalov (Itirra - www.itirra.com)
 */
class EmailMandrillManager extends EmailManager {

  /** API key */
  protected $key = 'SPwjyFs_RFp5njUEOnTJ1A';

  /** Debug */
  public $debug = false;

  /** Mandrill instance */
  protected $mandrill;

  /** Async send */
  protected $async = false;

  /** Ip pool */
  protected $ip_pool = null;

  /** Language Library. */
  protected $language;

  /** Tamplates Dir. */
  private $templatesDir = "email";

  /** LayoutFile. */
  protected $layoutFile = "email";

  /** Layout Library. */
  protected $layout;

  /** Base Options
   * https://mandrillapp.com/api/docs/messages.JSON.html
   *  */
  protected $baseOptions = array(
      'from_email' => 'no-reply@mammyclub.com',
      'from_name' => 'Mammyclub',
      'headers' => array('Reply-To' => 'no-reply@mammyclub.com'),
      'important' => false,
      'track_opens' => null,
      'track_clicks' => null,
      'auto_text' => null,
      'auto_html' => null,
      'inline_css' => null,
      'url_strip_qs' => null,
      'preserve_recipients' => null,
      'view_content_link' => null,
      'bcc_address' => null,
      'tracking_domain' => null,
      'signing_domain' => null,
      'return_path_domain' => null,
      'tags' => array(),
      'subaccount' => null,
      'google_analytics_domains' => null,
      'google_analytics_campaign' => null,
      'metadata' => array('website' => 'mammyclub.com'),
      'attachments' => null,
      'images' => null
  );

  /**
   * Options
   */
  protected $options = array();

  /**
   * Constructor.
   */
  public function EmailMandrillManager($lng = null) {
    $this->mandrill = new Mandrill($this->key);
    $this->options = $this->baseOptions;

    $CI =& get_instance();

    $CI->load->library("Language");
    $this->language = &$CI->lang;

    if ($lng) {
      if ($k = array_search('email_lang.php', $this->language->is_loaded)) {
        unset($this->language->is_loaded[$k]);
      }
      $this->language->load("email", $lng);
    } else {
      $this->language->load("email", $CI->config->item('language'));
    }

    $this->layout = new Layout();
    $this->layout->set("lang", $this->language);
    $this->layout->setLayout($this->layoutFile);
    $this->layout->setModule($this->templatesDir);
  }

  /**
   * Add attachment
   * @param $fileName
   * @param $name -
   * @param $type - the MIME type of the attachment
   */
  public function addAttachment($fileName, $type = null, $name = null) {
    $file = file_get_contents($fileName);
    $attachment = array(
        'type' => $type,
        'name' => $name,
        'content' => base64_encode($file)
    );

    if ( is_array($this->options['attachments']) ) {
      $this->options['attachments'][] = $attachment;
    } else {
      $this->options['attachments'] = array($attachment);
    }
  }

  /**
   * Send email.
   *
   * @param string $to
   * @param string $subject
   * @param string $message
   */
  public function send($to, $subject, $message, $from = null, $fromName = null) {
    $msg = array(
    	'html' => $message,
      'subject' => $subject,
      'to' => array(
        array(
          'email' => $to,
          'type' => 'to'
        )
       )
    );

    if ($from) {
      $msg['from_email'] = $from;
    }

    if ($fromName) {
      $msg['from_name'] = $fromName;
    }

    $this->sendByApi($msg);
  }

  /**
   * Add header
   * @see EmailManager::addHeader()
   */
  public function addHeader($key, $value) {
    $this->options['headers'][$key] = $value;
  }

  /**
   * @param mixed $tag
   */
  public function setTag($tag) {
    if (is_array($tag)) {
      $this->options['tags'] = $tag;
    } else {
      $this->options['tags'] = array($tag);
    }
  }

  /**
   * @param array $metadata
   */
  public function setMetadata($metadata) {
    if (is_array($this->options['metadata'])) {
      $this->options['metadata'] = array_merge($this->options['metadata'], $metadata);
    } else {
      $this->options['metadata'] = $metadata;
    }
  }

  /**
   * Set reply to
   * @param $email
   */
  public function setReplyTo($email) {
    $this->options['headers']['Reply-To'] = $email;
  }

  /**
   * Cancel scheduled email
   * @param string $id
   * @return boolean
   */
  public function cancelScheduled($id) {
    try {
      $result = $this->mandrill->messages->cancelScheduled($id);
      return TRUE;
    } catch(Mandrill_Error $e) {
      return FALSE;
    }
  }

  /**
   * Send by Mandrill API
   * @param array $msg
   * @param datetime $send_at
   * @return array
   */
  protected function sendByApi($msg, $send_at = null) {
    $msg = array_merge($this->options, $msg);

    try {
      $result = $this->mandrill->messages->send($msg, $this->async, $this->ip_pool, $send_at);
    } catch (Mandrill_HttpError $e) {
      $this->clear();
      log_message('error', "Http Mandrill error " . $e->getMessage());
      return FALSE;
    } catch (Mandrill_Error $e) {
      $this->clear();
      log_message('error', "Mandrill error" . $e->getMessage());
      return FALSE;
    }
    $this->clear();

    return $result;
  }

  /**
   * Set layout file
   * @see EmailManager::setLayoutFile()
   */
  public function setLayoutFile($file) {
    $this->layoutFile = $file;
  }

  /**
   * Clear
   */
  private function clear() {
    $this->options = $this->baseOptions;
  }


  /**
   * Get html of email
   * @param string $id
   * @return boolean
   */
  public function getContent($id) {
    $result = array();
    try {
      $result = $this->mandrill->messages->content($id);
    } catch(Mandrill_Error $e) {
      log_message('error', '[EmailMandrillManager -> getContent] - Exception' . $e->getMessage());
    }
    return $result;
  }

}
