<?php

/**
 * BaseTaskScheduleLog
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $task_schedule_id
 * @property datetime $date_start
 * @property datetime $date_end
 * @property integer $event_log_id
 * @property TaskSchedule $task_schedule
 * @property EventLog $event_log
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTaskScheduleLog extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('task_schedule_log');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('task_schedule_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('date_start', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('date_end', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('event_log_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('TaskSchedule as task_schedule', array(
             'local' => 'task_schedule_id',
             'foreign' => 'id',
             'onDelete' => 'SET NULL'));

        $this->hasOne('EventLog as event_log', array(
             'local' => 'event_log_id',
             'foreign' => 'id',
             'onDelete' => 'SET NULL'));
    }
}