<?php

/**
 * BaseAuthInfo
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $email
 * @property boolean $email_confirmed
 * @property string $activation_key
 * @property string $password
 * @property string $phone
 * @property boolean $phone_confirmed
 * @property boolean $banned
 * @property string $banned_reason
 * @property string $last_ip
 * @property timestamp $last_login
 * @property string $facebook_id
 * @property string $vkontakte_id
 * @property string $gmail_id
 * @property string $mailru_id
 * @property User $User
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseAuthInfo extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('auth_info');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('email', 'string', 255, array(
             'type' => 'string',
             'unique' => true,
             'length' => '255',
             ));
        $this->hasColumn('email_confirmed', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             'notnull' => true,
             ));
        $this->hasColumn('activation_key', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('password', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('phone', 'string', 255, array(
             'type' => 'string',
             'unique' => true,
             'length' => '255',
             ));
        $this->hasColumn('phone_confirmed', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             'notnull' => true,
             ));
        $this->hasColumn('banned', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             'notnull' => true,
             ));
        $this->hasColumn('banned_reason', 'string', 1000, array(
             'type' => 'string',
             'length' => '1000',
             ));
        $this->hasColumn('last_ip', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('last_login', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('facebook_id', 'string', 30, array(
             'type' => 'string',
             'unique' => true,
             'length' => '30',
             ));
        $this->hasColumn('vkontakte_id', 'string', 30, array(
             'type' => 'string',
             'unique' => true,
             'length' => '30',
             ));
        $this->hasColumn('gmail_id', 'string', 140, array(
             'type' => 'string',
             'unique' => true,
             'length' => '140',
             ));
        $this->hasColumn('mailru_id', 'string', 30, array(
             'type' => 'string',
             'unique' => true,
             'length' => '30',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'id',
             'foreign' => 'auth_info_id'));

        $timestampable0 = new Doctrine_Template_Timestampable(array(
             'updated' => 
             array(
              'disabled' => true,
             ),
             ));
        $this->actAs($timestampable0);
    }
}