<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Project broadcast manager
 */
class ProjectBroadcastManager {


  public function ProjectBroadcastManager() {

  }

  /**
   * Process links for visiting tracking
   * @param string $content
   */
  public function processLinksForVisitingTracking(&$content = array(), $broadcastId) {
    $url = trim(site_url('track-visiting'), '/');

    foreach ($content as &$html) {
      if (is_string($html)) {
        preg_match_all("'<a.*?href=\"(http[s]*://[^>\"]*?)\"[^>]*?>(.*?)</a>'si", $html, $matches);

        foreach ($matches[1] as $match) {
          $html = str_replace($match, $url . '?broadcast_id=' . $broadcastId . '&redirect_after=' . urlencode($match), $html);
        }
      }
    }

    return $html;
  }

}