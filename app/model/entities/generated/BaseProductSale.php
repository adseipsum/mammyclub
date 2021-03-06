<?php

/**
 * BaseProductSale
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $product_id
 * @property integer $sale_id
 * @property Product $product
 * @property Sale $sale
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseProductSale extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('product_sale');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('product_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('sale_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Product as product', array(
             'local' => 'product_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Sale as sale', array(
             'local' => 'sale_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}