<?php
namespace lolita\LolitaFramework\Core\Decorators;

use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Loc;
use \WP_Comment;

class Comment
{

    /**
     * Comment ID.
     *
     * @since 4.4.0
     * @access public
     * @var int
     */
    public $comment_ID;

    /**
     * ID of the post the comment is associated with.
     *
     * @since 4.4.0
     * @access public
     * @var int
     */
    public $comment_post_ID = 0;

    /**
     * Comment author name.
     *
     * @since 4.4.0
     * @access public
     * @var string
     */
    public $comment_author = '';

    /**
     * Comment author email address.
     *
     * @since 4.4.0
     * @access public
     * @var string
     */
    public $comment_author_email = '';

    /**
     * Comment author URL.
     *
     * @since 4.4.0
     * @access public
     * @var string
     */
    public $comment_author_url = '';

    /**
     * Comment author IP address (IPv4 format).
     *
     * @since 4.4.0
     * @access public
     * @var string
     */
    public $comment_author_IP = '';

    /**
     * Comment date in YYYY-MM-DD HH:MM:SS format.
     *
     * @since 4.4.0
     * @access public
     * @var string
     */
    public $comment_date = '0000-00-00 00:00:00';

    /**
     * Comment GMT date in YYYY-MM-DD HH::MM:SS format.
     *
     * @since 4.4.0
     * @access public
     * @var string
     */
    public $comment_date_gmt = '0000-00-00 00:00:00';

    /**
     * Comment content.
     *
     * @since 4.4.0
     * @access public
     * @var string
     */
    public $comment_content;

    /**
     * Comment karma count.
     *
     * @since 4.4.0
     * @access public
     * @var int
     */
    public $comment_karma = 0;

    /**
     * Comment approval status.
     *
     * @since 4.4.0
     * @access public
     * @var string
     */
    public $comment_approved = '1';

    /**
     * Comment author HTTP user agent.
     *
     * @since 4.4.0
     * @access public
     * @var string
     */
    public $comment_agent = '';

    /**
     * Comment type.
     *
     * @since 4.4.0
     * @access public
     * @var string
     */
    public $comment_type = '';

    /**
     * Parent comment ID.
     *
     * @since 4.4.0
     * @access public
     * @var int
     */
    public $comment_parent = 0;

    /**
     * Comment author ID.
     *
     * @since 4.4.0
     * @access public
     * @var int
     */
    public $user_id = 0;

    /**
     * Comment children.
     *
     * @since 4.4.0
     * @access protected
     * @var array
     */
    protected $children;

    /**
     * Whether children have been populated for this comment object.
     *
     * @since 4.4.0
     * @access protected
     * @var bool
     */
    protected $populated_children = false;

    /**
     * Post fields.
     *
     * @since 4.4.0
     * @access protected
     * @var array
     */
    protected $post_fields = array( 'post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_title', 'post_excerpt', 'post_status', 'comment_status', 'ping_status', 'post_name', 'to_ping', 'pinged', 'post_modified', 'post_modified_gmt', 'post_content_filtered', 'post_parent', 'guid', 'menu_order', 'post_type', 'post_mime_type', 'comment_count' );

    /**
     * Retrieves a WP_Comment instance.
     *
     * @since 4.4.0
     * @access public
     * @static
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param int $id Comment ID.
     * @return WP_Comment|false Comment object, otherwise false.
     */
    public static function getInstance($id)
    {
        global $wpdb;

        $comment_id = (int) $id;
        if (!$comment_id) {
            return false;
        }

        $_comment = wp_cache_get($comment_id, 'comment');

        if (!$_comment) {
            $_comment = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM $wpdb->comments WHERE comment_ID = %d LIMIT 1",
                    $comment_id
                )
            );

            if (!$_comment) {
                return false;
            }

            wp_cache_add($_comment->comment_ID, $_comment, 'comment');
        }

        return new Comment($_comment);
    }

    /**
     * Constructor.
     *
     * Populates properties with object vars.
     *
     * @since 4.4.0
     * @access public
     *
     * @param WP_Comment $comment Comment object.
     */
    public function __construct($comment)
    {
        foreach (get_object_vars($comment) as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Convert object to array.
     *
     * @since 4.4.0
     * @access public
     *
     * @return array Object as array.
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * Get the children of a comment.
     *
     * @since 4.4.0
     * @access public
     *
     * @param array $args {
     *     Array of arguments used to pass to get_comments() and determine format.
     *
     *     @type string $format        Return value format. 'tree' for a hierarchical tree, 'flat' for a flattened array.
     *                                 Default 'tree'.
     *     @type string $status        Comment status to limit results by. Accepts 'hold' (`comment_status=0`),
     *                                 'approve' (`comment_status=1`), 'all', or a custom comment status.
     *                                 Default 'all'.
     *     @type string $hierarchical  Whether to include comment descendants in the results.
     *                                 'threaded' returns a tree, with each comment's children
     *                                 stored in a `children` property on the `WP_Comment` object.
     *                                 'flat' returns a flat array of found comments plus their children.
     *                                 Pass `false` to leave out descendants.
     *                                 The parameter is ignored (forced to `false`) when `$fields` is 'ids' or 'counts'.
     *                                 Accepts 'threaded', 'flat', or false. Default: 'threaded'.
     *     @type string|array $orderby Comment status or array of statuses. To use 'meta_value'
     *                                 or 'meta_value_num', `$meta_key` must also be defined.
     *                                 To sort by a specific `$meta_query` clause, use that
     *                                 clause's array key. Accepts 'comment_agent',
     *                                 'comment_approved', 'comment_author',
     *                                 'comment_author_email', 'comment_author_IP',
     *                                 'comment_author_url', 'comment_content', 'comment_date',
     *                                 'comment_date_gmt', 'comment_ID', 'comment_karma',
     *                                 'comment_parent', 'comment_post_ID', 'comment_type',
     *                                 'user_id', 'comment__in', 'meta_value', 'meta_value_num',
     *                                 the value of $meta_key, and the array keys of
     *                                 `$meta_query`. Also accepts false, an empty array, or
     *                                 'none' to disable `ORDER BY` clause.
     * }
     * @return array Array of `WP_Comment` objects.
     */
    public function getChildren($args = array())
    {
        $defaults = array(
            'format'       => 'tree',
            'status'       => 'all',
            'hierarchical' => 'threaded',
            'orderby'      => '',
        );

        $_args = wp_parse_args($args, $defaults);
        $_args['parent'] = $this->comment_ID;

        if (is_null($this->children)) {
            if ($this->populated_children) {
                $this->children = array();
            } else {
                $this->children = self::sanitize(get_comments($_args));
            }
        }

        if ('flat' === $_args['format']) {
            $children = array();
            foreach ($this->children as $child) {
                $child_args = $_args;
                $child_args['format'] = 'flat';
                // get_children() resets this value automatically.
                unset($child_args['parent']);

                $children = array_merge($children, array($child), $child->get_children($child_args));
            }
        } else {
            $children = $this->children;
        }

        return $children;
    }

    /**
     * Add a child to the comment.
     *
     * Used by `WP_Comment_Query` when bulk-filling descendants.
     *
     * @since 4.4.0
     * @access public
     *
     * @param WP_Comment $child Child comment.
     */
    public function addChild(Comment $child)
    {
        $this->children[ $child->comment_ID ] = $child;
    }

    /**
     * Get a child comment by ID.
     *
     * @since 4.4.0
     * @access public
     *
     * @param int $child_id ID of the child.
     * @return WP_Comment|bool Returns the comment object if found, otherwise false.
     */
    public function getChild($child_id)
    {
        if (isset($this->children[ $child_id ])) {
            return $this->children[ $child_id ];
        }

        return false;
    }

    /**
     * Set the 'populated_children' flag.
     *
     * This flag is important for ensuring that calling `get_children()` on a childless comment will not trigger
     * unneeded database queries.
     *
     * @since 4.4.0
     *
     * @param bool $set Whether the comment's children have already been populated.
     */
    public function populatedChildren($set)
    {
        $this->populated_children = (bool) $set;
    }

    /**
     * Check whether a non-public property is set.
     *
     * If `$name` matches a post field, the comment post will be loaded and the post's value checked.
     *
     * @since 4.4.0
     * @access public
     *
     * @param string $name Property name.
     * @return bool
     */
    public function __isset($name)
    {
        if (in_array($name, $this->post_fields) && 0 !== (int) $this->comment_post_ID) {
            $post = get_post($this->comment_post_ID);
            return property_exists($post, $name);
        }
    }

    /**
     * Magic getter.
     *
     * If `$name` matches a post field, the comment post will be loaded and the post's value returned.
     *
     * @since 4.4.0
     * @access public
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (in_array($name, $this->post_fields)) {
            $post = get_post($this->comment_post_ID);
            return $post->$name;
        }
    }

    /**
     * Get the date to use in your template!
     *
     * @param string $date_format
     * @return string
     */
    public function date($date_format = '')
    {
        $df = $date_format ? $date_format : get_option('date_format');
        $the_date = (string) mysql2date($df, $this->comment_date);
        return apply_filters('get_the_date', $the_date, $df);
    }

    /**
     * Get the time to us in your template!
     *
     * @param  string $time_format
     * @return string
     */
    public function time($time_format = '')
    {
        $tf = $time_format ? $time_format : get_option('time_format');
        $the_time = (string) mysql2date($tf, $this->comment_date);
        return apply_filters('get_the_time', $the_time, $tf);
    }

    /**
     * Sanitize comment / comments
     *
     * @param  mixed $data
     * @return mixed
     */
    public static function sanitize($data)
    {
        if ($data instanceof Comment) {
            return $data;
        }
        if ($data instanceof WP_Comment) {
            return new Comment($data);
        }

        if (is_array($data)) {
            foreach ($data as &$el) {
                $el = self::sanitize($el);
            }
        }
        return $data;
    }

    /**
     * Avatar
     *
     * @param int    $size       Optional. Height and width of the avatar image file in pixels. Default 96.
     * @param string $default    Optional. URL for the default image or a default type. Accepts '404'
     *                           (return a 404 instead of a default image), 'retro' (8bit), 'monsterid'
     *                           (monster), 'wavatar' (cartoon face), 'indenticon' (the "quilt"),
     *                           'mystery', 'mm', or 'mysteryman' (The Oyster Man), 'blank' (transparent GIF),
     *                           or 'gravatar_default' (the Gravatar logo). Default is the value of the
     *                           'avatar_default' option, with a fallback of 'mystery'.
     * @param string $alt        Optional. Alternative text to use in &lt;img&gt; tag. Default empty.
     * @param array  $args       {
     *     Optional. Extra arguments to retrieve the avatar.
     *
     *     @type int          $height        Display height of the avatar in pixels. Defaults to $size.
     *     @type int          $width         Display width of the avatar in pixels. Defaults to $size.
     *     @type bool         $force_default Whether to always show the default image, never the Gravatar. Default false.
     *     @type string       $rating        What rating to display avatars up to. Accepts 'G', 'PG', 'R', 'X', and are
     *                                       judged in that order. Default is the value of the 'avatar_rating' option.
     *     @type string       $scheme        URL scheme to use. See set_url_scheme() for accepted values.
     *                                       Default null.
     *     @type array|string $class         Array or string of additional classes to add to the &lt;img&gt; element.
     *                                       Default null.
     *     @type bool         $force_display Whether to always show the avatar - ignores the show_avatars option.
     *                                       Default false.
     *     @type string       $extra_attr    HTML attributes to insert in the IMG element. Is not sanitized. Default empty.
     * }
     * @return false|string `<img>` tag for the user's avatar. False on failure.
     */
    public function avatar($size = 96, $default = '', $alt = '', $args = null)
    {
        return get_avatar($this, $size, $default, $alt, $args);
    }

    /**
     * Reply link
     *
     * @return string
     */
    public function reply()
    {
        if (get_option('comment_registration') && !is_user_logged_in()) {
            return esc_url(wp_login_url(get_permalink()));
        }
        return esc_url(
            add_query_arg(
                'replytocom',
                $this->comment_ID,
                get_permalink($this->comment_post_ID)
            )
        );
    }
}
