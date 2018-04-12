<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * XBroadcastRecipientManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class XBroadcastRecipientManager extends BaseManager {

  /** Name field. */
  protected $nameField = "id";

  /** Order by */
  protected $orderBy = "created_at DESC";

  /** Fields. */
  public $fields = array("email" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "mandrill_email_id" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "status" => array("type" => "enum"),
                         "template_variant" => array("type" => "input_integer", "class" => "required"),
                         "is_sent" => array("type" => "checkbox"),
                         "is_successfully_sent" => array("type" => "checkbox"),
                         "is_read" => array("type" => "checkbox"),
                         "is_link_visited" => array("type" => "checkbox"),
                         "is_converted" => array("type" => "checkbox"),
                         "data" => array("type" => "tinymce", "class" => "required", "attrs" => array("maxlength" => 65536)),
                         "user" => array("type" => "select", "relation" => array("entity_name" => "User")),
                         "broadcast" => array("type" => "select", "relation" => array("entity_name" => "XBroadcast")),
                         "created_at" => array("type" => "datetime", "class" => "readonly", "attrs" => array("disabled" => "disabled")));

  /** List params. */
  public $listParams = array("sent_date", "broadcast.template.type.name", "email", "broadcast.subject", "status", "template_variant", "is_successfully_sent", "is_read", "is_link_visited", "user.country");

  /**
   * Filter Values
   * @param string $filterName
   * @return array
   */
  public function getFilterValues($filterName) {
    if ($filterName == 'user.country') {
      return array('UA' => 'UA', 'RU' => 'RU');
    }
    if ($filterName == 'broadcast.template.type.id') {
      return ManagerHolder::get('XBroadcastType')->getAsViewArray();
    }
    return parent::getFilterValues($filterName);
  }

  /**
   * PreProcessWhereQuery.
   * OVERRIDE THIS METHOD IN A SUBCLASS TO ADD joins and other stuff.
   * @param Doctrine_Query $query
   * @return Doctrine_Query
   */
  protected function preProcessWhereQuery($query, $pref, $what = "*") {
    $query = parent::preProcessWhereQuery($query, $pref, $what);
    if (strpos($what, 'broadcast.*') !== FALSE || $what == '*') {
      $query->addSelect("broadcast_template.*")->leftJoin("broadcast.template broadcast_template");
      $query->addSelect("broadcast_template_type.*")->leftJoin("broadcast_template.type broadcast_template_type");
    }
    return $query;
  }

  /**
   * createUserStatsFromArray
   *
   * @param array $records
   * @return array
   */
  public function createUserStatsFromArray(array $records)
  {
    $result = array();
    if (!empty($records)) {
      foreach ($records as $r) {
        $result[] = array(
          'created_at' => $r['created_at'],
          'subject' => json_decode($r['broadcast']['subject'], true)[$r['template_variant']],
          'open_count' => count($r['XBroadcastOpen']),
          'opens' => get_array_vals_by_second_key($r['XBroadcastOpen'], 'created_at'),
          'visited_links' => get_array_vals_by_second_key($r['XBroadcastVisitedLink'], 'url'),
          'html_url' => admin_site_url('xbroadcastrecipient/preview/' . $r['id'])
        );
      }
    }
    return $result;
  }

}