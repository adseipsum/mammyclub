<?php

/**
 * BaseXBroadcastSegment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property enum $child_gender
 * @property string $last_login_filter
 * @property string $receipt_type_filter
 * @property string $open_type_filter
 * @property string $page_visit_filter
 * @property Doctrine_Collection $users
 * @property Doctrine_Collection $weeks
 * @property Doctrine_Collection $ages
 * @property Doctrine_Collection $countries
 * @property Doctrine_Collection $broadcast_types
 * @property Doctrine_Collection $broadcast_segment_ageofchild_rels
 * @property Doctrine_Collection $broadcast_segment_country_rels
 * @property Doctrine_Collection $broadcast_segment_pregnancyweek_rels
 * @property Doctrine_Collection $broadcast_segment_user_rels
 * @property Doctrine_Collection $broadcast_segment_broadcast_type_rels
 * @property Doctrine_Collection $XBroadcastTemplate
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseXBroadcastSegment extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('x_broadcast_segment');
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
             'length' => '255',
             ));
        $this->hasColumn('child_gender', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'm',
              1 => 'f',
             ),
             ));
        $this->hasColumn('last_login_filter', 'string', 5000, array(
             'type' => 'string',
             'length' => '5000',
             ));
        $this->hasColumn('receipt_type_filter', 'string', 5000, array(
             'type' => 'string',
             'length' => '5000',
             ));
        $this->hasColumn('open_type_filter', 'string', 5000, array(
             'type' => 'string',
             'length' => '5000',
             ));
        $this->hasColumn('page_visit_filter', 'string', 5000, array(
             'type' => 'string',
             'length' => '5000',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('User as users', array(
             'refClass' => 'XBroadcastSegmentUser',
             'local' => 'broadcast_segment_id',
             'foreign' => 'user_id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('PregnancyWeek as weeks', array(
             'refClass' => 'XBroadcastSegmentPregnancyWeek',
             'local' => 'broadcast_segment_id',
             'foreign' => 'pregnancyweek_id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('AgeOfChild as ages', array(
             'refClass' => 'XBroadcastSegmentAgeOfChild',
             'local' => 'broadcast_segment_id',
             'foreign' => 'ageofchild_id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('Country as countries', array(
             'refClass' => 'XBroadcastSegmentCountry',
             'local' => 'broadcast_segment_id',
             'foreign' => 'country_id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('XBroadcastType as broadcast_types', array(
             'refClass' => 'XBroadcastSegmentXBroadcastType',
             'local' => 'broadcast_segment_id',
             'foreign' => 'broadcast_type_id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('XBroadcastSegmentAgeOfChild as broadcast_segment_ageofchild_rels', array(
             'local' => 'id',
             'foreign' => 'broadcast_segment_id'));

        $this->hasMany('XBroadcastSegmentCountry as broadcast_segment_country_rels', array(
             'local' => 'id',
             'foreign' => 'broadcast_segment_id'));

        $this->hasMany('XBroadcastSegmentPregnancyWeek as broadcast_segment_pregnancyweek_rels', array(
             'local' => 'id',
             'foreign' => 'broadcast_segment_id'));

        $this->hasMany('XBroadcastSegmentUser as broadcast_segment_user_rels', array(
             'local' => 'id',
             'foreign' => 'broadcast_segment_id'));

        $this->hasMany('XBroadcastSegmentXBroadcastType as broadcast_segment_broadcast_type_rels', array(
             'local' => 'id',
             'foreign' => 'broadcast_segment_id'));

        $this->hasMany('XBroadcastTemplate', array(
             'local' => 'id',
             'foreign' => 'segment_id'));
    }
}