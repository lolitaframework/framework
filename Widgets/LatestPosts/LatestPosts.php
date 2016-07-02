<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\LatestPosts;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\View;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperArray;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperImage;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework;

class LatestPosts extends AbstractWithControls
{
    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        parent::__construct(
            __('Lolita latest posts widget', 'lolita'),
            array(
                'description' => __('Lolita latest posts widget', 'lolita'),
                'classname'   => 'lf_latest_posts',
            )
        );
    }

    /**
     * Get controls data
     * @return array data to generate controls.
     */
    public static function getControlsData()
    {
        return array(
            array(
                'name'     => 'style',
                '__TYPE__' => 'Select',
                'label'    => 'Render style',
                'options'  => array(
                    'style_1' => 'Style 1',
                    'style_2' => 'Style 2',
                ),
            ),
            array(
                'name'     => 'post_type',
                '__TYPE__' => 'Select',
                'label'    => 'Post type',
                'options'  => self::getPostTypeOptions(),
            ),
            array(
                "name"     => "count",
                "__TYPE__" => "Input",
                "label"    => "Count",
            ),
        );
    }

    /**
     * Get post types for select control
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
     * @param  string $post_type post type.
     * @param  integer $count posts per widget.
     * @return posts.
     */
    private function getPosts($post_type, $count)
    {
        return get_posts(
            array(
                'posts_per_page'   => $count,
                'offset'           => 0,
                'category'         => '',
                'category_name'    => '',
                'orderby'          => 'date',
                'order'            => 'DESC',
                'include'          => '',
                'exclude'          => '',
                'meta_key'         => '',
                'meta_value'       => '',
                'post_type'        => $post_type,
                'post_mime_type'   => '',
                'post_parent'      => '',
                'author'       => '',
                'author_name'      => '',
                'post_status'      => 'publish',
                'suppress_filters' => true 
            )
        );
    }

    /**
     * Get view style
     * @param  array $instance data.
     * @return string view style.
     */
    private function getStyle($instance)
    {
        if (array_key_exists('style', $instance)) {
            return $instance['style'];
        }
        return 'style_1';
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
        $this->view(
            dirname(__FILE__) . DS . 'views' . DS . $this->getStyle($instance) . '.php',
            array(
                'items' => $this->getPosts(
                    $this->getPostType($instance),
                    $this->getCount($instance)
                ),
                'style' => $this->getStyle($instance),
            )
        );
    }
}
