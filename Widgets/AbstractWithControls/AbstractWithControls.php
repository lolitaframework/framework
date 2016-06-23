<?php
namespace franken\LolitaFramework\Widgets\AbstractWithControls;

use \franken\LolitaFramework\Core\HelperArray as HelperArray;
use \franken\LolitaFramework\Core\HelperString as HelperString;
use \franken\LolitaFramework\Core\View as View;
use \franken\LolitaFramework\Controls\Controls as Controls;
use \franken\LolitaFramework\Widgets\IHaveBeforeInit;

abstract class AbstractWithControls extends \WP_Widget implements IHaveBeforeInit
{
    /**
     * Register widget with WordPress.
     */
    public function __construct($name, $widget_options = array(), $id_base = '', $control_options = array())
    {
        if ('' === $id_base) {
            $id_base = HelperString::sentenceToSnake($name);
        }
        parent::__construct(
            $id_base,
            $name,
            $widget_options,
            $control_options
        );
    }

    /**
     * Get controls data
     * @return array data to generate controls.
     */
    abstract public static function getControlsData();

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        echo View::make(dirname(__FILE__) . DS . 'views' . DS . 'default.php');
    }

    /**
     * This function run before widgets_init hook
     * @return void
     */
    public static function beforeInit()
    {
        Controls::loadScriptsAndStyles(static::getControlsData());
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        $controls = new Controls;
        $controls_data = static::getControlsData();
        foreach ($controls_data as &$control) {
            $control['small_name'] = $control['name'];
            $control['name'] = $this->get_field_name($control['name']);
        }

        $controls->generateControls($controls_data);

        if ($controls instanceof Controls) {
            foreach ($controls->collection as $control) {
                // ==============================================================
                // Set new value
                // ==============================================================
                $control->setValue(
                    HelperArray::get($instance, $control->parameters['small_name'])
                );
                // ==============================================================
                // Fill new attributes
                // ==============================================================
                $control->parameters = array_merge(
                    $control->parameters,
                    array(
                        'class' => $control->parameters['small_name'] . '-class widefat',
                        'id' => $this->get_field_id($control->getID()),
                    )
                );
            }
            echo $controls->render(
                dirname(__FILE__) . DS . 'views' . DS . 'collection.php',
                dirname(__FILE__) . DS . 'views' . DS . 'row.php'
            );
        } else {
            throw new \Exception('Wrong $controls object');
        }
    }
}
