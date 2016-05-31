<?php
namespace ECG\LolitaFramework\Core;

class GlobalLocator
{
    /**
     * Get wp_filesystem
     *
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
     * @return WP_Post current post object.
     */
    public static function post()
    {
        global $post;
        return $post;
    }
}
