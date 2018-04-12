<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

use Masterminds\HTML5;
use NovaPoshta\ApiModels\InternetDocument;
use NovaPoshta\ApiModels\TrackingDocument;

/**
 * Cron controller.
 * @property RetailCrm\ApiClient $retailcrmapi
 * @author Itirra - http://itirra.com
 */
class Task_Schedule_Controller extends Base_Project_Controller {

  /* Security code. Ensures that nobody runs this controller, but cron */
  const PROTECTION_CODE = '30ad3a3a1d2c7c63102e09e6fe4bb253';

  private $currentHour;

  private $currentMin;

  private $currentTimestamp;

  /**
   * Constructor.
   */
  public function Task_Schedule_Controller() {
    parent::Base_Project_Controller();
    if (!url_contains(self::PROTECTION_CODE)) show_404();
    set_time_limit(0);
    sleep(1);

    $this->currentHour = date('H');
    $this->currentMin = date('i');
    $this->currentTimestamp = time();
  }

  /**
   * Run
   */
  public function run() {
    log_message('debug', 'Task Schedule PERIODIC TICK');

    $this->process_one_time_task();
    $this->process_periodic_hour_task();
    $this->process_periodic_day_task();

    die('DONE');


    if ($this->currentMin % 5 == 0 || TRUE) {
      $this->process_one_time_task();
    }

  }

  /**
   * process_one_time_tasks
   */
  public function process_one_time_task() {
    $where = array();
    $where['is_active'] = TRUE;
    $where['is_processing'] = FALSE;
    $where['task_type'] = 'one_time';
    $where['execution_date'] = date('Y-m-d');
    $where['execution_time<='] = $this->currentHour . ':' . $this->currentMin . ':00';

    $task = ManagerHolder::get('TaskSchedule')->getOneWhere($where, 'e.*');
    if (empty($task)) {
      return;
    }

    ManagerHolder::get('TaskSchedule')->updateById($task['id'], 'is_processing', TRUE);

    $eventLog = Events::trigger($task['event'], json_decode($task['task_data'], TRUE));

    ManagerHolder::get('TaskSchedule')->updateById($task['id'], 'is_active', FALSE);


    if ($task['send_notification'] == 'always' || ($task['send_notification'] == 'on_not_success' && !$eventLog['is_success']) || ($task['send_notification'] == 'on_success' && $eventLog['is_success'])) {
      ManagerHolder::get('AdminNotification')->sendTaskNotification($task, $eventLog);
    }

    $this->process_one_time_task();
    return;
  }

  /**
   * Process periodic hour task
   */
  public function process_periodic_hour_task() {
    $where = array();
    $where['is_active'] = TRUE;
    $where['is_processing'] = FALSE;
    $where['task_type'] = 'periodic';
    $where['execution_type'] = 'hour';
    $fiveMinAgoMin = date('i', $this->currentTimestamp - 4 * 60);
    $where['execution_minBETWEEN'] = $fiveMinAgoMin . ' AND ' . $this->currentMin;

    $oneHourAgo = date(DOCTRINE_DATE_FORMAT, $this->currentTimestamp - 3260);
    $where['last_execution_date<'] = $oneHourAgo;
    $task = ManagerHolder::get('TaskSchedule')->getOneWhere($where, 'e.*');
    if (empty($task)) {
      return;
    }
    $this->process_periodic_task($task);
    $this->process_periodic_hour_task();
  }

  /**
   * Process periodic day task
   */
  public function process_periodic_day_task() {
    $where = array();
    $where['is_active'] = TRUE;
    $where['is_processing'] = FALSE;
    $where['task_type'] = 'periodic';
    $where['execution_type'] = 'day';
    $fiveMinAgoMin = date('i', $this->currentTimestamp - 4 * 60);
    $where['execution_minBETWEEN'] = $fiveMinAgoMin . ' AND ' . $this->currentMin;
    $where['execution_hour'] = (int)$this->currentHour;

//    $oneDayAgo = date(DOCTRINE_DATE_FORMAT, $this->currentTimestamp - ((3600 * 24) - (4 * 60)));
//    $where['last_execution_date<'] = $oneDayAgo;
    $startOfTodayDay = date('Y-m-d') . ' 00:00:00';
    $where['last_execution_date<'] = $startOfTodayDay;
    $task = ManagerHolder::get('TaskSchedule')->getOneWhere($where, 'e.*');
    if (empty($task)) {
      return;
    }
    $this->process_periodic_task($task);

    $this->process_periodic_day_task();
  }


  /**
   * Process periodic task
   * @param $task
   */
  public function process_periodic_task($task) {
    if (empty($task)) {
      return;
    }

    ManagerHolder::get('TaskSchedule')->updateById($task['id'], 'is_processing', TRUE);
    $taskLog = array();
    $taskLog['task_schedule_id'] = $task['id'];
    $taskLog['date_start'] = date(DOCTRINE_DATE_FORMAT);
    $taskLog['id'] = ManagerHolder::get('TaskScheduleLog')->insert($taskLog);

    $eventLog = Events::trigger($task['event'], json_decode($task['task_data'], TRUE));
    if (isset($eventLog['id'])) {
      ManagerHolder::get('TaskScheduleLog')->updateById($taskLog['id'], 'event_log_id', $eventLog['id']);
    }

    ManagerHolder::get('TaskScheduleLog')->updateById($taskLog['id'], 'date_end', date(DOCTRINE_DATE_FORMAT));
    ManagerHolder::get('TaskSchedule')->updateById($task['id'], 'last_execution_date', date(DOCTRINE_DATE_FORMAT));
    ManagerHolder::get('TaskSchedule')->updateById($task['id'], 'is_processing', FALSE);

    if ($task['send_notification'] == 'always' || ($task['send_notification'] == 'on_not_success' && !$eventLog['is_success']) || ($task['send_notification'] == 'on_success' && $eventLog['is_success'])) {
      ManagerHolder::get('AdminNotification')->sendTaskNotification($task, $eventLog);
    }

    return;
  }

  /**
   * Process hour periodic tasks
   */
  public function process_hour_periodic_tasks() {
    $where = array();
    $where['is_active'] = TRUE;
    $where['is_processing'] = FALSE;
    $where['task_type'] = 'periodic';

    $tasks = ManagerHolder::get('TaskSchedule')->getAllWhere($where, 'e.*');
    if (empty($tasks)) {
      return;
    }

    foreach ($tasks as $task) {

    }
  }

}