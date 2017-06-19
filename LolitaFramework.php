<?php
namespace lolita;

use \lolita\LolitaFramework\Core\Url;
use \lolita\LolitaFramework\Core\Ref;

/**
 * Lolita Framework singlton class
 */
class LolitaFramework
{
    /**
     * Base directory
     * It can be plugin directory or theme directory
     * @var null
     */
    private $base_dir = null;

    /**
     * Instance
     * @var null
     */
    private static $instance = null;

    /**
     * Get class instance only once
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $dir
     * @return [LolitaFramewor] object.
     */
    public static function getInstance($dir = null)
    {
        if (null === self::$instance) {
            self::$instance = new self($dir);
        }
        return self::$instance;
    }

    /**
     * Autoload class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $dir
     */
    private function __construct($dir = null)
    {
        $this->setBaseDir($dir);
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
        add_action('init', [$this, 'initConstants']);
    }

    /**
     * Init constants
     * @return void
     */
    public function initConstants()
    {
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
     * Set base dir
     *
     * @param string $dir
     * @return [LolitaFramewor] object.
     */
    public function setBaseDir($dir = null)
    {
        if (is_string($dir)) {
            $this->base_dir = $dir;
        }
        return $this;
    }

    /**
     * Parent directory
     *
     * @return string
     */
    public function baseDir()
    {
        if (null === $this->base_dir) {
            return dirname(self::dir());
        }
        return $this->base_dir;
    }

    /**
     * Parent url
     *
     * @return string
     */
    public function baseUrl()
    {
        return Url::toUrl($this->baseDir());
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
        $second_chance = $this->getClassPathBase($class);
        if (file_exists($class_path)) {
            require_once $class_path;
        } else if (file_exists($second_chance)) {
            require_once $second_chance;
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
        $class_path = str_replace(__NAMESPACE__ . DS, dirname(__DIR__) . DS, $class_path);
        return $class_path . '.php';
    }

    public function getClassPathBase($class)
    {
        $class_path = str_replace('\\', DS, $class);
        $class_path = explode(DS, $class_path);
        unset($class_path[0]);
        $class_path = implode(DS, $class_path);
        return sprintf('%s.php', $this->baseDir() . DS . $class_path);
    }

    /**
     * Create module instance
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $module_name Module / Folder name.
     */
    public function addModule($module_name, $params = array())
    {
        $class = __CLASS__ . NS . $module_name . NS . $module_name;
        if (!$params) {
            $module = new $class();
        } else {
            $module = Ref::create($class, $params);
        }
        if (property_exists($this, $module_name)) {
            if (!is_array($this->$module_name)) {
                $this->$module_name = [];
            }
            array_push($this->$module_name, $module);
        } else {
            $this->$module_name = [$module];
        }
    }
}
