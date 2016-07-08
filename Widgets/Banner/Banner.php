<?php
namespace MyProject\LolitaFramework\Widgets\Banner;

use \MyProject\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls;
use \MyProject\LolitaFramework\Core\View;
use \MyProject\LolitaFramework\Core\HelperArray;
use \MyProject\LolitaFramework\Core\HelperImage;
use \MyProject\LolitaFramework;

class Banner extends AbstractWithControls
{
    /**
     * Register widget with WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct()
    {
        parent::__construct(
            __('Lolita banner widget', 'lolita'),
            array(
                'description' => __('Lolita banner widget', 'lolita'),
                'classname' => 'lf_banner',
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
                "name"     => "title",
                "__TYPE__" => "Input",
                "label"    => "Title",
            ),
            array(
                "name"     => "url",
                "__TYPE__" => "Input",
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
        $url = HelperArray::get($instance, 'url', '/');
        if ('' === $url) {
            $url = '/';
        }

        $this->view(
            dirname(__FILE__) . DS . 'views' . DS . 'banner.php',
            array(
                'url'   => $url,
                'img'   => HelperArray::get($instance, 'img', 0),
                'title' => HelperArray::get($instance, 'title'),
                'args'  => $args,
            )
        );
    }
}
