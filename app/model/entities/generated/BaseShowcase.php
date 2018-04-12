<?php

/**
 * BaseShowcase
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property boolean $published
 * @property boolean $is_default
 * @property string $age_of_child
 * @property Doctrine_Collection $products
 * @property Doctrine_Collection $pregnancy_weeks
 * @property Doctrine_Collection $ShowcasePregnancyWeek
 * @property Doctrine_Collection $showcase_product_rels
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseShowcase extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('showcase');
        $this->hasColumn('id', 'integer', 4, array(
             'primary' => true,
             'autoincrement' => true,
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'msgprop' => 'Название',
             'length' => '255',
             ));
        $this->hasColumn('published', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             'notnull' => true,
             'msgprop' => 'Опубликовано',
             ));
        $this->hasColumn('is_default', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             'notnull' => true,
             'msgprop' => 'По умолчанию',
             ));
        $this->hasColumn('age_of_child', 'string', 255, array(
             'type' => 'string',
             'msgprop' => 'Возраст ребенка',
             'length' => '255',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Product as products', array(
             'refClass' => 'ShowcaseProduct',
             'local' => 'showcase_id',
             'foreign' => 'product_id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('PregnancyWeek as pregnancy_weeks', array(
             'refClass' => 'ShowcasePregnancyWeek',
             'local' => 'showcase_id',
             'foreign' => 'pregnancy_week_id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('ShowcasePregnancyWeek', array(
             'local' => 'id',
             'foreign' => 'showcase_id'));

        $this->hasMany('ShowcaseProduct as showcase_product_rels', array(
             'local' => 'id',
             'foreign' => 'showcase_id'));
    }
}