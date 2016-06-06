<?php
namespace redbrook\LolitaFramework\Controls;

use \redbrook\LolitaFramework\Core\HelperArray as HelperArray;
use \redbrook\LolitaFramework\Core\View as View;

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
     * @param  string $class control class name.
     * @param  array $parameters parameters.
     * @return mixed
     */
    public static function create($class, $parameters)
    {
        $class_name = __NAMESPACE__ . NS . $class . NS . $class;
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
        foreach ($data as $arguments) {
            if (!array_key_exists('__TYPE__', $arguments) || '' === $arguments['__TYPE__']) {
                throw new \Exception("`__TYPE__` option should be setted!");
            }
            if (!is_string($arguments['name'])) {
                throw new \Exception("`name` must be in string type.");
            }
            $control = self::create(
                $arguments['__TYPE__'],
                $arguments
            );
            if (null !== $control) {
                $this->collection[ $arguments['name'] ] = $control;
            }
        }
        return $this;
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
