<?php

/**
 * BaseProductCategory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property boolean $published
 * @property string $page_url
 * @property boolean $product_with_discount
 * @property clob $content
 * @property integer $priority
 * @property string $google_product_category
 * @property integer $header_id
 * @property Doctrine_Collection $filters
 * @property Doctrine_Collection $brands
 * @property Header $header
 * @property Doctrine_Collection $Product
 * @property Doctrine_Collection $category_filter_rels
 * @property Doctrine_Collection $product_category_product_brand_rels
 * @property Doctrine_Collection $ProductCategoryProductCategory
 * @property Doctrine_Collection $product_sale_categories
 * @property Doctrine_Collection $ProductSaleCategoryProductCategory
 * @property Doctrine_Collection $remarketing_categories
 * @property Doctrine_Collection $remarketing_product_category_rels
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseProductCategory extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('product_category');
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
        $this->hasColumn('published', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 1,
             'notnull' => true,
             'msgprop' => 'Опубликовано',
             ));
        $this->hasColumn('page_url', 'string', 255, array(
             'type' => 'string',
             'unique' => true,
             'notnull' => true,
             'default' => '/',
             'msgprop' => 'URL-адрес страницы',
             'length' => '255',
             ));
        $this->hasColumn('product_with_discount', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             'notnull' => true,
             ));
        $this->hasColumn('content', 'clob', 65536, array(
             'type' => 'clob',
             'msgprop' => 'Текст',
             'length' => '65536',
             ));
        $this->hasColumn('priority', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'msgprop' => 'Приоритет',
             'length' => '4',
             ));
        $this->hasColumn('google_product_category', 'string', 255, array(
             'type' => 'string',
             'msgprop' => 'google_product_category',
             'length' => '255',
             ));
        $this->hasColumn('header_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));


        $this->index('name_index', array(
             'fields' => 
             array(
              0 => 'name',
             ),
             ));
        $this->index('page_url_index', array(
             'fields' => 
             array(
              0 => 'page_url',
             ),
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Filter as filters', array(
             'refClass' => 'ProductCategoryFilter',
             'local' => 'product_category_id',
             'foreign' => 'filter_id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('ProductBrand as brands', array(
             'refClass' => 'ProductCategoryProductBrand',
             'local' => 'product_category_id',
             'foreign' => 'product_brand_id',
             'onDelete' => 'SET NULL'));

        $this->hasOne('Header as header', array(
             'local' => 'header_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('Product', array(
             'local' => 'id',
             'foreign' => 'category_id'));

        $this->hasMany('ProductCategoryFilter as category_filter_rels', array(
             'local' => 'id',
             'foreign' => 'product_category_id'));

        $this->hasMany('ProductCategoryProductBrand as product_category_product_brand_rels', array(
             'local' => 'id',
             'foreign' => 'product_category_id'));

        $this->hasMany('ProductCategoryProductCategory', array(
             'local' => 'id',
             'foreign' => 'product_category_id'));

        $this->hasMany('ProductSaleCategory as product_sale_categories', array(
             'refClass' => 'ProductSaleCategoryProductCategory',
             'local' => 'product_category_id',
             'foreign' => 'product_sale_category_id'));

        $this->hasMany('ProductSaleCategoryProductCategory', array(
             'local' => 'id',
             'foreign' => 'product_category_id'));

        $this->hasMany('RemarketingCategory as remarketing_categories', array(
             'refClass' => 'RemarketingCategoryProductCategory',
             'local' => 'product_category_id',
             'foreign' => 'remarketing_category_id'));

        $this->hasMany('RemarketingCategoryProductCategory as remarketing_product_category_rels', array(
             'local' => 'id',
             'foreign' => 'product_category_id'));

        $nestedset0 = new Doctrine_Template_NestedSet(array(
             'hasManyRoots' => true,
             'rootColumnName' => 'root_id',
             ));
        $this->actAs($nestedset0);
    }
}