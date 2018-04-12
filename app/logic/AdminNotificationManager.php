<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * AdminNotification
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class AdminNotificationManager extends BaseManager {

  /**
   * Send task notification
   * @param $task
   * @param $eventLog
   */
  public function sendTaskNotification($task, $eventLog) {
    $CI = &get_instance();
    $CI->load->config('event');
    $config = $CI->config->item('event');

    $what = 'e.*';
    if (isset($config[$eventLog['event_model']]['entity_what'])) {
      $what = $config[$eventLog['event_model']]['entity_what'];
    }

    $template = ManagerHolder::get('AdminNotificationTemplate')->getById($task['admin_notification_template_id'], 'e.*, admins.*');
    $data = array();
    if (!empty($eventLog['entity_id'])) {
      $entity = ManagerHolder::get($eventLog['event_model'])->getById($eventLog['entity_id'], $what);
      $data['entity'] = $entity;
    }

    $data['event'] = json_decode($eventLog['data'], TRUE);
    $data['result'] = $eventLog['result'];
    $data = array_make_plain_with_dots($data);

    $subject = kprintf($template['subject'], $data);
    $content = kprintf($template['email_main_text'], $data);

    $emails = array();
    foreach ($template['admins'] as $admin) {
      $emails[] = $admin['email'];
    }
    if (isset($config[$eventLog['event_model']]['additional_email_entity_rel']) && isset($config[$eventLog['event_model']]['additional_email_entity_field'])) {
      if (isset($entity[$config[$eventLog['event_model']]['additional_email_entity_rel']][$config[$eventLog['event_model']]['additional_email_entity_field']])) {
        $emails[] = $entity[$config[$eventLog['event_model']]['additional_email_entity_rel']][$config[$eventLog['event_model']]['additional_email_entity_field']];
      }
    }
    if (!empty($template['additional_emails'])) {
      $additionalEmails = explode(',', $template['additional_emails']);
      foreach ($additionalEmails as $additionalEmail) {
        $emails[] = trim($additionalEmail);
      }
    }

    foreach ($emails as $email) {
      ManagerHolder::get('EmailMandrill')->send($email, $subject, $content);
    }
  }

  /**
   * @param $adminNotificationTemplateKey
   * @param null $entityName
   * @param $entityId
   * @param string $what
   * @return bool
   */
  public function sendNotification($adminNotificationTemplateKey, $entityName = NULL, $entityId = NULL, $what = NULL) {
    $entity = array();

    if (!empty($entityName) && !empty($entityId)) {
      if ($what === NULL && isset($config[$entityName]['entity_what'])) {
        $what = $config[$entityName]['entity_what'];
      } else {
        $what = 'e.*';
      }

      $entity = ManagerHolder::get($entityName)->getById($entityId, $what);
    }

    $template = ManagerHolder::get('AdminNotificationTemplate')->getOneWhere(array('k' => $adminNotificationTemplateKey), 'e.*, admins.*');
    if (empty($template)) {
      return FALSE;
    }

    $data = array();
    $data['entity'] = $entity;
    $data = array_make_plain_with_dots($data);

    $subject = kprintf($template['subject'], $data);
    $content = kprintf($template['email_main_text'], $data);

    $emails = array();
    foreach ($template['admins'] as $admin) {
      $emails[] = $admin['email'];
    }
    if (isset($config[$entityName]['additional_email_entity_rel']) && isset($config[$entityName]['additional_email_entity_field'])) {
      if (isset($entity[$config[$entityName]['additional_email_entity_rel']][$config[$entityName]['additional_email_entity_field']])) {
        $emails[] = $entity[$config[$entityName]['additional_email_entity_rel']][$config[$entityName]['additional_email_entity_field']];
      }
    }
    if (!empty($template['additional_emails'])) {
      $additionalEmails = explode(',', $template['additional_emails']);
      foreach ($additionalEmails as $additionalEmail) {
        $emails[] = trim($additionalEmail);
      }
    }
    $emails = array_unique($emails);

    foreach ($emails as $email) {
      ManagerHolder::get('EmailMandrill')->send($email, $subject, $content);
    }

    return TRUE;
  }

}