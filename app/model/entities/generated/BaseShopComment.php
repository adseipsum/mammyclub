<?php

/**
 * BaseShopComment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property clob $content
 * @property integer $parent_id
 * @property integer $level
 * @property integer $sortorder
 * @property datetime $date
 * @property boolean $published
 * @property integer $user_id
 * @property boolean $can_be_deleted
 * @property User $user
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseShopComment extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('shop_comment');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('content', 'clob', 65536, array(
             'type' => 'clob',
             'notnull' => true,
             'msgprop' => 'Текст',
             'length' => '65536',
             ));
        $this->hasColumn('parent_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('level', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('sortorder', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('date', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             'msgprop' => 'Дата создания',
             ));
        $this->hasColumn('published', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 1,
             'notnull' => true,
             'msgprop' => 'Опубликовано',
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'msgprop' => 'Пользователь',
             'length' => '4',
             ));
        $this->hasColumn('can_be_deleted', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             'notnull' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User as user', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}