<?php

/**
 * BaseWarehouseType
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $ref
 * @property Doctrine_Collection $Warehouse
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseWarehouseType extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('warehouse_type');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('ref', 'string', 50, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '50',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Warehouse', array(
             'local' => 'id',
             'foreign' => 'warehouse_type_id'));
    }
}