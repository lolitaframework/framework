<?php
namespace MyProject\LolitaFramework\Widgets\LatestPosts;

use \MyProject\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls;
use \MyProject\LolitaFramework\Core\Arr;

class LatestPosts extends AbstractWithControls
{
    /**
     * Register widget with WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array data to generate controls.
     */
    public static function getControlsData()
    {
        return array(
            array(
                'name'     => 'title',
                '__TYPE__' => 'Text',
                'label'    => 'Title',
            ),
            array(
                'name'     => 'post_type',
                '__TYPE__' => 'Select',
                'label'    => 'Post type',
                'options'  => self::getPostTypeOptions(),
            ),
            array(
                'name'     => 'orderby',
                '__TYPE__' => 'Select',
                'label'    => 'Order by',
                'options'  => array(
                    'none'          => __('None', 'lolita'),
                    'ID'            => __('ID', 'lolita'),
                    'author'        => __('Author', 'lolita'),
                    'title'         => __('Title', 'lolita'),
                    'date'          => __('Date', 'lolita'),
                    'modified'      => __('Modified', 'lolita'),
                    'parent'        => __('Parent', 'lolita'),
                    'rand'          => __('Random', 'lolita'),
                    'comment_count' => __('Comment count', 'lolita'),
                    'menu_order'    => __('Menu order', 'lolita'),
                ),
            ),
            array(
                'name'     => 'order',
                '__TYPE__' => 'Select',
                'label'    => 'Order',
                'options'  => array(
                    'ASC'  => __('Ascending', 'lolita'),
                    'DESC' => __('Descending', 'lolita'),
                ),
            ),
            array(
                'name'     => 'count',
                '__TYPE__' => 'Text',
                'label'    => 'Count',
            ),
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
     * Get order by
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array $instance data.
     * @return string order by.
     */
    private function getOrderBy($instance)
    {
        $order_by = 'date';
        if (array_key_exists('orderby', $instance)) {
            $order_by = $instance['orderby'];
        }
        return $order_by;
    }

    /**
     * Get order
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array $instance data.
     * @return string order.
     */
    private function getOrder($instance)
    {
        $order = 'DESC';
        if (array_key_exists('order', $instance)) {
            $order = $instance['order'];
        }
        return $order;
    }

    /**
     * Get posts
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $post_type post type.
     * @param  integer $count posts per widget.
     * @param  string $order_by order by.
     * @return posts.
     */
    private function getPosts($post_type, $count, $order_by, $order)
    {
        return get_posts(
            array(
                'posts_per_page'   => $count,
                'offset'           => 0,
                'category'         => '',
                'category_name'    => '',
                'orderby'          => $order_by,
                'order'            => $order,
                'include'          => '',
                'exclude'          => '',
                'meta_key'         => '',
                'meta_value'       => '',
                'post_type'        => $post_type,
                'post_mime_type'   => '',
                'post_parent'      => '',
                'author'           => '',
                'author_name'      => '',
                'post_status'      => 'publish',
                'suppress_filters' => true,
            )
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
        $post_type = $this->getPostType($instance);
        $count     = $this->getCount($instance);
        $orderby   = $this->getOrderBy($instance);
        $order     = $this->getOrder($instance);
        $this->view(
            dirname(__FILE__) . DS . 'views' . DS . 'latest_posts.php',
            array(
                'items'     => $this->getPosts(
                    $post_type,
                    $count,
                    $orderby,
                    $order
                ),
                'title'     => Arr::get($instance, 'title', ''),
                'post_type' => $post_type,
                'count'     => $count,
                'orderby'   => $orderby,
                'order'     => $order,
                'args'      => $args,
            )
        );
    }
}
