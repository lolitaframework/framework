<?php
namespace redbrook\LolitaFramework\Widgets;

use \redbrook\LolitaFramework\Core\GlobalLocator as GlobalLocator;
use \redbrook\LolitaFramework\Core\HelperClass as HelperClass;

class Widgets
{
    /**
     * Loaded data.
     * @var array
     */
    private $data = array();

    /**
     * All loaded widgets
     *
     * @var null
     */
    private $loaded_widgets = null;

    /**
     * Widgets class constructor
     */
    public function __construct()
    {
        $this->data = $this->getAllClasses();
        add_action('widgets_init', array($this, 'load'));
    }

    /**
     * Get all paths to classes
     *
     * @return array
     */
    public static function getAllClasses()
    {
        $result  = array();
        $folders = (array) glob(dirname(__FILE__) . '/*', GLOB_ONLYDIR);
        foreach ($folders as $folder) {
            $class_name = NS . __NAMESPACE__ . NS . basename($folder) . NS . basename($folder);
            if (class_exists($class_name)) {
                $class_candidate = new \ReflectionClass($class_name);
                if (false === $class_candidate->isAbstract()) {
                    $result[] = $class_name;
                }
            }
        }
        return $result;
    }

    /**
     * Load classes
     */
    public function load()
    {
        foreach ($this->data as $class) {
            register_widget($class);
        }
    }
}
