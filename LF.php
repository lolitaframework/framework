<?php
namespace LolitaFramework;

// ==============================================================
// Load core classes
// ==============================================================
array_map(
	function( $el ) {
		require_once implode( DIRECTORY_SEPARATOR, array( 'Data', $el ) ) . '.php';
	},
	array(
		'Base',
		'Arr',
		'Str',
		'Path',
		'Chain',
		'View',
		'Fn',
	)
);

use \LolitaFramework\Data\Chain;
use \LolitaFramework\Data\Arr;
use \LolitaFramework\Data\Str;
use \LolitaFramework\Data\View;
use \LolitaFramework\Data\Fn;
use \LolitaFramework\Data\Path;

/**
 * Lolita Framework singlton class
 */
class LF extends Fn {

	/**
	 * Lolita Framewokr folder
	 *
	 * @var string
	 */
	public $lf_folder = '';

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
	 * @return [LolitaFramework] object.
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
		$this->lf_folder = __DIR__;
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
		return Chain::of( $class )
			->str_replace( '\\', DS )
			->str_replace( __NAMESPACE__ . DS, __DIR__ . DS )
			->value() . '.php';
	}

	/**
	 * Create module instance
	 *
	 * @param string $module_name Module / Folder name.
	 */
	public function addModule( $module_name ) {
		if ( ! property_exists( $this, $module_name ) ) {
			$class = Path::join(
				array(
					__NAMESPACE__,
					$module_name,
					$module_name,
				),
				NS
			);
			$this->$module_name = new $class( self::$instance );
		}
	}
}
