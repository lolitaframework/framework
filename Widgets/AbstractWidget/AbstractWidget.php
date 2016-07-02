<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\AbstractWidget;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperString;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\View;
use \WP_Widget;

abstract class AbstractWidget extends WP_Widget
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
     * Get view
     * @param  string $file view file.
     * @param  array $arguments arguments.
     * @return string view HTML code.
     */
    public function getView($file, $arguments = array())
    {
        $arguments = apply_filters($this->id_base.'_arguments', $arguments);
        $file      = apply_filters($this->id_base.'_view', $file, $arguments);
        return View::make(
            $file,
            $arguments
        );
    }

    /**
     * Render view
     * @param  string $file view file.
     * @param  array $arguments arguments.
     * @return void.
     */
    public function view($file, $arguments)
    {
        echo $this->getView($file, $arguments);
    }

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
        $this->view(dirname(__FILE__) . DS . 'views' . DS . 'default.php');
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
    }
}
