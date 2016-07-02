<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine\Engine;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\View;

class Breadcrumbs extends AbstractWithControls
{
    /**
     * Register widget with WordPress.
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
