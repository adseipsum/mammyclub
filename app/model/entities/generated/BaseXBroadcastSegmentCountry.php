<?php

/**
 * BaseXBroadcastSegmentCountry
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $broadcast_segment_id
 * @property integer $country_id
 * @property XBroadcastSegment $broadcast_segment
 * @property Country $country
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseXBroadcastSegmentCountry extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('x_broadcast_segment_country');
        $this->hasColumn('broadcast_segment_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('country_id', 'integer', 4, array(
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

        $this->hasOne('Country as country', array(
             'local' => 'country_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}