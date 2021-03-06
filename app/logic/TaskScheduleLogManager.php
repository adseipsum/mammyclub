<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * TaskScheduleLogManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class TaskScheduleLogManager extends BaseManager {

  /** Name field. */
  protected $nameField = "id";


  /** Fields. */
  public $fields = array("task_schedule_id" => array("type" => "input_integer"),
                         "date_start" => array("type" => "date"),
                         "date_end" => array("type" => "date"),
                         "event_log_id" => array("type" => "input_integer"));

  /** List params. */
  public $listParams = array("task_schedule_id", "date_start", "date_end", "event_log_id");

}