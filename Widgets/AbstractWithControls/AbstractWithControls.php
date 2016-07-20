<?php
namespace MyProject\LolitaFramework\Widgets\AbstractWithControls;

use \MyProject\LolitaFramework\Core\Arr;
use \MyProject\LolitaFramework\Core\View;
use \MyProject\LolitaFramework\Controls\Controls;
use \MyProject\LolitaFramework\Widgets\IHaveBeforeInit;
use \MyProject\LolitaFramework\Widgets\AbstractWidget\AbstractWidget;

abstract class AbstractWithControls extends AbstractWidget implements IHaveBeforeInit
{
    /**
     * Get controls data
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array data to generate controls.
     */
    public static function getControlsData()
    {
        return array();
    }

    /**
     * This function run before widgets_init hook
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     * @author Guriev Eugen <gurievcreative@gmail.com>
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        $controls = new Controls;
        $controls_data = static::getControlsData();
        foreach ($controls_data as &$control) {
            $control['old_name'] = $control['name'];
            $control['name']     = $this->get_field_name($control['name']);
        }

        $controls->generateControls($controls_data);

        if ($controls instanceof Controls) {
            foreach ($controls->collection as $control) {
                // ==============================================================
                // Set new value
                // ==============================================================
                $control->setValue(
                    Arr::get($instance, $control->old_name, '')
                );
                // ==============================================================
                // Fill new attributes
                // ==============================================================
                $attributes = $control->getAttributes();
                $control->setAttributes(
                    array_merge(
                        $attributes,
                        array(
                            'class' => $control->old_name . '-class widefat ' . Arr::get($attributes, 'class', ''),
                            'id'    => $this->get_field_id($control->getID()),
                        )
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
