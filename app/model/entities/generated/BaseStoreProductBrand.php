<?php

/**
 * BaseStoreProductBrand
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $store_id
 * @property integer $product_brand_id
 * @property Store $store
 * @property ProductBrand $product_brand
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseStoreProductBrand extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('store_product_brand');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('store_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('product_brand_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Store as store', array(
             'local' => 'store_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('ProductBrand as product_brand', array(
             'local' => 'product_brand_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}