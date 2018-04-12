<?php
/*
 *  $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine_Import_ManagersBuilder
 *
 */
class Doctrine_Import_AdminControllersBuilder extends Doctrine_Import_Builder {

  /** Base Class. */
  protected $_baseManager = 'Base_Admin_Controller';
  
  /** Base Lang Class. */
  protected $_baseLangManager = 'Base_Admin_Lang_Controller';

  /**
   * overridden
   * @see lib/database/Doctrine/Import/Doctrine_Import_Builder::loadTemplate()
   */
  public function loadTemplate() {
    self::$_tpl = '/**'
    . '%s' . PHP_EOL
    . ' */' . PHP_EOL
    . '%s'
    . '%sclass %s extends %s {' . PHP_EOL
    . '%s' . PHP_EOL
    . '%s' . PHP_EOL
    . '}';
  }


  /**
   * overridden
   * @see lib/database/Doctrine/Import/Doctrine_Import_Builder::buildPhpDocs()
   */
  public function buildPhpDocs(array $definition) {
    $ret = array();
    $ret[] = $definition['className'];
    $ret[] = 'This class has been auto-generated by Itirra';
    $ret = ' * ' . implode(PHP_EOL . ' * ', $ret);
    $ret = ' ' . trim($ret);
    return $ret;
  }


  /**
   * overridden
   * @see lib/database/Doctrine/Import/Doctrine_Import_Builder::buildDefinition()
   */
  public function buildDefinition(array $definition, array $translation = array()) {
    if (!isset($definition['className'])) {
      throw new Doctrine_Import_Builder_Exception('Missing class name.');
    }
    $abstract = isset($definition['abstract']) && $definition['abstract'] === true ? 'abstract ':null;
    $className = $definition['className'];

    if (isset($definition['actAs']) && !empty($definition['actAs']) && is_array($definition['actAs']) && in_array('NestedSet', array_keys($definition['actAs']))) {
      if (is_array($definition['actAs']['NestedSet']) && $definition['actAs']['NestedSet']['hasManyRoots'] == 1) {
        if (!empty($translation)) {
          $extends = 'Base_Admin_Lang_Tree_Controller';
        } else {
          $extends = 'Base_Admin_Tree_Controller';
        }
      } else {
        if (!empty($translation)) {
          $extends = 'Base_Admin_Lang_Tree_Controller';
        } else {
          $extends = 'Base_Admin_Simple_Tree_Controller';
        }
      }
    } else {
      //      $extends = isset($definition['inheritance']['extends']) ? $definition['inheritance']['extends'] . 'Manager': $this->_baseManager;
      if (!empty($translation)) {
        $extends = $this->_baseLangManager;
      } else {
        $extends = $this->_baseManager;
      }
    }

    if (!(isset($definition['no_definition']) && $definition['no_definition'] === TRUE)) {
      $tableDefinitionCode = $this->buildTableDefinition($definition);
      $setUpCode = '';//$this->buildSetUp($definition);
    } else {
      $tableDefinitionCode = null;
      $setUpCode = null;
    }

    $docs = PHP_EOL . $this->buildPhpDocs($definition);

    $require = "require_once APPPATH . 'controllers/admin/base/" . strtolower($extends) . ".php';" . PHP_EOL;

    $content = sprintf(self::$_tpl, $docs, $require, $abstract,
    $className,
    $extends,
    $tableDefinitionCode,
    $setUpCode);

    return $content;
  }

  /**
   * BuildTableDefinition
   * @param $definition
   * @return string
   */
  public function buildTableDefinition(array $definition) {
    $code = '';
    $properties = $this->buildPropertiesForController($definition);
//    $code = $this->buildFieldsForManager($definition);
//    $code = trim($code);
//    if ($code) {
//      $code1 = PHP_EOL . '  /** Fields. */';
//      $code = $code1 . PHP_EOL . '  public $fields = array(' . $code;
//    }
    if (!empty($properties)) {
      $code = $properties . PHP_EOL . $code;
    }

    return $code;
  }




  /**
   * BuildPropertiesForController
   * @param array $definition
   * @return string
   */
  public function buildPropertiesForController(array $definition) {
    $code = PHP_EOL;
    $columns = $definition['columns'];
    $importExcludeFields = array();
    $exportExcludeFields = array();
    $filters = array();
    $dateFilters = array();
    $additionalItemActions = array();
    $searchParams = array();
    foreach ($columns as $name => $column) {

      // Add "view" action to all entities with page_url
      if ($name == 'page_url') {
        $additionalItemActions[] = 'view';
        $importExcludeFields[] = 'page_url';
      }
      // Add search to all entities with name
      if ($name == 'name') {
        $searchParams[] = 'name';
      }
      // Add enums and booleans to filters
      if ($column['type'] == 'enum' || $column['type'] == 'boolean') {
        $filters[$name] = '';
      }
      // Add dates to date filters
      if ($column['type'] == 'timestamp' || $column['type'] == 'datetime') {
        $dateFilters[] = $name;
      }
    }

    // Add created_at to date filters
    $isTimeStampable = is_array($definition['actAs']) && array_key_exists('Timestampable', $definition['actAs']);
    if ($isTimeStampable) {
      $field = "created";
      // created is disabled ?
      if(isset($definition['actAs']['Timestampable']['created']['disabled'])) {
        $field = 'updated';
      }
      // default field name or the defined one?
      if(isset($definition['actAs']['Timestampable'][$field]['name'])) {
        $field = $definition['actAs']['Timestampable'][$field]['name'];
      } else {
        $field .= '_at';
      }
       $dateFilters[] = $field;
    }

    $relations = array();
    if (isset($definition['relations'])) {
      foreach ($definition['relations'] as $relName => $rel) {
        if ($rel['class'] == 'Image' || $rel['class'] == 'Resource') {
          $importExcludeFields[] = $relName;
          $exportExcludeFields[] = $relName;
        } else if ($rel['class'] != 'Header') {
          if ($rel['type'] == 0) {
            $relations[] = $relName . '.id';
          } elseif (isset($rel['refClass'])) {
            $relations[] = $relName . '.id';
          }
        }
      }
    }
    foreach ($relations as $rel) {
      $filters[$rel] = '';
    }
    if (!empty($additionalItemActions)) {
      $code .= $this->buildProperty('additionalItemActions', $additionalItemActions) . PHP_EOL . PHP_EOL;
    }

    if (!empty($filters)) {
      $code .= $this->buildProperty('filters', $filters) . PHP_EOL . PHP_EOL;
    }

    if (!empty($dateFilters)) {
      $code .= $this->buildProperty('dateFilters', $dateFilters) . PHP_EOL . PHP_EOL;
    }

    if (!empty($searchParams)) {
      $code .= $this->buildProperty('searchParams', $searchParams) . PHP_EOL . PHP_EOL;
    }

    $code .= $this->buildProperty('import', TRUE) . PHP_EOL . PHP_EOL;

    $code .= $this->buildProperty('export', TRUE) . PHP_EOL . PHP_EOL;

    if (!empty($importExcludeFields)) {
      $code .= $this->buildProperty('importExcludeFields', $importExcludeFields) . PHP_EOL . PHP_EOL;
    }
    if (!empty($exportExcludeFields)) {
      $code .= $this->buildProperty('exportExcludeFields', $exportExcludeFields) . PHP_EOL . PHP_EOL;
    }

    return $code;
  }

  /**
   * BuildProperty
   * @param $name
   * @param $value
   * @param $type
   * @param $prefix
   * @return string
   */
  private function buildProperty($name, $value, $type = "protected", $prefix = '  ') {
    $phpDoc = '/** ' . ucfirst($name) . '. */' . PHP_EOL;
    switch ($value) {
      case is_string($value):
        $value = '"' . $value . '"';
        break;
      case is_bool($value):
        if ($value) {
          $value = "TRUE";
        } else {
          $value = "FALSE";
        }
        break;
      case is_array($value):
        $val = 'array(';
        if (count($value) > 3) {
          $margin = str_repeat(' ', strlen($prefix . $type . " $" . $name . " = array("));
          $count = 0;
          foreach ($value as $k => $v) {
            if (is_numeric($k)) {
              if ($count > 0) {
                $val .= $margin . '"' . $v . '", ';
              } else {
                $val .= '"' . $v . '", ';
              }
            } else {
              if ($count > 0) {
                $val .= $margin . '"' . $k . '" => "' . $v . '", ';
              } else {
                $val .= '"' . $k . '" => "' . $v . '", ';
              }
            }
            $val .= PHP_EOL;
            $count++;
          }
        } else {
          foreach ($value as $k => $v) {
            if (is_numeric($k)) {
              $val .= '"' . $v . '", ';
            } else {
              $val .= '"' . $k . '" => "' . $v . '", ';
            }
          }
        }
        $val = rtrim(trim(rtrim($val, PHP_EOL)), ',');
        $val .= ')';
        $value = $val;
        break;
    }
    $code = $prefix . $phpDoc . $prefix . $type . " $" . $name . " = " . $value . ";";
    return $code;
  }


  /**
   * (non-PHPdoc)
   * @see Doctrine_Import_Builder::buildRecord()
   */
  public function buildRecord(array $definition, array $translation = array()) {
    if ( ! isset($definition['className'])) {
      throw new Doctrine_Import_Builder_Exception('Missing class name.');
    }
    $definition['topLevelClassName'] = $definition['className'];
    $this->writeDefinition($definition, $translation);
  }

  /**
   * Return the file name of the class to be generated.
   *
   * @param string $originalClassName
   * @param array $definition
   * @return string
   */
  protected function _getFileName($originalClassName, $definition) {
    if ($this->_classPrefixFiles) {
      $fileName = strtolower($definition['className']) . $this->_suffix;
    } else {
      $fileName = $originalClassName . $this->_suffix;
    }
    if ($this->_pearStyle) {
      $fileName = str_replace('_', '/', $fileName);
    }
    return $fileName;
  }

  /**
   * overridden
   * @see lib/database/Doctrine/Import/Doctrine_Import_Builder::writeDefinition()
   */
  public function writeDefinition(array $definition, array $translation = array()) {
    if (isset($definition['package']) && !empty($definition['package'])) {
      // Dont generate admin controllers for common entities
      return;
    }

    // DON'T GENERATE CONTROLLERS FOR MANY-TO-MANY TABLES
    $columns = $definition['columns'];
    if(!isset($columns['name'])) {
      // NO "NAME" -> MAYBE MANY-TO-MANY TABLE! Check for multiple PKS
      $pks = array();
      foreach ($columns as $cName => $cVal) {
        if (isset($cVal['primary']) && $cVal['primary'] == TRUE) {
          $pks[] = $cName;
        }
      }
      if (count($pks) > 1) {
        return;
      }
    }

    // DON'T GENERATE CONTROLLERS FOR TRANSLATION TABLES
    if (strpos(strtolower($definition['className']), 'translation') !== FALSE) {
      return;
    }
    
    $originalClassName = $definition['className'];
    $fileName = $this->_getFileName($originalClassName, $definition);
    Doctrine_Lib::makeDirectories($this->_path);
    $writePath = $this->_path . '/' . $fileName;

    if (isset($definition['generate_once']) && $definition['generate_once'] === TRUE) {
      if (!file_exists($writePath)) {
        $definitionCode = $this->buildDefinition($definition, $translation);
        $code = '<?php if (!defined("BASEPATH")) exit("No direct script access allowed");';;
        $code .= PHP_EOL . $definitionCode;
        if ($this->_eolStyle) {
          $code = str_replace(PHP_EOL, $this->_eolStyle, $code);
        }
        Doctrine_Lib::makeDirectories(dirname($writePath));
        $bytes = file_put_contents($writePath, $code);
      }
    } else {
      $definitionCode = $this->buildDefinition($definition);
      $code = '<?php if (!defined("BASEPATH")) exit("No direct script access allowed");';;
      $code .= PHP_EOL . $definitionCode;
      if ($this->_eolStyle) {
        $code = str_replace(PHP_EOL, $this->_eolStyle, $code);
      }
      Doctrine_Lib::makeDirectories(dirname($writePath));
      $bytes = file_put_contents($writePath, $code);
    }
    if (isset($bytes) && $bytes === false) {
      throw new Doctrine_Import_Builder_Exception("Couldn't write file " . $writePath);
    }
  }
}