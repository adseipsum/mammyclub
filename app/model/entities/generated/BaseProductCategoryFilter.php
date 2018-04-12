<?php

/**
 * BaseProductCategoryFilter
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $product_category_id
 * @property integer $filter_id
 * @property ProductCategory $product_category
 * @property Filter $filter
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseProductCategoryFilter extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('product_category_filter');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('product_category_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('filter_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));


        $this->index('category_filter_index', array(
             'fields' => 
             array(
              0 => 'product_category_id',
              1 => 'filter_id',
             ),
             'type' => 'unique',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('ProductCategory as product_category', array(
             'local' => 'product_category_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Filter as filter', array(
             'local' => 'filter_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}