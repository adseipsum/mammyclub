<?php

/**
 * BaseTeam
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $place
 * @property string $description
 * @property integer $image_id
 * @property Image $image
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTeam extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('team');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'msgprop' => 'ФИО',
             'length' => '255',
             ));
        $this->hasColumn('place', 'string', 255, array(
             'type' => 'string',
             'msgprop' => 'Должность',
             'length' => '255',
             ));
        $this->hasColumn('description', 'string', 1000, array(
             'type' => 'string',
             'msgprop' => 'Описание',
             'length' => '1000',
             ));
        $this->hasColumn('image_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'msgprop' => 'Изображение',
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
    }
}