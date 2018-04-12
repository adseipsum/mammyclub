<?php

/**
 * BaseReturningBroadcast
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $subject
 * @property string $email_appeal
 * @property string $email_intro
 * @property clob $email_main_text
 * @property string $email_outro
 * @property datetime $sent_datetime
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseReturningBroadcast extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('returning_broadcast');
        $this->hasColumn('id', 'integer', 4, array(
             'primary' => true,
             'autoincrement' => true,
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'msgprop' => 'Название',
             'length' => '255',
             ));
        $this->hasColumn('subject', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('email_appeal', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('email_intro', 'string', 5000, array(
             'type' => 'string',
             'length' => '5000',
             ));
        $this->hasColumn('email_main_text', 'clob', 65536, array(
             'type' => 'clob',
             'length' => '65536',
             ));
        $this->hasColumn('email_outro', 'string', 5000, array(
             'type' => 'string',
             'length' => '5000',
             ));
        $this->hasColumn('sent_datetime', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}