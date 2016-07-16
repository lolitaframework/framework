<?php
namespace MyProject\LolitaFramework\Widgets\AbstractWidget;

use \MyProject\LolitaFramework\Core\Str;
use \MyProject\LolitaFramework\Core\View;
use \WP_Widget;

abstract class AbstractWidget extends WP_Widget
{
    /**
     * Register widget with WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct($name, $widget_options = array(), $id_base = '', $control_options = array())
    {
        if ('' === $id_base) {
            $id_base = Str::sentenceToSnake($name);
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     * @author Guriev Eugen <gurievcreative@gmail.com>
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
    }
}
