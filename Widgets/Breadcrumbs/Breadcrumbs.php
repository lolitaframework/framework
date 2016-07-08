<?php
namespace MyProject\LolitaFramework\Widgets\Breadcrumbs;

use \MyProject\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls;
use \MyProject\LolitaFramework\Widgets\Breadcrumbs\Engine\Engine;
use \MyProject\LolitaFramework\Core\View;

class Breadcrumbs extends AbstractWithControls
{
    /**
     * Register widget with WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct()
    {
        parent::__construct(
            __('Lolita breadcrumbs', 'lolita'),
            array(
                'description' => __('Lolita breadcrumbs', 'lolita'),
                'classname'   => 'lf_breadcrumbs',
            )
        );
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

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        $breadcrumbs = new Engine();
        echo View::make(
            dirname(__FILE__) . DS . 'views' . DS . 'breadcrumbs.php',
            array(
                'crumbs'   => $breadcrumbs->compile(),
                'args'     => $args,
                'instance' => $instance,
            )
        );
    }
}
