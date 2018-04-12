<?php

/**
 * BaseQuestion
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property clob $content
 * @property string $page_url
 * @property integer $comment_count
 * @property datetime $date
 * @property integer $user_id
 * @property integer $header_id
 * @property User $user
 * @property Header $header
 * @property Doctrine_Collection $QuestionComment
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseQuestion extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('question');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'msgprop' => 'Тема',
             'length' => '255',
             ));
        $this->hasColumn('content', 'clob', 65536, array(
             'type' => 'clob',
             'msgprop' => 'Текст',
             'length' => '65536',
             ));
        $this->hasColumn('page_url', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '/консультация/',
             'msgprop' => 'URL-адрес страницы',
             'length' => '255',
             ));
        $this->hasColumn('comment_count', 'integer', 4, array(
             'type' => 'integer',
             'default' => 0,
             'unsigned' => true,
             'msgprop' => 'Количество комментариев',
             'length' => '4',
             ));
        $this->hasColumn('date', 'datetime', null, array(
             'type' => 'datetime',
             'notnull' => true,
             'msgprop' => 'Дата создания',
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'msgprop' => 'Пользователь',
             'length' => '4',
             ));
        $this->hasColumn('header_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User as user', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'SET NULL'));

        $this->hasOne('Header as header', array(
             'local' => 'header_id',
             'foreign' => 'id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('QuestionComment', array(
             'local' => 'id',
             'foreign' => 'entity_id'));
    }
}