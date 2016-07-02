<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Logo;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls as AbstractWithControls;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\View as View;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperArray as HelperArray;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperImage;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework as LolitaFramework;

class Logo extends AbstractWithControls
{
    /**
     * Register widget with WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct()
    {
        parent::__construct(
            __('Lolita logo widget', 'lolita'),
            array(
                'description' => __('Lolita logo widget', 'lolita'),
                'classname' => 'lf_logo',
            )
        );
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
                "name"     => "url",
                "__TYPE__" => "Input",
                "type"     => "text",
                "label"    => "URL",
            ),
            array(
                "name"     => "img",
                "__TYPE__" => "Media",
                "label"    => "Image",
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
        $src = HelperArray::get($instance, 'img', 0);
        if (0 === $src) {
            $src = 'http://placehold.it/50x50';
        } else {
            $src = HelperImage::getURL($src, 'full');
        }

        $url = HelperArray::get($instance, 'url', '/');
        if ('' === $url) {
            $url = '/';
        }
        echo View::make(
            dirname(__FILE__) . DS . 'views' . DS . 'logo.php',
            array(
                'url'  => $url,
                'src'  => $src,
                'alt'  => esc_attr(get_bloginfo('name')),
                'args' => $args,
            )
        );
    }
}
