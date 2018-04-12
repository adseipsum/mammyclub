<?php

/**
 * BaseProductBroadcast
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $subject
 * @property string $email_appeal
 * @property string $email_intro
 * @property string $email_main_text
 * @property string $email_outro
 * @property datetime $sent_datetime
 * @property boolean $is_sent
 * @property boolean $newsletter_recommended_products
 * @property boolean $newsletter_shop
 * @property boolean $exclude_who_buys_without_discount
 * @property enum $utm_source
 * @property string $age_of_child
 * @property Doctrine_Collection $countries
 * @property Doctrine_Collection $pregnancy_weeks
 * @property Doctrine_Collection $users
 * @property Doctrine_Collection $products
 * @property Doctrine_Collection $product_broadcast_country_rels
 * @property Doctrine_Collection $ProductBroadcastPregnancyWeek
 * @property Doctrine_Collection $product_broadcast_product_rels
 * @property Doctrine_Collection $product_broadcast_user_rels
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseProductBroadcast extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('product_broadcast');
        $this->hasColumn('id', 'integer', 4, array(
             'primary' => true,
             'autoincrement' => true,
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'msgprop' => 'Название',
             'length' => '255',
             ));
        $this->hasColumn('subject', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('email_appeal', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('email_intro', 'string', 5000, array(
             'type' => 'string',
             'length' => '5000',
             ));
        $this->hasColumn('email_main_text', 'string', 5000, array(
             'type' => 'string',
             'length' => '5000',
             ));
        $this->hasColumn('email_outro', 'string', 5000, array(
             'type' => 'string',
             'length' => '5000',
             ));
        $this->hasColumn('sent_datetime', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('is_sent', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('newsletter_recommended_products', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             'notnull' => true,
             'msgprop' => 'Отсылать только тем у кого отмечено: Получать рассылку Полезные покупки для беременных',
             ));
        $this->hasColumn('newsletter_shop', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             'notnull' => true,
             'msgprop' => 'Отсылать только тем у кого отмечено: Получать рассылку Акции на товары в магазине',
             ));
        $this->hasColumn('exclude_who_buys_without_discount', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             'notnull' => true,
             'msgprop' => 'Исключить пользователей покупающих без скидки',
             ));
        $this->hasColumn('utm_source', 'enum', null, array(
             'type' => 'enum',
             'default' => 'order_shipped',
             'values' => 
             array(
              0 => 'order_shipped',
              1 => 'thanks_purchase',
              2 => 'ask_review',
              3 => 'thanks_review',
              4 => 'new_arrivals',
              5 => 'sale',
             ),
             'msgprop' => 'Тип рассылки',
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
        $this->hasMany('Country as countries', array(
             'refClass' => 'ProductBroadcastCountry',
             'local' => 'product_broadcast_id',
             'foreign' => 'country_id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('PregnancyWeek as pregnancy_weeks', array(
             'refClass' => 'ProductBroadcastPregnancyWeek',
             'local' => 'product_broadcast_id',
             'foreign' => 'pregnancy_week_id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('User as users', array(
             'refClass' => 'ProductBroadcastUser',
             'local' => 'product_broadcast_id',
             'foreign' => 'user_id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('Product as products', array(
             'refClass' => 'ProductBroadcastProduct',
             'local' => 'product_broadcast_id',
             'foreign' => 'product_id',
             'onDelete' => 'SET NULL'));

        $this->hasMany('ProductBroadcastCountry as product_broadcast_country_rels', array(
             'local' => 'id',
             'foreign' => 'product_broadcast_id'));

        $this->hasMany('ProductBroadcastPregnancyWeek', array(
             'local' => 'id',
             'foreign' => 'product_broadcast_id'));

        $this->hasMany('ProductBroadcastProduct as product_broadcast_product_rels', array(
             'local' => 'id',
             'foreign' => 'product_broadcast_id'));

        $this->hasMany('ProductBroadcastUser as product_broadcast_user_rels', array(
             'local' => 'id',
             'foreign' => 'product_broadcast_id'));
    }
}