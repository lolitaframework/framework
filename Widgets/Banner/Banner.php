<?php
namespace MyProject\LolitaFramework\Widgets\Banner;

use \MyProject\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls;
use \MyProject\LolitaFramework\Core\View;
use \MyProject\LolitaFramework\Core\Arr;
use \MyProject\LolitaFramework\Core\Img;
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
                "__TYPE__" => "Text",
                "label"    => "Title",
            ),
            array(
                "name"     => "url",
                "__TYPE__" => "Text",
                "label"    => "URL",
            ),
            array(
                "name"     => "img",
                "__TYPE__" => "Media",
                "label"    => "Image",
            ),
            array(
                "name"     => "content",
                "__TYPE__" => "Textarea",
                "label"    => "Content",
                "rows"     => "10",
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
        $url = Arr::get($instance, 'url', '/');
        if ('' === $url) {
            $url = '/';
        }

        $this->view(
            dirname(__FILE__) . DS . 'views' . DS . 'banner.php',
            array(
                'url'     => $url,
                'img'     => Arr::get($instance, 'img', 0),
                'title'   => Arr::get($instance, 'title', ''),
                'content' => Arr::get($instance, 'content', ''),
                'args'    => $args,
            )
        );
    }
}
