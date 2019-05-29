<?php
// phpcs:ignoreFile
namespace LolitaFramework;

use \LolitaFramework\Data\Arr;

/**
 * Lolita Framework singlton class
 */
class LF {

    /**
     * Instance
     *
     * @var null
     */
    private static $instance = null;

    /**
     * Define constant
     *
     * @param  string $name unique name.
     * @param  mixed  $value some value.
     * @return void
     */
    public static function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Get class instance only once
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $dir
     * @return [LolitaFramewor] object.
     */
    public static function getInstance($dir = null) {
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
    private function __construct($dir = null) {
        $this->constants();
        spl_autoload_register(array( &$this, 'autoload' ));
    }

    /**
     * Define some constants
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function constants() {
        self::define('DS', DIRECTORY_SEPARATOR);
        self::define('NS', '\\');
        return $this;
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
        $class_path = str_replace(__NAMESPACE__ . DS, __DIR__ . DS, $class_path);
        return $class_path . '.php';
    }

    public static function __callStatic($name, $arguments) {
        $dic = array(
            '\LolitaFramework\Data\Arr::'
        );
        foreach ($dic as $namespace) {
            if (is_callable($namespace . $name)) {
                return call_user_func_array($namespace . $name, $arguments);
            }
        }
        throw new Exception("Invalid function {$name}");
    }
}
