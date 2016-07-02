<?php
namespace zorgboerderij_lenteheuvel_wp;

/**
 * Lolita Framework singlton class
 */
class LolitaFramework
{
    /**
     * Get class instance only once
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
     */
    private function __construct()
    {
        $this->constants();
        spl_autoload_register(array( &$this, 'autoload' ));
    }

    /**
     * Define some constants
     */
    public function constants()
    {
        define('DS', DIRECTORY_SEPARATOR);
        define('NS', '\\');
        define('LF_DIR', dirname(__FILE__));
        define('LF_URL', self::getURLByDirectory(LF_DIR));
        define('BASE_DIR', dirname(LF_DIR));
        define('BASE_URL', self::getURLByDirectory(BASE_DIR));
        define('SITE_URL', get_bloginfo('url'));
        define('AJAX_URL', admin_url('admin-ajax.php'));
        if (!function_exists('wp_create_nonce')) {
            require_once(ABSPATH . DS . 'wp-includes' . DS . 'pluggable.php');
        }
        define('LF_NONCE', wp_create_nonce('Lolita Framework'));
    }

    /**
     * Autoload my classes
     *
     * @return void
     */
    public function autoload($class)
    {
        $class_path = dirname(BASE_DIR) . DS . str_replace('\\', '/', $class) . '.php';
        if (file_exists($class_path)) {
            require_once $class_path;
        }
    }

    /**
     * Create module instance
     *
     * @param [string] $module_name Module / Folder name.
     */
    public function addModule($module_name)
    {
        if (!property_exists($this, $module_name)) {
            $class = __CLASS__ . NS . $module_name . NS . $module_name;
            $this->$module_name = new $class();
        }
    }

    /**
     * Get URL by directory
     *
     * @param  [string] $dir path.
     * @return [string] URL.
     */
    public static function getURLByDirectory($dir)
    {
        $url = str_replace(untrailingslashit(ABSPATH), site_url(), $dir);
        return str_replace('\\', '/', $url);
    }
}
