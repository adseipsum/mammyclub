<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * XBroadcastSegmentXBroadcastTypeManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class XBroadcastSegmentXBroadcastTypeManager extends BaseManager {

  /** Primary Key Field. */
  protected $pk = array("broadcast_segment_id", "broadcast_type_id");


  /** Fields. */
  public $fields = array("broadcast_segment" => array("type" => "select", "class" => "required", "relation" => array("entity_name" => "XBroadcastSegment")),
                         "broadcast_type" => array("type" => "select", "class" => "required", "relation" => array("entity_name" => "XBroadcastType")));

  /** List params. */
  public $listParams = array("broadcast_segment.name", "broadcast_type.name");

}