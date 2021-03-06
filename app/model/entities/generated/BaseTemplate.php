<?php

/**
 * BaseTemplate
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $entity_name
 * @property string $field
 * @property string $value
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTemplate extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('template');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('entity_name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'msgprop' => 'Название сущности',
             'length' => '255',
             ));
        $this->hasColumn('field', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'msgprop' => 'Поле',
             'length' => '255',
             ));
        $this->hasColumn('value', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'msgprop' => 'Значение',
             'length' => '',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}