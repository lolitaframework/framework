<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Core\Data;
use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Configuration\IModule;

class Customs implements IModule
{

    /**
     * Save the data list
     *
     * @var array
     */
    private $data = array();

    /**
     * Folders to loading classes
     *
     * @var array
     */
    private $folders = array();

    /**
     * All classes in folders.
     *
     * @var array
     */
    private $classes = array();

    /**
     * Customs class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $data config file data.
     * @return void
     */
    public function __construct($data = null)
    {
        // Save data
        $this->data = $data;

        // Autoload classes
        $this->folders = $this->getFolders();
        $this->classes = $this->getClasses();

        spl_autoload_register(array( &$this, 'autoload' ));
    }

    /**
     * Autoload my classes
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return void
     */
    public function autoload($class)
    {
        $class = str_replace('\\', '/', $class);
        $class = basename($class);
        if (array_key_exists($class, $this->classes)) {
            if (file_exists($this->classes[$class])) {
                require_once $this->classes[$class];
            }
        }
    }

    /**
     * Get all classes from folders.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [array] classes.
     */
    private function getClasses()
    {
        $classes          = array();
        $class_dictionary = array();

        foreach ($this->folders as $folder) {
            $pattern = untrailingslashit($folder) . DS . '*.php';
            $classes = array_merge($classes, (array) glob($pattern));
        }

        foreach ($classes as $key => $value) {
            $name = pathinfo(basename($value), PATHINFO_FILENAME);
            $class_dictionary[ $name ] = $value;
        }
        return $class_dictionary;
    }

    /**
     * Get all folders
     * Where we want load classes
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [array] folders paths.
     */
    private function getFolders()
    {
        $folders = array();
        if (is_array($this->data)) {
            foreach ($this->data as $path) {
                array_push($folders, Data::interpret($path));
            }
        }
        return $folders;
    }

    /**
     * Module priority
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority()
    {
        return 99;
    }
}
