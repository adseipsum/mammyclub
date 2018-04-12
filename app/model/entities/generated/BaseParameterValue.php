<?php

/**
 * BaseParameterValue
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $priority
 * @property integer $parameter_id
 * @property Parameter $parameter
 * @property Doctrine_Collection $parameter_groups
 * @property Doctrine_Collection $ParameterGroup
 * @property Doctrine_Collection $ParameterGroupValueOut
 * @property Doctrine_Collection $parameter_product
 * @property Doctrine_Collection $ParameterProductLink
 * @property Doctrine_Collection $ParameterProductParameterValue
 * @property Doctrine_Collection $Product
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseParameterValue extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('parameter_value');
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
             'msgprop' => 'Название',
             'length' => '255',
             ));
        $this->hasColumn('priority', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'msgprop' => 'Приоритет',
             'length' => '4',
             ));
        $this->hasColumn('parameter_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'msgprop' => 'Параметр',
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Parameter as parameter', array(
             'local' => 'parameter_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('ParameterGroup as parameter_groups', array(
             'refClass' => 'ParameterGroupValueOut',
             'local' => 'parameter_value_id',
             'foreign' => 'parameter_group_id'));

        $this->hasMany('ParameterGroup', array(
             'local' => 'id',
             'foreign' => 'main_parameter_value_id'));

        $this->hasMany('ParameterGroupValueOut', array(
             'local' => 'id',
             'foreign' => 'parameter_value_id'));

        $this->hasMany('ParameterProduct as parameter_product', array(
             'refClass' => 'ParameterProductParameterValue',
             'local' => 'parameter_value_id',
             'foreign' => 'parameter_product_id'));

        $this->hasMany('ParameterProductLink', array(
             'local' => 'id',
             'foreign' => 'parameter_value_id'));

        $this->hasMany('ParameterProductParameterValue', array(
             'local' => 'id',
             'foreign' => 'parameter_value_id'));

        $this->hasMany('Product', array(
             'local' => 'id',
             'foreign' => 'parameter_value_link_id'));
    }
}