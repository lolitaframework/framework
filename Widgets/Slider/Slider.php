<?php
namespace MyProject\LolitaFramework\Widgets\Slider;

use \MyProject\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls;
use \MyProject\LolitaFramework\Core\View;
use \MyProject\LolitaFramework\Core\Arr;
use \MyProject\LolitaFramework\Core\Img;
use \MyProject\LolitaFramework;

class Slider extends AbstractWithControls
{
    /**
     * Register widget with WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct()
    {
        parent::__construct(
            __('Lolita slider widget', 'lolita'),
            array('description' => __('Lolita slider widget', 'lolita'))
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
            'bx-slider',
            $assets . 'js' . DS . 'jquery.bx-slider.min.js',
            array('jquery'),
            false,
            true
        );
        wp_enqueue_script(
            'lolita_widget_slider',
            $assets . 'js' . DS . 'lolita_widget_slider.js',
            array('jquery'),
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
            'lolita-widget-slider',
            $assets . 'js' . DS . 'admin_lolita_widget_slider.js',
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
                'name'     => 'slider_type',
                '__TYPE__' => 'Select',
                'label'    => 'Slider type',
                'options'  => array(
                    '1' => __('Style 1', 'lolita'),
                    '2' => __('Style 2', 'lolita'),
                    '3' => __('Style 3', 'lolita'),
                ),
            ),
            array(
                'name'     => 'style_1',
                '__TYPE__' => 'Repeater',
                'label'    => 'Style 1',
                'controls' => array(
                    array(
                        'name'     => 'url',
                        '__TYPE__' => 'Text',
                        'type'     => 'text',
                        'label'    => 'Link',
                    ),
                    array(
                        'name'     => 'img',
                        '__TYPE__' => 'Media',
                        'label'    => 'Image',
                    ),
                    array(
                        'name'     => 'logo',
                        '__TYPE__' => 'Media',
                        'label'    => 'Logo',
                    ),
                ),
            ),
            array(
                'name'     => 'style_2',
                '__TYPE__' => 'Repeater',
                'label'    => 'Style 2',
                'controls' => array(
                    array(
                        'name'     => 'title',
                        '__TYPE__' => 'Text',
                        'type'     => 'text',
                        'label'    => 'Title',
                    ),
                    array(
                        'name'     => 'subtitle',
                        '__TYPE__' => 'Text',
                        'type'     => 'text',
                        'label'    => 'Subtitle',
                    ),
                    array(
                        'name'     => 'content',
                        '__TYPE__' => 'Textarea',
                        'rows'     => '7',
                        'label'    => 'Content',
                    ),
                    array(
                        'name'     => 'img',
                        '__TYPE__' => 'Media',
                        'label'    => 'Image',
                    ),
                    array(
                        'name'     => 'background',
                        '__TYPE__' => 'Media',
                        'label'    => 'Background',
                    ),
                ),
            ),
            array(
                'name'     => 'style_3',
                '__TYPE__' => 'Repeater',
                'label'    => 'Style 3',
                'controls' => array(
                    array(
                        'name'     => 'title',
                        '__TYPE__' => 'Text',
                        'type'     => 'text',
                        'label'    => 'Title',
                    ),
                    array(
                        'name'     => 'url',
                        '__TYPE__' => 'Text',
                        'type'     => 'text',
                        'label'    => 'Link',
                    ),
                    array(
                        'name'     => 'content',
                        '__TYPE__' => 'Textarea',
                        'rows'     => '7',
                        'label'    => 'Content',
                    ),
                    array(
                        'name'     => 'img',
                        '__TYPE__' => 'Media',
                        'label'    => 'Image',
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
        add_action('wp_enqueue_scripts', array(__CLASS__, 'wpAddScriptsAndStyles'));
        echo View::make(
            sprintf(
                '%sstyle_%s.php',
                dirname(__FILE__) . DS . 'views' . DS,
                $this->getSliderType($instance)
            ),
            array(
                'args'     => $args,
                'instance' => $this->prepareInstance($instance),
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
        $key  = 'style_' . $this->getSliderType($instance);
        $data = Arr::get($instance, $key, array());
        foreach ($data as &$list) {
            if (array_key_exists('img', $list)) {
                $list['img_src'] = Img::getURL(
                    (int) $list['img'],
                    'full'
                );
            }

            if (array_key_exists('background', $list)) {
                $list['background_src'] = Img::getURL(
                    (int) $list['background'],
                    'full'
                );
            }

            if (array_key_exists('logo', $list)) {
                $list['logo_src'] = Img::getURL(
                    (int) $list['logo'],
                    'full'
                );
            }
        }
        return $data;
    }

    /**
     * Get slider type
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array $instance instance.
     * @return integer slider type.
     */
    private function getSliderType($instance)
    {
        return Arr::get($instance, 'slider_type', 1);
    }
}
