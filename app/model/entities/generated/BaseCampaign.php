<?php

/**
 * BaseCampaign
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $priority
 * @property date $end_date
 * @property boolean $published
 * @property string $banned_countries
 * @property string $allowed_sections
 * @property string $slot_top_banner
 * @property string $slot_right_banner
 * @property string $slot_text_top
 * @property string $slot_text_bot
 * @property string $slot_text_comments_bot
 * @property string $slot_tt1
 * @property string $slot_tt2
 * @property string $slot_tt3
 * @property string $slot_head_section
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCampaign extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('campaign');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'msgprop' => 'Название',
             'length' => '255',
             ));
        $this->hasColumn('priority', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'msgprop' => 'Приоритет',
             'length' => '4',
             ));
        $this->hasColumn('end_date', 'date', null, array(
             'type' => 'date',
             'msgprop' => 'Дата Окончания',
             ));
        $this->hasColumn('published', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 1,
             'notnull' => true,
             'msgprop' => 'Опубликовано',
             ));
        $this->hasColumn('banned_countries', 'string', 255, array(
             'type' => 'string',
             'msgprop' => 'Не показывать для стран',
             'length' => '255',
             ));
        $this->hasColumn('allowed_sections', 'string', 5000, array(
             'type' => 'string',
             'msgprop' => 'Разделы на которых будут отображаться баннеры',
             'length' => '5000',
             ));
        $this->hasColumn('slot_top_banner', 'string', 5000, array(
             'type' => 'string',
             'msgprop' => 'Верхний Баннер {TOP_BANNER}',
             'length' => '5000',
             ));
        $this->hasColumn('slot_right_banner', 'string', 5000, array(
             'type' => 'string',
             'msgprop' => 'Верхний Баннер {RIGHT_BANNER}',
             'length' => '5000',
             ));
        $this->hasColumn('slot_text_top', 'string', 5000, array(
             'type' => 'string',
             'msgprop' => 'Текст Верх {TEXT_TOP}',
             'length' => '5000',
             ));
        $this->hasColumn('slot_text_bot', 'string', 5000, array(
             'type' => 'string',
             'msgprop' => 'Текст Низ {ТEXT_BOT}',
             'length' => '5000',
             ));
        $this->hasColumn('slot_text_comments_bot', 'string', 5000, array(
             'type' => 'string',
             'msgprop' => 'Текст Низ Комментов {TEXT_COMMENTS_BOT}',
             'length' => '5000',
             ));
        $this->hasColumn('slot_tt1', 'string', 5000, array(
             'type' => 'string',
             'msgprop' => 'Текст Тэг 1 {ТТ1}',
             'length' => '5000',
             ));
        $this->hasColumn('slot_tt2', 'string', 5000, array(
             'type' => 'string',
             'msgprop' => 'Текст Тэг 2 {ТТ2}',
             'length' => '5000',
             ));
        $this->hasColumn('slot_tt3', 'string', 5000, array(
             'type' => 'string',
             'msgprop' => 'Текст Тэг 3 {ТТ3}',
             'length' => '5000',
             ));
        $this->hasColumn('slot_head_section', 'string', 5000, array(
             'type' => 'string',
             'msgprop' => 'Head блок',
             'length' => '5000',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}