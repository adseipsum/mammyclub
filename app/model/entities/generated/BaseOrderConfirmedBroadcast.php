<?php

/**
 * BaseOrderConfirmedBroadcast
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $subject
 * @property clob $email_main_text
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseOrderConfirmedBroadcast extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('order_confirmed_broadcast');
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
             'msgprop' => 'Тема',
             'length' => '255',
             ));
        $this->hasColumn('email_main_text', 'clob', 65536, array(
             'type' => 'clob',
             'notnull' => true,
             'msgprop' => 'Контент',
             'length' => '65536',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}