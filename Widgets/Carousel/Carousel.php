<?php
namespace MyProject\LolitaFramework\Widgets\Carousel;

use \MyProject\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls;
use \MyProject\LolitaFramework\Core\View;
use \MyProject\LolitaFramework\Core\Arr;
use \MyProject\LolitaFramework\Core\Img;
use \MyProject\LolitaFramework;

class Carousel extends AbstractWithControls
{
    /**
     * Register widget with WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct()
    {
        parent::__construct(
            __('Lolita carousel widget', 'lolita'),
            array(
                'description' => __('Lolita carousel widget', 'lolita'),
                'classname'   => 'lf_carousel',
            )
        );
    }

    /**
     * This function run before widgets_init hook
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return void
     */
    public static function beforeInit()
    {
        parent::beforeInit();
        add_action('admin_enqueue_scripts', array(__CLASS__, 'adminAddScriptsAndStyles'));
    }

    /**
     * Add scripts and styles to wp admin
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public static function wpAddScriptsAndStyles()
    {
        $assets = LolitaFramework::getURLByDirectory(__DIR__) . DS . 'assets' . DS;
        // ==============================================================
        // Scripts
        // ==============================================================
        wp_enqueue_script('jquery');
        wp_enqueue_script(
            'jquery.sly',
            $assets . 'js' . DS . 'jquery.sly.min.js',
            array('jquery'),
            false,
            true
        );
        wp_enqueue_script(
            'lolita_widget_carousel',
            $assets . 'js' . DS . 'lolita_widget_carousel.js',
            array('jquery', 'jquery.sly'),
            false,
            true
        );
    }

    /**
     * Add scripts and styles to wp admin
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public static function adminAddScriptsAndStyles()
    {
        $assets = LolitaFramework::getURLByDirectory(__DIR__) . DS . 'assets' . DS;
        // ==============================================================
        // Scripts
        // ==============================================================
        wp_enqueue_script('jquery');
        wp_enqueue_script(
            'lolita-widget-carousel',
            $assets . 'js' . DS . 'admin_lolita_widget_carousel.js',
            array('jquery'),
            false,
            true
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
                'name'     => 'carousel_type',
                '__TYPE__' => 'Select',
                'label'    => __('Carousel type', 'lolita'),
                'options'  => array(
                    '1' => __('Style 1', 'lolita'),
                    '2' => __('Style 2', 'lolita'),
                ),
            ),
            array(
                'name'     => 'style_1',
                '__TYPE__' => 'Repeater',
                'label'    => __('Style 1', 'lolita'),
                'controls' => array(
                    array(
                        'name'     => 'title',
                        '__TYPE__' => 'Text',
                        'type'     => 'text',
                        'label'    => __('Title', 'lolita'),
                    ),
                    array(
                        'name'     => 'content',
                        '__TYPE__' => 'Textarea',
                        'rows'     => '7',
                        'label'    => __('Content', 'lolita'),
                    ),
                    array(
                        'name'     => 'img',
                        '__TYPE__' => 'Media',
                        'label'    => __('Image', 'lolita'),
                    ),
                ),
            ),
            array(
                'name'     => 'style_2',
                '__TYPE__' => 'Repeater',
                'label'    => __('Style 2', 'lolita'),
                'controls' => array(
                    array(
                        'name'     => 'title',
                        '__TYPE__' => 'Text',
                        'label'    => __('Title', 'lolita'),
                    ),
                    array(
                        'name'     => 'url',
                        '__TYPE__' => 'Text',
                        'label'    => __('Link', 'lolita'),
                    ),
                    array(
                        'name'     => 'img',
                        '__TYPE__' => 'Media',
                        'label'    => __('Image', 'lolita'),
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
        add_action('wp_footer', array(__CLASS__, 'wpAddScriptsAndStyles'));
        $this->view(
            sprintf(
                '%sstyle_%s.php',
                dirname(__FILE__) . DS . 'views' . DS,
                $this->getStyleType($instance)
            ),
            array(
                'args'     => $args,
                'items'    => $this->prepareInstance($instance),
                'style'    => $this->getStyleType($instance),
                'instance' => $instance,
            )
        );
    }

    /**
     * Prepare data per style
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array $instance data.
     * @return array prepared data.
     */
    private function prepareInstance($instance)
    {
        $key  = 'style_' . $this->getStyleType($instance);
        $data = Arr::get($instance, $key, array());
        foreach ($data as &$list) {
            if (array_key_exists('img', $list)) {
                $list['img_src'] = Img::getURL(
                    (int) $list['img'],
                    'full'
                );
            }
        }
        return $data;
    }

    /**
     * Get carousel type
     * @param  array $instance instance.
     * @return integer carousel type.
     */
    private function getStyleType($instance)
    {
        return max(1, (int) Arr::get($instance, 'carousel_type', 1));
    }
}
