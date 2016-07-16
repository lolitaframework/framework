<?php
namespace MyProject\LolitaFramework\Widgets\SocialNetworks;

use \MyProject\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls;
use \MyProject\LolitaFramework\Core\View;
use \MyProject\LolitaFramework\Core\Arr;
use \MyProject\LolitaFramework;

class SocialNetworks extends AbstractWithControls
{
    /**
     * Register widget with WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct()
    {
        parent::__construct(
            __('Lolita Social networks', 'lolita'),
            array(
                'description' => __('Social networks widget', 'lolita'),
                'classname'   => 'lf_social_networks',
            )
        );
    }

    /**
     * Add scripts and styles
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public static function addScriptsAndStyles()
    {
        wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
    }

    /**
     * Get controls data
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array data to generate controls.
     */
    public static function getControlsData()
    {
        return array(
            array(
                "name"     => "title",
                "__TYPE__" => "Text",
                "label"    => "Title",
            ),
            array(
                "name"     => "collection",
                "__TYPE__" => "Repeater",
                "label"    => "Social networks",
                "controls" => array(
                    array(
                        "name"     => "url",
                        "__TYPE__" => "Text",
                        "label"    => "Link",
                    ),
                    array(
                        "name"     => "content",
                        "__TYPE__" => "Text",
                        "label"    => "Content",
                    ),
                    array(
                        "name"     => "icon_css",
                        "__TYPE__" => "Icons",
                        "label"    => "Icon css class",
                    ),
                ),
            ),
        );
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
        add_action('wp_footer', array(__CLASS__, 'addScriptsAndStyles'));
        $title = Arr::get($instance, 'title', '');
        $instance['title'] = '';
        $this->view(
            dirname(__FILE__) . DS . 'views' . DS . 'social_networks.php',
            array(
                'title'    => $title,
                'instance' => $instance,
                'args'     => $args,
                'icons'    => Arr::get($instance, 'collection', array()),
                'id_base'  => $this->id_base,
            )
        );
    }
}
