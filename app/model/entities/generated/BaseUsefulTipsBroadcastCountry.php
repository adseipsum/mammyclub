<?php

/**
 * BaseUsefulTipsBroadcastCountry
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $country_id
 * @property integer $useful_tips_broadcast_id
 * @property Country $countries
 * @property UsefulTipsBroadcast $useful_tips_broadcast
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUsefulTipsBroadcastCountry extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('useful_tips_broadcast_country');
        $this->hasColumn('country_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('useful_tips_broadcast_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Country as countries', array(
             'local' => 'country_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('UsefulTipsBroadcast as useful_tips_broadcast', array(
             'local' => 'useful_tips_broadcast_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}