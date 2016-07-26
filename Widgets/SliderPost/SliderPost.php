<?php
namespace MyProject\LolitaFramework\Widgets\SliderPost;

use \MyProject\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls;
use \MyProject\LolitaFramework\Core\View;
use \MyProject\LolitaFramework\Core\Arr;
use \MyProject\LolitaFramework\Core\Str;
use \MyProject\LolitaFramework\Core\Img;
use \MyProject\LolitaFramework;

class SliderPost extends AbstractWithControls
{
    /**
     * Register widget with WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct()
    {
        parent::__construct(
            __('Lolita slider post widget', 'lolita'),
            array(
                'description' => __('Lolita slider post widget', 'lolita'),
                'classname'   => 'lf_slider_post',
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
     * @param array $instance widget instance data.
     */
    public static function wpAddScriptsAndStyles($instance = array())
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
            'lf_widget_slider_post',
            $assets . 'js' . DS . 'lolita_widget_slider_post.js',
            array('jquery'),
            false,
            true
        );
        wp_localize_script(
            'lf_widget_slider_post',
            'lf_widget_slider_post_l10n',
            array(
                'speed' => (int) Arr::get((array) $instance, 'speed', 3000),
            )
        );
    }

    /**
     * Post type taxonomies select controls.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array select controls.
     */
    public static function postTypeTaxonomiesSelects()
    {
        $return = array();
        $post_types = (array) self::getPostTypeOptions();
        foreach ($post_types as $key => $value) {
            $taxonomies = get_object_taxonomies($key, 'objects');
            foreach ($taxonomies as $tax) {
                $options = array('' => __('Select item', 'lolita'));
                $terms   = get_terms(
                    array(
                        'taxonomy'   => $tax->name,
                        'hide_empty' => false,
                    )
                );
                if (!empty($terms) && !is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        $options[$term->slug] = $term->name;
                    }
                    $return[] = array(
                        'name'     => $key . '__' . $tax->name,
                        '__TYPE__' => 'Select',
                        'label'    => $value . ' ' . $tax->labels->singular_name,
                        'options'  => $options,
                        'class'    => 'lf_slider_post_taxonomy lf_slider_post_type__' . $key,
                    );
                }
            }
        }
        return $return;
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
            'lf_slide_post',
            $assets . 'js' . DS . 'admin_lolita_widget_slider_post.js',
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
        return array_merge(
            array(
                array(
                    'name'     => 'post_type',
                    '__TYPE__' => 'Select',
                    'label'    => 'Post type',
                    'options'  => self::getPostTypeOptions(),
                    'class'    => 'lf_slide_post_type',
                ),
                array(
                    "name"     => "count",
                    "__TYPE__" => "Text",
                    "label"    => "Count",
                ),
                array(
                    "name"     => "speed",
                    "__TYPE__" => "Text",
                    "label"    => "Speed",
                ),
            ),
            self::postTypeTaxonomiesSelects()
        );
    }

    /**
     * Get post types for select control
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array options.
     */
    public static function getPostTypeOptions()
    {
        $result     = array();
        $post_types = get_post_types(array(), 'objects');

        foreach ($post_types as $key => $value) {
            $result[ $key ] = $value->label;
        }
        return $result;
    }



    /**
     * Get post type
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array $instance data.
     * @return string post type.
     */
    private function getPostType($instance)
    {
        if (array_key_exists('post_type', $instance)) {
            return $instance['post_type'];
        }
        return 'post';
    }

    /**
     * Get post per widget
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array $instance data.
     * @return int post per widget.
     */
    private function getCount($instance)
    {
        $count = 1;
        if (array_key_exists('count', $instance)) {
            $count = $instance['count'];
        }
        return max(1, (int) $count);
    }

    /**
     * Get posts
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $post_type post type.
     * @param  integer $count posts per widget.
     * @param  array $filter tax query.
     * @return posts.
     */
    private function getPosts($post_type, $count, $filter)
    {
        $filter = (array) $filter;
        $args = array(
            'posts_per_page'   => $count,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => $post_type,
            'post_status'      => 'publish',
            'suppress_filters' => true,
        );

        if (count($filter)) {
            $args['tax_query'] = $filter;
        }

        $posts = get_posts($args);
        foreach ($posts as &$p) {
            if (has_post_thumbnail($p->ID)) {
                $p->img    = Img::url(get_post_thumbnail_id($p->ID), 'full');
            }
            $p->format = get_post_format($p->ID);
        }

        return $posts;
    }

    /**
     * Get taxonomy filter
     * @param  string $post_type post type.
     * @param  array  $instance widget data instance.
     * @return array filter.
     */
    private function getTaxonomyFilter($post_type, array $instance = array())
    {
        $return = array();
        $instance = Arr::removeEmpty($instance);
        foreach ($instance as $key => $value) {
            if (Str::startsWith($key, $post_type . '__')) {
                $tax = str_replace($post_type . '__', '', $key);
                $return[] = array(
                    'taxonomy' => $tax,
                    'field'    => 'slug',
                    'terms'    => $value,
                );
            }
        }
        return $return;
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
        $post_type = $this->getPostType($instance);
        $count     = $this->getCount($instance);
        $filter    = $this->getTaxonomyFilter($post_type, $instance);

        add_action(
            'wp_footer',
            function () use ($instance) {
                SliderPost::wpAddScriptsAndStyles($instance);
            }
        );
        $this->view(
            __DIR__ . DS . 'views' . DS . 'slider_post.php',
            array(
                'args'     => $args,
                'instance' => $instance,
                'items'    => $this->getPosts(
                    $post_type,
                    $count,
                    $filter
                ),
            )
        );
    }
}
