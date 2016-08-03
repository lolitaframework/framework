<?php
namespace MyProject\LolitaFramework\Core;

use \MyProject\LolitaFramework;

class Loc
{
    /**
     * Get all helpers
     *
     * @return array Helpers.
     */
    public static function helpers()
    {
        return array(
            'Img'  => __NAMESPACE__ . NS . 'Img',
            'Arr'  => __NAMESPACE__ . NS . 'Arr',
            'Str'  => __NAMESPACE__ . NS . 'Str',
            'Data' => __NAMESPACE__ . NS . 'Data',
        );
    }
    /**
     * Get wp_filesystem
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return Object
     */
    public static function wpFilesystem()
    {
        global $wp_filesystem;

        if (! defined('FS_CHMOD_FILE')) {
            define('FS_CHMOD_FILE', (fileperms(ABSPATH . 'index.php') & 0777 | 0644));
        }

        if (empty($wp_filesystem)) {
            include_once(ABSPATH . '/wp-admin/includes/class-wp-filesystem-base.php');
            include_once(ABSPATH . '/wp-admin/includes/class-wp-filesystem-direct.php');
        }

        return new \WP_Filesystem_Direct(null);
    }

    /**
     * Get post
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return WP_Post current post object.
     */
    public static function post()
    {
        global $post;
        return $post;
    }

    /**
     * Get $wp_query
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return WP_Query instance.
     */
    public static function wpQuery()
    {
        global $wp_query;
        return $wp_query;
    }

    /**
     * Get $wp_rewrite
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return WP_Rewrite instance.
     */
    public static function wpRewrite()
    {
        global $wp_rewrite;
        return $wp_rewrite;
    }

    /**
     * LolitaFramework instance
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return LolitaFramework
     */
    public static function lolita()
    {
        return LolitaFramework::getInstance();
    }
}
