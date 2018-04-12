<?php

/**
 * BaseXBroadcastSegmentPregnancyWeek
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $broadcast_segment_id
 * @property integer $pregnancyweek_id
 * @property XBroadcastSegment $broadcast_segment
 * @property PregnancyWeek $pregnancyweek
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseXBroadcastSegmentPregnancyWeek extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('x_broadcast_segment_pregnancy_week');
        $this->hasColumn('broadcast_segment_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('pregnancyweek_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('XBroadcastSegment as broadcast_segment', array(
             'local' => 'broadcast_segment_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('PregnancyWeek as pregnancyweek', array(
             'local' => 'pregnancyweek_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}