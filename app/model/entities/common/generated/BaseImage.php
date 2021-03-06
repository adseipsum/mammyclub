<?php

/**
 * BaseImage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property Doctrine_Collection $Article
 * @property Doctrine_Collection $ArticleCategory
 * @property Doctrine_Collection $ArticleExample
 * @property Doctrine_Collection $DefaultAvatar
 * @property Doctrine_Collection $ParameterGroup
 * @property Doctrine_Collection $PregnancyArticle
 * @property Doctrine_Collection $Product
 * @property Doctrine_Collection $ProductBrand
 * @property Doctrine_Collection $ProductColor
 * @property Doctrine_Collection $ProductImage
 * @property Doctrine_Collection $ProductParamImage
 * @property Doctrine_Collection $Team
 * @property Doctrine_Collection $User
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseImage extends Resource
{
    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Article', array(
             'local' => 'id',
             'foreign' => 'image_id'));

        $this->hasMany('ArticleCategory', array(
             'local' => 'id',
             'foreign' => 'image_id'));

        $this->hasMany('ArticleExample', array(
             'local' => 'id',
             'foreign' => 'image_id'));

        $this->hasMany('DefaultAvatar', array(
             'local' => 'id',
             'foreign' => 'image_id'));

        $this->hasMany('ParameterGroup', array(
             'local' => 'id',
             'foreign' => 'image_id'));

        $this->hasMany('PregnancyArticle', array(
             'local' => 'id',
             'foreign' => 'image_id'));

        $this->hasMany('Product', array(
             'local' => 'id',
             'foreign' => 'image_id'));

        $this->hasMany('ProductBrand', array(
             'local' => 'id',
             'foreign' => 'image_id'));

        $this->hasMany('ProductColor', array(
             'local' => 'id',
             'foreign' => 'image_id'));

        $this->hasMany('ProductImage', array(
             'local' => 'id',
             'foreign' => 'image_id'));

        $this->hasMany('ProductParamImage', array(
             'local' => 'id',
             'foreign' => 'image_id'));

        $this->hasMany('Team', array(
             'local' => 'id',
             'foreign' => 'image_id'));

        $this->hasMany('User', array(
             'local' => 'id',
             'foreign' => 'image_id'));
    }
}