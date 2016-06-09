<?php
namespace duidluck\LolitaFramework\Controls;

use \duidluck\LolitaFramework\Core\HelperArray as HelperArray;
use \duidluck\LolitaFramework\Core\View as View;

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
     * @param  string $class_name control class name.
     * @param  array $parameters parameters.
     * @return mixed
     */
    public static function create($class_name, $parameters)
    {
        if (class_exists($class_name)) {
            return new $class_name($parameters);
        }
        return null;
    }

    /**
     * Generate controls from data
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
            $class_name = self::getClassNameFromType(HelperArray::get($arguments, '__TYPE__'));
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
     * @param  string $type control type
     * @return string 
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
     * @param  array  $data controls data.
     * @return void.
     */
    public static function loadScriptsAndStyles(array $data)
    {
        foreach ($data as $arguments) {
            $class_name = self::getClassNameFromType(HelperArray::get($arguments, '__TYPE__'));
            add_action('wp_enqueue_scripts', array($class_name, 'enqueue'));
            add_action('admin_enqueue_scripts', array($class_name, 'adminEnqueue'));
        }
    }

    /**
     * Render each control
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
