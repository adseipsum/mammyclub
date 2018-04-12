<?php

/**
 * BaseDelivery
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $order_price_from
 * @property integer $order_price_to
 * @property decimal $price
 * @property boolean $is_active
 * @property Doctrine_Collection $delivery_id
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseDelivery extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('delivery');
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
        $this->hasColumn('order_price_from', 'integer', 7, array(
             'type' => 'integer',
             'default' => 0,
             'notnull' => true,
             'length' => '7',
             ));
        $this->hasColumn('order_price_to', 'integer', 7, array(
             'type' => 'integer',
             'default' => 0,
             'notnull' => true,
             'length' => '7',
             ));
        $this->hasColumn('price', 'decimal', 7, array(
             'type' => 'decimal',
             'default' => 0,
             'msgprop' => 'Цена доставки',
             'length' => '7',
             'scale' => '2',
             ));
        $this->hasColumn('is_active', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('SiteOrder as delivery_id', array(
             'local' => 'id',
             'foreign' => 'delivery_id'));
    }
}