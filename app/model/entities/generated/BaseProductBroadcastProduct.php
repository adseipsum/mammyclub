<?php

/**
 * BaseProductBroadcastProduct
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $product_broadcast_id
 * @property integer $product_id
 * @property ProductBroadcast $product_broadcast
 * @property Product $product
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseProductBroadcastProduct extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('product_broadcast_product');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('product_broadcast_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'length' => '4',
             ));
        $this->hasColumn('product_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('ProductBroadcast as product_broadcast', array(
             'local' => 'product_broadcast_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Product as product', array(
             'local' => 'product_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}