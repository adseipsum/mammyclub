<?php

/**
 * BaseEventLog
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $event_model
 * @property string $event_method
 * @property datetime $created_at
 * @property bool $is_success
 * @property enum $change_by
 * @property integer $admin_id
 * @property integer $entity_id
 * @property string $search_field_value
 * @property string $data
 * @property string $result
 * @property Admin $admin
 * @property Doctrine_Collection $TaskScheduleLog
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseEventLog extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('event_log');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('event_model', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('event_method', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('created_at', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('is_success', 'bool', null, array(
             'type' => 'bool',
             'default' => 0,
             ));
        $this->hasColumn('change_by', 'enum', null, array(
             'type' => 'enum',
             'notnull' => true,
             'values' => 
             array(
              0 => 'system',
              1 => 'admin',
             ),
             'default' => 'system',
             ));
        $this->hasColumn('admin_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('entity_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('search_field_value', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('data', 'string', 1000, array(
             'type' => 'string',
             'length' => '1000',
             ));
        $this->hasColumn('result', 'string', 1000, array(
             'type' => 'string',
             'length' => '1000',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Admin as admin', array(
             'local' => 'admin_id',
             'foreign' => 'id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('TaskScheduleLog', array(
             'local' => 'id',
             'foreign' => 'event_log_id'));
    }
}