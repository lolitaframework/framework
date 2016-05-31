<?php
namespace ECG\LolitaFramework\Controls;

use \ECG\LolitaFramework\Core\HelperArray as HelperArray;
use \ECG\LolitaFramework\Core\View as View;

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
     * @param  string $name  control name.
     * @param  string $value current value.
     * @return mixed
     */
    public static function create($class, $name, $value = '')
    {
        $class_name = __NAMESPACE__ . NS . $class . NS . $class;
        if (class_exists($class_name)) {
            return new $class_name($name, $value);
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
        foreach ($data as $name => $arguments) {
            if (!array_key_exists('type', $arguments) || '' === $arguments['type']) {
                throw new \Exception("`type` option should be setted!");
            }
            if (!is_string($name)) {
                throw new \Exception("`name` must be in string type.");
            }
            $control = self::create(
                $arguments['type'],
                $name,
                HelperArray::get($arguments, 'value')
            );
            $control->generate_data = $arguments;
            if (null !== $control) {
               $this->collection[ $name ] = $control;
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
            $view_data = array_merge($control->generate_data, array('control' => $control));
            $rendered_controls[] = View::make($control_view, $view_data);
        }
        return View::make($collection_view, array('controls' => implode('', $rendered_controls)));
    }
}
