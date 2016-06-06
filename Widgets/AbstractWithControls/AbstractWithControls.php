<?php
namespace redbrook\LolitaFramework\Widgets\AbstractWithControls;

use \redbrook\LolitaFramework\Core\HelperArray as HelperArray;
use \redbrook\LolitaFramework\Core\HelperString as HelperString;
use \redbrook\LolitaFramework\Core\View as View;
use \redbrook\LolitaFramework\Controls\Controls as Controls;

abstract class AbstractWithControls extends \WP_Widget
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
    abstract public function getControlsData();

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
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        $controls = new Controls;
        $controls->generateControls($this->getControlsData());

        if ($controls instanceof Controls) {
            foreach ($controls->collection as $control) {
                // ==============================================================
                // Set new value
                // ==============================================================
                $control->setValue(
                    HelperArray::get($instance, $control->getName())
                );
                // ==============================================================
                // Fill new attributes
                // ==============================================================
                $control->parameters = array_merge(
                    $control->parameters,
                    array(
                        'class' => $control->getName() . '-class widefat',
                        'id' => $this->get_field_id($control->getName()),
                    )
                );
                // ==============================================================
                // Set new name
                // ==============================================================
                $control->setName(
                    $this->get_field_name($control->getName())
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
