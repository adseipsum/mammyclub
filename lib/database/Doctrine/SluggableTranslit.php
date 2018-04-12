<?php
class Doctrine_SluggableTranslit
{
  /**
   * Convert any passed string to a url friendly string. Converts 'My first blog post' to 'my-first-blog-post'
   *
   * @param  string $text  Text to urlize
   * @return string $text  Urlized text
   */
  static public function urlize($text)
  {
    require_once APPPATH . "helpers/common/itirra_commons_helper.php";
    require_once APPPATH . "helpers/common/itirra_language_helper.php";
    return Doctrine_Inflector::urlize(lang_url($text));
  }
}