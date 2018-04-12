<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ImageOptimizerWrapper library
 * Itirra - http://itirra.com
 */
abstract class ImageOptimizerWrapper {

  /** $processedCount @var integer */
  public static $processedCount = 0;

  /** $factory @var object */
  private static $factory;

  /** $binariesInclude @var array */
  private static $binariesInclude = array('jpegoptim', 'pngquant', 'gifsicle');

  /** $options @var array */
  private static $options = array('ignore_errors' => false,
                                  'jpegoptim_options' => array('--strip-all', '--all-progressive', '--max=85'),
                                  'pngquant_options' => array('--force'),
                                  'gifsicle_options' => array('-b', '-O3'));

  /**
   * $debug @var bool
   */
  private static $debug = false;

  /**
   * optimize
   * @param string $filePath
   */
  public static function optimize($filePath) {
    if (empty(self::$factory)) {
      self::init();
    }

    try {
      $basicSize = filesize($filePath);
      self::$factory->get()->optimize($filePath);
      if (self::$debug) {
        clearstatcache();
        log_message('debug', '[ImageOptimizer -> optimize] - image optimized from ' . $basicSize/1024 . ' kb to ' . filesize($filePath)/1024 . ' kb; (' . round(100 - filesize($filePath)/$basicSize*100, 2). '%) ext: ' . array_pop(explode('.', $filePath)));
      }
      self::$processedCount++;
    } catch (Exception $e) {
      if (self::$debug) {
        log_message('error', '[ImageOptimizer -> optimize] - exception: ' . $e->getMessage());
      }
    }
  }

  /**
   * init
   * @param array $customOptions
   */
  private static function init($customOptions = array()) {
    if (!empty(self::$binariesInclude)) {
      $pathToBinaries = BASEPATH . 'vendor/image-optimizers-bin/{binName}.exe';
      if (ENV == 'PROD') {
        $pathToBinaries = '/usr/local/bin/{binName}';
      }
      foreach (self::$binariesInclude as $b) {
        self::$options[$b . '_bin'] = str_replace('{binName}', $b, $pathToBinaries);
      }
    }
    if (!empty($customOptions)) {
      self::$options = array_merge(self::$options, $customOptions);
    }
    self::$factory = new \ImageOptimizer\OptimizerFactory(self::$options);
  }
}
?>