<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * XBroadcastSegmentCountryManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class XBroadcastSegmentCountryManager extends BaseManager {

  /** Primary Key Field. */
  protected $pk = array("broadcast_segment_id", "country_id");


  /** Fields. */
  public $fields = array("broadcast_segment_id" => array("type" => "input_integer", "class" => "required"),
                         "country" => array("type" => "select", "class" => "required", "relation" => array("entity_name" => "Country")));

  /** List params. */
  public $listParams = array("broadcast_segment_id", "country.name");

}