<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * ArticleUserManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class ArticleUserManager extends BaseManager {

  /** Primary Key Field. */
  protected $pk = array("article_id", "user_id");


  /** Fields. */
  public $fields = array("Article" => array("type" => "select", "relation" => array("entity_name" => "Article")),
                         "User" => array("type" => "select", "relation" => array("entity_name" => "User")));

  /** List params. */
  public $listParams = array("Article.name", "User.name");

  /**
   * PreProcessWhereQuery.
   * OVERRIDE THIS METHOD IN A SUBCLASS TO ADD joins and other stuff.
   * @param Doctrine_Query $query
   * @return Doctrine_Query
   */
  protected function preProcessWhereQuery($query, $pref, $what = '*') {
    $query = parent::preProcessWhereQuery($query, $pref, $what);
    if (strpos($what, 'article.') !== FALSE || $what == '*') {
      $query->addSelect("articles.*")->leftJoin($pref . ".Article articles");
    }

    return $query;
  }

}