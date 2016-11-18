<?php
namespace lolitatheme;

use \lolitatheme\LolitaFramework\Core\Url;

/**
 * Lolita Framework singlton class
 */
class LolitaFramework
{
    /**
     * Get class instance only once
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [LolitaFramewor] object.
     */
    public static function getInstance()
    {
        static $instance;
        if (isset($instance)) {
            return $instance;
        }
        $self = new self();
        return $self;
    }

    /**
     * Autoload class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    private function __construct()
    {
        spl_autoload_register(array( &$this, 'autoload' ));
        $this->constants();
        load_theme_textdomain('lolita', __DIR__ . DS . 'languages');
    }

    /**
     * Define some constants
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function constants()
    {
        self::define('DS', DIRECTORY_SEPARATOR);
        self::define('NS', '\\');
        self::define('SITE_URL', get_bloginfo('url'));
        self::define('AJAX_URL', admin_url('admin-ajax.php'));
        if (!function_exists('wp_create_nonce')) {
            require_once(ABSPATH . DS . 'wp-includes' . DS . 'pluggable.php');
        }
        self::define('LF_NONCE', wp_create_nonce('Lolita Framework'));
    }

    /**
     * LolitaFramework directory
     *
     * @return string
     */
    public static function dir()
    {
        return __DIR__;
    }

    /**
     * Lolita Framework URL
     *
     * @return string
     */
    public static function url()
    {
        return Url::toUrl(self::dir());
    }

    /**
     * Parent directory
     *
     * @return string
     */
    public static function baseDir()
    {
        return dirname(self::dir());
    }

    /**
     * Parent url
     *
     * @return string
     */
    public static function baseUrl()
    {
        return Url::toUrl(self::baseDir());
    }

    /**
     * Define constant
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public static function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * Autoload my classes
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return void
     */
    public function autoload($class)
    {
        $class_path = self::getClassPath($class);
        if (file_exists($class_path)) {
            require_once $class_path;
        }
    }

    /**
     * Get class path
     *
     * @param  string $class
     * @return string
     */
    public static function getClassPath($class)
    {
        $class_path = str_replace('\\', DS, $class);
        $class_path = str_replace(__NAMESPACE__ . DS, self::baseDir() . DS, $class_path);
        return $class_path . '.php';
    }

    /**
     * Create module instance
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $module_name Module / Folder name.
     */
    public function addModule($module_name)
    {
        if (!property_exists($this, $module_name)) {
            $class = __CLASS__ . NS . $module_name . NS . $module_name;
            $this->$module_name = new $class();
        }
    }
}
