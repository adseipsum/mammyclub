<?php

/**
 * BaseArticleExample
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $priority
 * @property integer $image_id
 * @property integer $pregnancy_article_id
 * @property Image $image
 * @property PregnancyArticle $article
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseArticleExample extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('article_example');
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
             'msgprop' => 'Название',
             'length' => '255',
             ));
        $this->hasColumn('priority', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'msgprop' => 'Приоритет',
             'length' => '4',
             ));
        $this->hasColumn('image_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'msgprop' => 'Изображение',
             'length' => '4',
             ));
        $this->hasColumn('pregnancy_article_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'msgprop' => 'Статья',
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Image as image', array(
             'local' => 'image_id',
             'foreign' => 'id',
             'onDelete' => 'SET NULL'));

        $this->hasOne('PregnancyArticle as article', array(
             'local' => 'pregnancy_article_id',
             'foreign' => 'id',
             'onDelete' => 'SET NULL'));
    }
}