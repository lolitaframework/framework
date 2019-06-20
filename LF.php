<?php
namespace LolitaFramework;

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
	 * @return [LolitaFramewor] object.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Autoload class constructor
	 *
	 * @author Guriev Eugen <gurievcreative@gmail.com>
	 */
	private function __construct() {
		$this->constants();
		spl_autoload_register( array( &$this, 'autoload' ) );
	}

	/**
	 * Define some constants
	 *
	 * @author Guriev Eugen <gurievcreative@gmail.com>
	 */
	public function constants() {
		self::define( 'DS', DIRECTORY_SEPARATOR );
		self::define( 'NS', '\\' );
		return $this;
	}

	/**
	 * Autoload my classes
	 *
	 * @param  mixed $class class.
	 * @return void
	 */
	public function autoload( $class ) {
		$class_path = self::get_class_path( $class );
		if ( file_exists( $class_path ) ) {
			require_once $class_path;
		}
	}

	/**
	 * Get class path
	 *
	 * @param  string $class name.
	 * @return string
	 */
	public static function get_class_path( $class ) {
		$class_path = str_replace( '\\', DS, $class );
		$class_path = str_replace( __NAMESPACE__ . DS, __DIR__ . DS, $class_path );
		return $class_path . '.php';
	}
}
