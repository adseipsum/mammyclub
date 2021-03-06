<?php

/**
 * BaseMandrillBroadcastVisitedLink
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $recipient_id
 * @property integer $link_id
 * @property integer $broadcast_id
 * @property MandrillBroadcastRecipient $recipient
 * @property MandrillBroadcastLink $link
 * @property MandrillBroadcast $broadcast
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseMandrillBroadcastVisitedLink extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('mandrill_broadcast_visited_link');
        $this->hasColumn('recipient_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('link_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('broadcast_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('MandrillBroadcastRecipient as recipient', array(
             'local' => 'recipient_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('MandrillBroadcastLink as link', array(
             'local' => 'link_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('MandrillBroadcast as broadcast', array(
             'local' => 'broadcast_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $timestampable0 = new Doctrine_Template_Timestampable(array(
             'updated' => 
             array(
              'disabled' => true,
             ),
             ));
        $this->actAs($timestampable0);
    }
}