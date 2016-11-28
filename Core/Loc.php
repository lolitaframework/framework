<?php
namespace lolita\LolitaFramework\Core;

use \lolita\LolitaFramework;

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
            '__Img'  => __NAMESPACE__ . NS . 'Img',
            '__Arr'  => __NAMESPACE__ . NS . 'Arr',
            '__Str'  => __NAMESPACE__ . NS . 'Str',
            '__Data' => __NAMESPACE__ . NS . 'Data',
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
     * Get $wpdb
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return wpdb instance.
     */
    public static function wpdb()
    {
        global $wpdb;
        return $wpdb;
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

    /**
     * Get global $wp_version
     *
     * @return string
     */
    public static function wpVersion()
    {
        global $wp_version;
        return $wp_version;
    }

    /**
     * Get global $current_site
     *
     * @return string
     */
    public static function currentSite()
    {
        global $current_site;
        return $current_site;
    }

    /**
     * Get global wp_customize
     *
     * @return WP_Customize_Manager instance
     */
    public static function wpCustomize()
    {
        global $wp_customize;
        return $wp_customize;
    }

    /**
     * Get global wp_filter
     *
     * @return array
     */
    public static function wpFilter()
    {
        global $wp_filter;
        return $wp_filter;
    }

    /**
     * Pagenow
     *
     * @return mixed
     */
    public static function pagenow()
    {
        return Arr::get($GLOBALS, 'pagenow', null);
    }

    /**
     * Browser version
     * Check if the user needs a browser update.
     *
     * @return mixed
     */
    public static function browserVersion()
    {
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        $key = md5($_SERVER['HTTP_USER_AGENT']);
        $response = get_site_transient('browser_' . $key);
        if (false === $response) {
            $wp_version = self::wpVersion();
            $options = array(
                'body'       => array('useragent' => $_SERVER['HTTP_USER_AGENT']),
                'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url(),
            );
            $response = wp_remote_post('http://api.wordpress.org/core/browse-happy/1.1/', $options);
            if (is_wp_error($response) || 200 != wp_remote_retrieve_response_code($response)) {
                return false;
            }
            /**
             * Response should be an array with:
             *  'name' - string - A user friendly browser name
             *  'version' - string - The version of the browser the user is using
             *  'current_version' - string - The most recent version of the browser
             *  'upgrade' - boolean - Whether the browser needs an upgrade
             *  'insecure' - boolean - Whether the browser is deemed insecure
             *  'upgrade_url' - string - The url to visit to upgrade
             *  'img_src' - string - An image representing the browser
             *  'img_src_ssl' - string - An image (over SSL) representing the browser
             */
            $response = json_decode(wp_remote_retrieve_body($response), true);
            if (!is_array($response)) {
                return false;
            }
            set_site_transient('browser_' . $key, $response, WEEK_IN_SECONDS);
        }
        return $response;
    }
}
