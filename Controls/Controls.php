<?php
namespace lolita\LolitaFramework\Controls;

use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\Ref;
use \lolita\LolitaFramework\Core\Data;
use \lolita\LolitaFramework\Core\Cls;
use \lolita\LolitaFramework\Core\View;

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
            $control = Ref::create(
                self::getClassNameFromType(Arr::get($arguments, '__TYPE__')),
                $arguments
            );
            if (Arr::exists($arguments, 'old_name')) {
                $control->old_name = $arguments['old_name'];
            }

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

            if ('Repeater' === $arguments['__TYPE__'] && is_array($arguments['controls'])) {
                self::loadScriptsAndStyles($arguments['controls']);
            }
        }
    }

    /**
     * Admin enqueue for all controls from data
     *
     * @param  array  $data
     * @return mixed
     */
    public static function adminEnqueue(array $data)
    {
        if (!is_array($data)) {
            return false;
        }
        foreach ($data as $arguments) {
            $class_name = self::getClassNameFromType(Arr::get($arguments, '__TYPE__'));

            if (Cls::isImplements($class_name, __NAMESPACE__ . NS . 'IHaveAdminEnqueue')) {
                $class_name::adminEnqueue();
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
