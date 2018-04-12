<?php
class Doctrine_FiltersSluggable
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

    $text = str_replace(array(',', '.'), '-', $text);
    
    //lang_url implementation:
    $string = $text;
    $string = trim(strip_tags($string));
    $string = to_translit($string);
    $string = strtolower($string);
    $string = preg_replace("/[^a-zA-Z0-9\s-]/", "", $string); //remove all non-alphanumeric characters except the hyphen
    $string = str_replace("\n", "", $string);
    $string = str_replace("\r", "", $string);
    $string = str_replace(' ', '-', $string);
    $string = preg_replace("/[-]{2,}/", "", $string);  //replace multiple instances of the hyphen with a single instance
    $text = $string;
    
    // Remove all non url friendly characters with the unaccent function
    $text = Doctrine_Inflector::unaccent($text);
    
    if (function_exists('mb_strtolower')) {
      $text = mb_strtolower($text);
    } else {
      $text = strtolower($text);
    }
    
    // Remove all none word characters
    $text = preg_replace('/[^A-Za-z0-9\-]/', ' ', $text);
    
    // More stripping. Replace spaces with dashes
    $text = strtolower(preg_replace('/[^A-Z^a-z^0-9^\/]+/', '-',
    preg_replace('/([a-z\d])([A-Z])/', '\1_\2',
    preg_replace('/([A-Z]+)([A-Z][a-z])/', '\1_\2',
    preg_replace('/::/', '/', $text)))));
    
    return trim($text, '-');
    
  }
}