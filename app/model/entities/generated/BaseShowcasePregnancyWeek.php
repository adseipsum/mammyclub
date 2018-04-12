<?php

/**
 * BaseShowcasePregnancyWeek
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $pregnancy_week_id
 * @property integer $showcase_id
 * @property PregnancyWeek $pregnancy_week
 * @property Showcase $showcase
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseShowcasePregnancyWeek extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('showcase_pregnancy_week');
        $this->hasColumn('pregnancy_week_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('showcase_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('PregnancyWeek as pregnancy_week', array(
             'local' => 'pregnancy_week_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Showcase as showcase', array(
             'local' => 'showcase_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}