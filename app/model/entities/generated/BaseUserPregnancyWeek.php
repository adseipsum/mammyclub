<?php

/**
 * BaseUserPregnancyWeek
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property integer $pregnancy_week_id
 * @property PregnancyWeek $pregnancyweek
 * @property User $user
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUserPregnancyWeek extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('user_pregnancy_week');
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'length' => '4',
             ));
        $this->hasColumn('pregnancy_week_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('PregnancyWeek as pregnancyweek', array(
             'local' => 'pregnancy_week_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('User as user', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}