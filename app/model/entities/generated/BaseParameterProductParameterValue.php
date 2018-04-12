<?php

/**
 * BaseParameterProductParameterValue
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $parameter_product_id
 * @property integer $parameter_value_id
 * @property ParameterProduct $parameter_product
 * @property ParameterValue $parameter_value
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseParameterProductParameterValue extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('parameter_product_parameter_value');
        $this->hasColumn('parameter_product_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'length' => '4',
             ));
        $this->hasColumn('parameter_value_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('ParameterProduct as parameter_product', array(
             'local' => 'parameter_product_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('ParameterValue as parameter_value', array(
             'local' => 'parameter_value_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}