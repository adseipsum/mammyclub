<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'exceptions/common/EmailSendingException.php';

/**
 * Email Manager.
 * @author Alexei Chizhmakov (Itirra - www.itirra.com)
 */
class EmailManager {

  /** Tamplates Dir. */
  private $templatesDir = "email";

  /** LayoutFile. */
  private $layoutFile = "email";

  /** Config. */
  protected $config;

  /** Email Library. */
  protected $email;

  /** Language Library. */
  protected $language;

  /** Layout Library. */
  protected $layout;

  /** Attachments. */
  protected $attachments;

  /** Headers */
  protected $headers;

  /** Bcc */
  protected $bcc;
  
  /** Cc */
  protected $cc;  

  /** ReplyTo */
  protected $replyTo;

  /**
   * Constructor.
   */
  public function EmailManager($lng = null) {
    $CI =& get_instance();

    $CI->load->library("Email");
    $this->email = new CI_Email();

    $CI->load->config("email");
    $this->config = $CI->config->item('email');

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
    $this->email->initialize($this->config['settings']);
  }

  /**
   * Send templated email.
   *
   * @param string $to
   * @param string $template
   * @param mixed $data
   */
  public function sendTemplate($to, $template, $data, $subject = null, $from = null, $fromName = null) {
    if (!$subject) {
      $subject = $this->language->language['email_subject_' . $template];
      if (!is_array($data)) {
        $data = $data->toArray();
      }
      $subject = kprintf($subject, $data);
    }

    $data['subject'] = $subject;
    $this->layout->setArray($data);
    $this->layout->setLayout($this->layoutFile);
    $message = $this->layout->view($template, TRUE, TRUE);
    return $this->send($to, $subject, $message, $from, $fromName);
  }


  /**
   * Send view file email.
   * @param string $to
   * @param string $template
   * @param mixed $data
   */
  public function sendView($to, $viewFile, $data, $subject = null) {
    if (!$subject) {
      $subject = $this->language->language['email_subject_' . $viewFile];
      if (!is_array($data)) {
        $data = $data->toArray();
      }
      $subject = kprintf($subject, $data);
    }
    $data['subject'] = $subject;
    $message = $this->layout->render('includes/email/' . $viewFile, $data, TRUE);
    return $this->send($to, $subject, $message);
  }


  /**
   * Send email.
   *
   * @param string $to
   * @param string $subject
   * @param string $message
   */
  public function send($to, $subject, $message, $from = null, $fromName = null) {
    $this->email->to($to);
    if (!$from) {
      $this->email->from($this->config['from_email'], $this->config['from_name']);
    } else {
      if ($fromName) {
        $this->email->from($from, $fromName);
      } else {
        $this->email->from($from);
      }
    }

    // Encoding
    if (isset($this->config['encode_subject']) && !empty($this->config['encode_subject'])) {
      $subject = @iconv("utf-8", $this->config['encode_subject'], $subject);
    }
    if (isset($this->config['encode_message']) && !empty($this->config['encode_message'])) {
      $message = @iconv("utf-8", $this->config['encode_message'], $message);
    }

    $this->email->subject($subject);
    $this->email->message($message);
    if ($this->config['settings']["mailtype"] == 'html') {
      $this->email->set_alt_message($message);
    }

    if ($this->attachments) {
      foreach ($this->attachments as $attachment) {
        $this->email->attach($attachment);
      }
    }

    // BCC
    if ($this->bcc) {
      $this->email->bcc($this->bcc);
    }
    
    // CC
    if ($this->cc) {
      $this->email->cc($this->cc);
    }    

    if ($this->replyTo) {
      $this->email->reply_to($this->replyTo);
    }

    if($this->headers) {
      foreach($this->headers as $key => $value) {
        $this->email->_set_header($key, $value);
      }
    }

    if (!@$this->email->send()) {
      log_message('error', "Error while sending email: <to> = " . $to . '; <message> = ' . $message);
      $this->email->clear(true);
      if (ENV != 'DEV') {
        throw new EmailSendingException($this->email->print_debugger(), 1);
      }
    }
    $this->email->clear(true);
  }

  /**
   * Set attachment.
   *
   * @param $filePath
   */
  public function setAttachment($filePath) {
    $this->attachments = array($filePath);
  }


  /**
   * addAttachment
   * @param $filePath
   */
  public function addAttachment($filePath) {
    $this->attachments[] = $filePath;
  }


  /**
   * addHeader
   * @param $key
   * @param $value
   */
  public function addHeader($key, $value) {
    $this->headers[$key] = $value;
  }


  /**
   * Set layout file
   * @param string $file
   */
  public function setLayoutFile($file) {
    $this->layoutFile = $file;
  }
  
  /**
   * Set CC
   * @param string $ccEmail
   */
  public function setCc($ccEmail) {
    $this->cc = $ccEmail;
  }  

  /**
   * Set BCC
   * @param string $bccEmail
   */
  public function setBcc($bccEmail) {
    $this->bcc = $bccEmail;
  }

  /**
   * @param mixed $replyTo
   */
  public function setReplyTo($replyTo) {
    $this->replyTo = $replyTo;
  }
}