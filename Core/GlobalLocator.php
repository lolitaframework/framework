<?php
namespace ECG\LolitaFramework\Core;

class GlobalLocator
{
    /**
     * Get global vaiable
     *
     * @param  [string] $global_name varibale name.
     * @return [mixed] varibale.
     */
    public static function get($global_name)
    {
        $methods = HelperClass::getAllMethods(__CLASS__, \ReflectionMethod::IS_STATIC);

        $methods = array_flip($methods);
        unset($methods[ __METHOD__ ]);
        $methods = array_flip($methods);

        if (in_array($global_name, $methods)) {
            return self::$global_name();
        }
        return null;
    }

    /**
     * Get wp_filesystem
     *
     * @return Object
     */
    public static function getWpFilesystem()
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
}
