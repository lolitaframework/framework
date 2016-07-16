<?php
namespace MyProject\LolitaFramework\Controls;

use \MyProject\LolitaFramework\Core\Arr;
use \MyProject\LolitaFramework\Core\Cls;
use \MyProject\LolitaFramework\Core\View;

/**
 * Controls collection class
 */
class Controls
{
    /**
     * Generated controls
     * @var null
     */
    public $collection = null;

    /**
     * Create control
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $class_name control class name.
     * @param  array $parameters parameters.
     * @return mixed
     */
    public static function create($class_name, $parameters)
    {
        if (class_exists($class_name)) {
            $reflection  = new \ReflectionClass($class_name);
            return $reflection->newInstanceArgs(self::prepareClassParameters($class_name, $parameters));
        }
        return null;
    }

    /**
     * Prepare class parameters
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $class_name class name.
     * @param  array  $parameters class parameters.
     * @return array  prepared parameters.
     */
    public static function prepareClassParameters($class_name, array $parameters = array())
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
     * Generate controls from data
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array  $data from we want to generate controls.
     * @return Controls $this.
     */
    public function generateControls(array $data)
    {
        self::loadScriptsAndStyles($data);
        foreach ($data as $arguments) {
            if (!is_string($arguments['name'])) {
                throw new \Exception("`name` must be in string type.");
            }
            $class_name = self::getClassNameFromType(Arr::get($arguments, '__TYPE__'));
            $control = self::create(
                $class_name,
                $arguments
            );
            if (null !== $control) {
                $this->collection[ $arguments['name'] ] = $control;
            }
        }
        return $this;
    }

    /**
     * Get class name from type
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $type control type
     * @return string class name.
     */
    public static function getClassNameFromType($type)
    {
        if ('' === $type) {
            throw new \Exception("`__TYPE__` option should be setted!");
        }
        return __NAMESPACE__ . NS . $type . NS . $type;
    }

    /**
     * Load all scripts and styles from each control
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array  $data controls data.
     * @return void.
     */
    public static function loadScriptsAndStyles(array $data)
    {
        foreach ($data as $arguments) {
            $class_name = self::getClassNameFromType(Arr::get($arguments, '__TYPE__'));
            if (Cls::isImplements($class_name, __NAMESPACE__ . NS . 'IHaveEnqueue')) {
                add_action('wp_enqueue_scripts', array($class_name, 'enqueue'));
            }

            if (Cls::isImplements($class_name, __NAMESPACE__ . NS . 'IHaveAdminEnqueue')) {
                add_action('admin_enqueue_scripts', array($class_name, 'adminEnqueue'));
            }
        }
    }

    /**
     * Render each control
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $collection_view path to collection view.
     * @param  string $control_view    path to control view.
     * @return string                  rendered content HTML.
     */
    public function render($collection_view, $control_view)
    {
        $rendered_controls = array();
        foreach ($this->collection as $control) {
            $rendered_controls[] = View::make($control_view, array('control' => $control));
        }
        return View::make($collection_view, array('controls' => implode('', $rendered_controls)));
    }
}
