<?php

/**
 * BaseProductParams
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property clob $product_params
 * @property Doctrine_Collection $Product
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseProductParams extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('product_params');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'msgprop' => 'Название',
             'length' => '255',
             ));
        $this->hasColumn('product_params', 'clob', 65536, array(
             'type' => 'clob',
             'length' => '65536',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Product', array(
             'local' => 'id',
             'foreign' => 'product_params_id'));
    }
}