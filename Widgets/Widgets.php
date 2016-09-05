<?php
namespace MyProject\LolitaFramework\Widgets;

use \MyProject\LolitaFramework\Core\Loc;
use \MyProject\LolitaFramework\Core\Str;
use \MyProject\LolitaFramework\Controls\Controls;
use \MyProject\LolitaFramework;

class Widgets
{

    /**
     * Paths to widgets JSON file
     * @var null
     */
    private $paths = null;

    /**
     * JSONS data in array type
     * @var null
     */
    private $parsers = null;

    /**
     * Prepared data
     * @var null
     */
    private $data = null;

    /**
     * All loaded widgets
     * @var null
     */
    private $widgets = null;

    /**
     * Widgets class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct()
    {
        $this->allWidgetsPaths()->parse()->init();
        add_action('widgets_init', array($this, 'register'));
    }

    /**
     * Init widgets classes
     *
     * @return Widgets instance.
     */
    private function init()
    {
        if (is_array($this->parsers)) {
            foreach ($this->parsers as $parser) {
                $class_name = __NAMESPACE__ . NS . 'Widget';
                $reflection = new \ReflectionClass($class_name);
                $widget     = $reflection->newInstanceArgs(
                    $this->prepareClassParameters($class_name, $parser->data())
                );
                $this->widgets[] = $widget;
                if ($widget->controls) {
                    Controls::loadScriptsAndStyles($widget->controls);
                }
            }
        }
        return $this;
    }

    /**
     * Load classes
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function register()
    {
        if (is_array($this->parsers)) {
            foreach ($this->widgets as $widget) {
                $widget->_register();
            }
        }
    }

    /**
     * Prepare class parameters
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $class_name class name.
     * @param  array  $parameters class parameters.
     * @return array  prepared parameters.
     */
    private function prepareClassParameters($class_name, array $parameters = array())
    {
        $return = array();
        if (!class_exists($class_name)) {
            throw new \Exception("Class [$class_name] doesn't exists!");
        }
        $reflection     = new \ReflectionClass($class_name);
        $constructor    = $reflection->getConstructor();
        $default_params = $constructor->getParameters();
        foreach ($default_params as $parameter) {
            if (array_key_exists($parameter->getName(), $parameters)) {
                array_push($return, $parameters[ $parameter->getName() ]);
            } else if ($parameter->isDefaultValueAvailable()) {
                array_push($return, $parameter->getDefaultValue());
            } else {
                throw new \Exception("Parameter [{$parameter->getName()}] is required!");
            }
        }

        return $return;
    }

    /**
     * Get default widgets settings path
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return default widgets settings path.
     */
    private function defaultPath()
    {
        return apply_filters(
            'lf_widgets_settings_path',
            LolitaFramework::dir() . '/app' . DS . 'widgets' . DS
        );
    }

    /**
     * Get all widgets jsons
     *
     * @return Widgets instance.
     */
    private function allWidgetsPaths()
    {
        $folders = (array) glob($this->defaultPath() . '*', GLOB_ONLYDIR);
        foreach ($folders as $folder) {
            $json_path = $this->jsonPath($folder);
            if (false !== $json_path) {
                $this->paths[] = $json_path;
            }
        }
        return $this;
    }

    /**
     * Parse data
     *
     * @return Widgets instance.
     */
    private function parse()
    {
        if (is_array($this->paths)) {
            foreach ($this->paths as $path) {
                $this->parsers[] = new Parser($path);
            }
        }
        return $this;
    }

    /**
     * JSON path from folder path
     *
     * @param  string $folder
     * @return string json path
     */
    private function jsonPath($folder)
    {
        $json_path = $folder . DS . basename($folder) . '.json';
        if (is_file($json_path)) {
            return $json_path;
        }
        return false;
    }
}
