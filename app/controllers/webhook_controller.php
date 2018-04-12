<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Webhook controller.
 * @author Itirra - http://itirra.com
 */
class Webhook_Controller extends Base_Project_Controller {

  /**
   * Constructor.
   */
  public function Webhook_Controller() {
    parent::Base_Project_Controller();
  }

  /**
   * Mandrill triger webhook
   */
  public function mandrill_trigger_webhook() {

    $this->benchmark->mark('code_start');
    $this->checkMandrillSignature();

    $mandrillEvents = json_decode($_POST['mandrill_events'], TRUE);
    $mandrillEventsCount = count($mandrillEvents);
    $notSendEvents = array('hard_bounce', 'soft_bounce', 'reject');

    if (is_array($mandrillEvents) && !empty($mandrillEvents)) {

//       log_message('debug', '[mandrill_trigger_webhook] - STARTED for ' . $mandrillEventsCount . ' events');

      $newAppEventsSkipped = 0;
      $broadcastIds = array();
      $recipienIds = array();
      foreach ($mandrillEvents as $k => $event) {
        if (isset($event['msg']['metadata']['new_app'])) {
          $newAppEventsSkipped++;
          unset($mandrillEvents[$k]);
          continue;
        }
        if (empty($event['msg']['metadata']['recipient_id']) || empty($event['msg']['metadata']['broadcast_id'])) {
          unset($mandrillEvents[$k]);
          continue;
        }
        $broadcastIds[] = $event['msg']['metadata']['broadcast_id'];
        $recipienIds[] = $event['msg']['metadata']['recipient_id'];
      }
      if ($newAppEventsSkipped) {
        log_message('debug', '[mandrill_trigger_webhook] - skipped ' . $newAppEventsSkipped . ' new_app events');
      }
      if (empty($mandrillEvents)) {
//         log_message('debug', '[mandrill_trigger_webhook] - No valid mandrill events - Exiting');
        die();
      }

      if ($mandrillEventsCount != count($mandrillEvents)) {
//         log_message('debug', '[mandrill_trigger_webhook] - $mandrillEventsCount value changed from ' . $mandrillEventsCount . ' to ' . count($mandrillEvents));
        $mandrillEventsCount = count($mandrillEvents);
      }

      $broadcastsInDb = ManagerHolder::get('MandrillBroadcast')->getAllWhere(array('id' => $broadcastIds), 'id');
      $broadcastsInDbIds = get_array_vals_by_second_key($broadcastsInDb, 'id');

      $recipiensInDb = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere(array('id' => $recipienIds), 'e.*, user.*');
      $recipiensInDbIds = get_array_vals_by_second_key($recipiensInDb, 'id');

      $processed = 0;
      foreach ($mandrillEvents as $event) {

        $debugIteration = FALSE;
        if (rand(0, 20) == 0) {
          $debugIteration = TRUE;
        }

        $broadcastId = $event['msg']['metadata']['broadcast_id'];
        if (!in_array($broadcastId, $broadcastsInDbIds)) {
          continue;
        }

        $recipientId = $event['msg']['metadata']['recipient_id'];
        $rKey = array_search($recipientId, $recipiensInDbIds);
        if ($rKey === FALSE) {
          continue;
        }
        $recipient = $recipiensInDb[$rKey];

        // Send error
        if (in_array($event['event'], $notSendEvents)) {
          if($event['event'] == 'reject') {
            ManagerHolder::get('EmailNotice')->senRejectedWebhookNotice($recipient);
            $userUpdateData = array('newsletter' => FALSE, 'newsletter_shop' => FALSE, 'newsletter_questions' => FALSE, 'newsletter_comments' => FALSE, 'newsletter_recommended_products' => FALSE, 'newsletter_first_year' => FALSE);
            ManagerHolder::get('User')->updateAllWhere(array('id' => $recipient['user_id']), $userUpdateData);
          } elseif(in_array($event['msg']['bounce_description'], array('bad_mailbox, invalid_domain'))) {
            ManagerHolder::get('User')->deleteById($recipient['user_id']);
            ManagerHolder::get('EmailNotice')->senBounceWebhookNoticeToAdmins($recipient);
          }
          continue;
        }

        // Send
        if ($event['event'] == 'send') {
          ManagerHolder::get('MandrillBroadcastRecipient')->updateById($recipientId, 'is_send', TRUE);
        }

        // Open
        if ($event['event'] == 'open') {
          $openExists = ManagerHolder::get('MandrillBroadcastOpen')->existsWhere(array('recipient_id' => $recipientId, 'broadcast_id' => $broadcastId));
          ManagerHolder::get('MandrillBroadcastOpen')->insert(array('recipient_id' => $recipientId,
                                                                    'broadcast_id' => $broadcastId,
                                                                    'created_at' => date(DOCTRINE_DATE_FORMAT, $event['ts'])));
          ManagerHolder::get('MandrillBroadcastRecipient')->updateById($recipientId, 'is_read', TRUE);
          if (!$openExists) {
            ManagerHolder::get('MandrillBroadcast')->increment($broadcastId, 'read_count');
          }
        }

        // Save the unique identifier assigned to each email sent via Mandrill
        if (empty($recipient['mandrill_email_id'])) {
          ManagerHolder::get('MandrillBroadcastRecipient')->updateById($recipient['id'], 'mandrill_email_id', $event['_id']);
        }

        // Click
        if ($event['event'] == 'click') {
          $url = urldecode($event['url']);
          if (strpos($url, '#') !== FALSE) {
            $url = explode('#', $url);
            if($url[1] == 'read-from') {
              ManagerHolder::get('MandrillBroadcastRecipient')->updateById($recipientId, 'read_more_click', TRUE);
              ManagerHolder::get('MandrillBroadcast')->increment($broadcastId, 'link_visited_count');
              continue;
            }
            $url = $url[0];
          }
          if (strpos($url, '?') !== FALSE) {
            $url = explode('?', $url);
            $url = $url[0];
          }

          // Check for unsubscribe link click
          if(strpos($url, 'отписаться-от-рассылки') !== FALSE || strpos($url, RECOMMENDED_PRODUCTS_BROADCAST_UNSUBSCRIBE_PROCESS) !== FALSE) {
            ManagerHolder::get('MandrillBroadcastRecipient')->updateById($recipientId, 'unsubscribe_link_click', TRUE);
          }

          $mBroadcastLink = ManagerHolder::get('MandrillBroadcastLink')->getOneWhere(array('url' => $url, 'broadcast_id' => $broadcastId), 'id');
          if (!empty($mBroadcastLink)) {
            $clickExists = ManagerHolder::get('MandrillBroadcastVisitedLink')->existsWhere(array('recipient_id' => $recipientId, 'broadcast_id' => $broadcastId));
            ManagerHolder::get('MandrillBroadcastVisitedLink')->insert(array('link_id' => $mBroadcastLink['id'],
                                                                             'recipient_id' => $recipientId,
                                                                             'broadcast_id' => $broadcastId,
                                                                             'created_at' => date(DOCTRINE_DATE_FORMAT, $event['ts'])));
            if (!$clickExists) {
              ManagerHolder::get('MandrillBroadcast')->increment($broadcastId, 'link_visited_count');
            }
          }
        }

        $processed++;
        if ($debugIteration) {
//           log_message('debug', '[mandrill_trigger_webhook] - processed: ' . $processed . '/' . $mandrillEventsCount);
        }
      }
    }

    $this->benchmark->mark('code_end');

//     log_message('debug', '[mandrill_trigger_webhook] - FINISHED for ' . $mandrillEventsCount . ' events; Elapsed Time: ' . $this->benchmark->elapsed_time('code_start', 'code_end'));
    die();
  }

  /**
   * Check mandrill signature
   */
  private function checkMandrillSignature() {
    $params = $_POST;

    $webhook_key = 'Te6Z9vHIhDXIc-hLjSA85A';
    // IMPORTANT: in our case Mandrill is sending webhooks only via http
    $signed_data = str_replace('https://', 'http://', trim(site_url('mandrill-webhook'), '/'));
    ksort($params);
    foreach ($params as $key => $value) {
      $signed_data .= $key;
      $signed_data .= $value;
    }

    $signature = base64_encode(hash_hmac('sha1', $signed_data, $webhook_key, TRUE));

    if (!isset($_SERVER['HTTP_X_MANDRILL_SIGNATURE'])) {
      //       log_message('debug', 'Not isset $_SERVER[\'HTTP_X_MANDRILL_SIGNATURE\']');
      show_404();
    }

    if ($signature != $_SERVER['HTTP_X_MANDRILL_SIGNATURE']) {
      //       log_message('debug', 'Wrong data ' . print_r($_POST, TRUE));
      show_404();
    }
  }

}