<?php
/**
 * BroadcastLinkManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class BroadcastLinkManager extends BaseManager {

  /** Fields. */
  public $fields = array("url" => array("type" => "textarea", "class" => "charCounter"),
                         "broadcast_id" => array("type" => "select", "options" => array()));

  /** List params. */
  public $listParams = array("url");

  /**
   * setVisitedByUrl
   * @param $url
   */
  public function setVisitedById($linkId, $recipentId){
    $recipentId = ManagerHolder::get('Broadcast')->doDecodeId($recipentId);
    $linkId = ManagerHolder::get('Broadcast')->doDecodeId($linkId);
    $link = $this->getById($linkId);
    if($link){
      $visLink = array();
      $visLink['link_id'] = $link['id'];
      $visLink['recipent_id'] = $recipentId;
      if(!ManagerHolder::get('BroadcastVisitedLink')->existsWhere($visLink)) {
        ManagerHolder::get('BroadcastVisitedLink')->insert($visLink);
        ManagerHolder::get('Broadcast')->increment($link['broadcast_id'], 'link_visited_count');
      }
      // user followed the link
      // it means he has read the broadcast
      ManagerHolder::get('BroadcastRecipent')->updateById($recipentId, 'is_read', TRUE);
    }
  }

}