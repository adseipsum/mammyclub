<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * kprintf letters content
 * @param array $user
 * @param array $data
 * @return array
 */
if (!function_exists('kprintfLettersContent')) {
  function kprintfLettersContent(&$user, &$data) {
    if (is_array($data)) {
      foreach ($data as &$content) {
        if (is_array($content)) {
          foreach ($content as &$c) {
            $c = kprintf($c, $user);
          }
        } else {
          $content = kprintf($content, $user);
        }
      }
    } else {
      $data = kprintf($data, $user);
    }
  }
}

/**
 * prepare_viewdata_msg_ru
 */
if (!function_exists('prepare_viewdata_msg_ru')) {
  function prepare_viewdata_msg_ru($article) {
    $message = $article['content'];
    if(!empty($article['content_short'])) {
      $message = $article['content_short'];
      $message .= '<p style="text-align: center;"><a style="font-family: \'PT Sans\', sans-serif; cursor: pointer; text-decoration: none; color: #fff; border: 1px solid #d0751a; background-color: #f0861e; padding: 9px 15px; margin: 0px; text-align: center; display: inline-block; font-size: 14px; line-height: 14px; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;" href="' . site_url(rtrim($article['page_url'], '/') . '#read-from') . '">Читать дальше</a></p>';
    }
    return $message;
  }
}