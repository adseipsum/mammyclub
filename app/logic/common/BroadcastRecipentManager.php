<?php
/**
 * BroadcastRecipentManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class BroadcastRecipentManager extends BaseManager {
  
  /** Order By. */
  protected $orderBy = "updated_at DESC";

  /** Fields. */
  public $fields = array("email" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "is_read" => array("type" => "checkbox"),
                         "broadcast_id" => array("type" => "select", "options" => array()));

  /** List params. */
  public $listParams = array("email", "broadcast.subject", "is_read", "updated_at");
  
  /**
   * setIsRead
   */
  public function setIsRead($encodedId){
    $id = ManagerHolder::get('Broadcast')->doDecodeId($encodedId);
    $this->updateById($id, 'is_read', TRUE);
  }
  
  /**
   * PostUpdate.
   * OVERRIDE THIS METHOD IN A SUBCLASS.
   * @param array $entity
   */
  protected function postUpdate($entity) {
    $entity = $this->getById($entity['id']);
    ManagerHolder::get('Broadcast')->updateById($entity['broadcast_id'], 'read_count', $this->getCountWhere(array('broadcast_id' => $entity['broadcast_id'], 'is_read' => TRUE)));
  }

}