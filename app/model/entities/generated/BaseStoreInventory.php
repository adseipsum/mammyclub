<?php

/**
 * BaseStoreInventory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $bar_code
 * @property string $product_code
 * @property integer $product_id
 * @property integer $product_group_id
 * @property integer $store_id
 * @property string $config_file_name
 * @property integer $qty
 * @property integer $update_by_admin_id
 * @property enum $update_source
 * @property string $file
 * @property datetime $updated_at
 * @property datetime $last_sync_at
 * @property Product $product
 * @property ParameterGroup $product_group
 * @property Store $store
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseStoreInventory extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('store_inventory');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('bar_code', 'string', 255, array(
             'type' => 'string',
             'msgprop' => 'Штрих-код',
             'length' => '255',
             ));
        $this->hasColumn('product_code', 'string', 20, array(
             'type' => 'string',
             'msgprop' => 'Код товара',
             'length' => '20',
             ));
        $this->hasColumn('product_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('product_group_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('store_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('config_file_name', 'string', 20, array(
             'type' => 'string',
             'length' => '20',
             ));
        $this->hasColumn('qty', 'integer', 4, array(
             'type' => 'integer',
             'msgprop' => 'Остаток',
             'length' => '4',
             ));
        $this->hasColumn('update_by_admin_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('update_source', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'file',
              1 => 'edit',
              2 => 'web',
             ),
             ));
        $this->hasColumn('file', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('updated_at', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('last_sync_at', 'datetime', null, array(
             'type' => 'datetime',
             ));


        $this->index('bar_code_index', array(
             'fields' => 
             array(
              0 => 'bar_code',
             ),
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Product as product', array(
             'local' => 'product_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('ParameterGroup as product_group', array(
             'local' => 'product_group_id',
             'foreign' => 'id',
             'onDelete' => 'SET NULL'));

        $this->hasOne('Store as store', array(
             'local' => 'store_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}