<?php
/*
 *  $Id: BuildAll.php 2761 2007-10-07 23:42:29Z zYne $
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
 * Doctrine_Task_BuildAll
 *
 * @package     Doctrine
 * @subpackage  Task
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision: 2761 $
 * @author      Jonathan H. Wage <jwage@mac.com>
 */
class Doctrine_Task_BuildAll extends Doctrine_Task
{
    public $description          =   'Calls generate-models-from-yaml, create-db, and create-tables',
            $requiredArguments    =   array(),
            $optionalArguments    =   array();

    protected $models,
               $managers,
               $adminControllers,
               $tables;

    public function __construct($dispatcher = null)
    {
        parent::__construct($dispatcher);

        $this->managers = new Doctrine_Task_GenerateManagersYaml($this->dispatcher);
        $this->adminControllers = new Doctrine_Task_GenerateAdminControllersYaml($this->dispatcher);
        $this->models = new Doctrine_Task_GenerateModelsYaml($this->dispatcher);
        $this->createDb = new Doctrine_Task_CreateDb($this->dispatcher);
        $this->tables = new Doctrine_Task_CreateTables($this->dispatcher);

        $this->requiredArguments = array_merge($this->requiredArguments, $this->models->requiredArguments, $this->managers->requiredArguments, $this->adminControllers->requiredArguments, $this->createDb->requiredArguments, $this->tables->requiredArguments);
        $this->optionalArguments = array_merge($this->optionalArguments, $this->models->optionalArguments, $this->managers->optionalArguments, $this->adminControllers->optionalArguments, $this->createDb->optionalArguments, $this->tables->optionalArguments);
    }



    public function execute()
    {

// //    TRY BY DENIS
//       try {

// //      ------------------------------ CODE BY DENIS START ---------------------------------------
//         $migration = new Doctrine_Migration(APPPATH . 'doctrine/migrations', $connection);


//         $models_paths[] = $this->getArgument('models_path') . '/';
//         $models_paths[] = $this->getArgument('models_path') . '/generated/';
//         $models_paths[] = $this->getArgument('models_path') . '/common' . '/' . 'generated/';
//         foreach ($models_paths as $model_path) {
//           $this->changeExt($model_path, 'php', 'bak');
//         }

// //      ------------------------------ CODE BY DENIS END -----------------------------------------

        $this->models->setArguments($this->getArguments());
        $this->models->execute();

        $this->managers->setArguments($this->getArguments());
        $this->managers->execute();

        $this->adminControllers->setArguments($this->getArguments());
        $this->adminControllers->execute();

        $this->createDb->setArguments($this->getArguments());
        $this->createDb->execute();

        $this->tables->setArguments($this->getArguments());
        $this->tables->execute();

// //      ------------------------------ CODE BY DENIS START ---------------------------------------
//         foreach ($models_paths as $model_path) {
//           $this->deleteExt($model_path, 'bak');
//         }
// //      ------------------------------ CODE BY DENIS END -----------------------------------------

// //      ------------------------------ CODE BY DENIS START ---------------------------------------
//         $latestVersion = $migration->getLatestVersion();
//         $migration->_createMigrationTable;
//         $migration->setCurrentVersion($latestVersion);
// //      ------------------------------ CODE BY DENIS END -----------------------------------------

        if (file_exists(APPPATH . 'doctrine/migrations/versions.out')) {
          unlink(APPPATH . 'doctrine/migrations/versions.out');
        }

// //      CATCH BY DENIS
//         } catch (Exception $e) {
//           // Delete all php files and recover from *.bak
//           foreach ($models_paths as $model_path) {
//             $this->deleteExt($model_path, 'php');
//             $this->changeExt($model_path, 'bak', 'php');
//           }
//           // Get migrations version from Ant output file
//           $versions = file_get_contents(APPPATH . 'doctrine/migrations/versions.out');
//           preg_match('/^version\[s\]=(.*)./',$versions,$versions);
//           $versions = explode(',', $versions[1]);

//           // Delete migrations and Ant .out file
//           foreach ($versions as $version) {
//             $migration->deleteMigration($version);
//           }
//           if (file_exists(APPPATH . 'doctrine/migrations/versions.out')) {
//             unlink(APPPATH . 'doctrine/migrations/versions.out');
//           }
//           die($e->getMessage());
//         }
    }



    /*
     * FUNCTION BY DENIS
     * Change files extension
     */
    function changeExt($source, $ext, $newExt) {
      if (is_dir($source) === TRUE) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::LEAVES_ONLY);
        $files->setMaxDepth(0);
        foreach ($files as $file) {
          $file = realpath($file);
          $path_parts = pathinfo($file);
          if ($path_parts['extension'] == $ext) {
            $newname = $path_parts['dirname'] . '/' . $path_parts['filename'] . '.' . $newExt;
            rename($file, $newname);
          }
        }
        return TRUE;
      }
      return FALSE;
    }


    /*
     *  Delete files by extension
     */
    function deleteExt($source, $ext)
    {
      if (is_dir($source) === true)
      {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::LEAVES_ONLY);
        $files->setMaxDepth(0);
        foreach ($files as $file)
        {
          $file = realpath($file);
          $path_parts = pathinfo($file);
          if ($path_parts['extension'] == $ext) {
            unlink($path_parts['dirname'].'/'.$path_parts['filename'].'.'.$path_parts['extension']);
          }
        }
        return true;
      }
      return false;
    }
}