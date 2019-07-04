<?php
namespace LolitaFramework\Configuration\Modules;

use \LolitaFramework\Data\Chain;
use \LolitaFramework\LF;

/**
 * Assets config module
 */
class Assets {

	/**
	 * Allowed config keys
	 *
	 * @var array
	 */
	private $keys = array(
		'register.scripts',
		'register.styles',
		'localize',
		'deregister.scripts',
		'deregister.styles',
	);

	/**
	 * Class constructor
	 *
	 * @param mixed $lf Lolita Framework instance.
	 * @param mixed $data config data.
	 */
	public function __construct( $lf, $data = array() ) {
		$functions = array(
			'wp_enqueue_scripts',
			'admin_enqueue_scripts',
			'login_enqueue_scripts',
			'customize_controls_enqueue_scripts',
		);
		LF::map( $functions, $this->add_actions( $data ) );
	}

	/**
	 * Add actions to wp core
	 *
	 * @param array $data all.
	 * @return function
	 */
	public function add_actions( $data ) {
		return function( $fn ) use ( $data ) {
			$key = LF::str_replace( $fn, '_enqueue_scripts', '' );
			add_action(
				$fn,
				$this->launch(
					$this->prepare_data( LF::get( $data, $key, array() ) )
				)
			);
		};
	}

	/**
	 * Prepare data
	 *
	 * @param  array $data to prepare.
	 * @return array prepared
	 */
	public function prepare_data( $data ) {
		return Chain::of( $this->keys )
			->map(
				function( $key ) use ( $data ) {
					return LF::get( $data, $key, array() );
				}
			)
			->map( array( &$this, 'run_or_return_parameters' ) )
			->bind(
				function( $values ) {
					return LF::array_combine( $this->keys, $values );
				}
			)
			->value();
	}

	/**
	 * Run function if parameter was function or return value if not
	 *
	 * @param  array $data input.
	 * @return array
	 */
	public function run_or_return_parameters( $data ) {
		return LF::map(
			$data,
			function( $values ) {
				return LF::map( $values, array( '\\LolitaFramework\\LF', 'run_or_return' ) );
			}
		);
	}

	/**
	 * Launch action
	 *
	 * @param  array $data actions data.
	 * @return function
	 */
	public function launch( $data ) {
		return function() use ( $data ) {
			return Chain::of( $this->keys )
				->map(
					function( $key ) use ( $data ) {
						return $this->parse_function( $key, $data );
					}
				)
				->map( array( &$this, 'filter_by_condition' ) )
				->map( array( &$this, 'run_function' ) );
		};
	}

	/**
	 * Run register, deregister, localize function
	 *
	 * @param  array $function_and_data function and data.
	 * @return array
	 */
	public function run_function( $function_and_data ) {
		list( $fn, $data ) = $function_and_data;
		return LF::map( $data, $fn );
	}

	/**
	 * Filter by condition
	 *
	 * @param  array $function_and_data function and data.
	 * @return array
	 */
	public function filter_by_condition( $function_and_data ) {
		list( $fn, $data ) = $function_and_data;
		return array(
			$fn,
			Chain::of( $data )
				->where(
					function( $el ) {
						return $this->check_condition( end( $el ) );
					}
				)
				->compact()
				->value(),
		);
	}

	/**
	 * Parse function from key
	 *
	 * @param  string $key some key.
	 * @param  array  $data input.
	 * @return array
	 */
	public function parse_function( $key, $data ) {
		return array(
			array( &$this, LF::str_replace( $key, '.', '_' ) ),
			$data[ $key ],
		);
	}

	/**
	 * Check condition
	 *
	 * @param  Closure $condition some function.
	 * @return boolean
	 */
	public function check_condition( $condition ) {
		if ( is_callable( $condition ) ) {
			return call_user_func( $condition );
		}
		return true;
	}

	/**
	 * Register script to wp core
	 *
	 * @param  array $el script data.
	 * @return array
	 */
	public function register_scripts( $el ) {
		list( $handle, $src, $deps, $ver, $media ) = $el + array( '', false, array(), false, false, null );
		wp_enqueue_script( $handle, $src, $deps, $ver, $media );
		return $el;
	}

	/**
	 * Register style to wp core
	 *
	 * @param  array $el style data.
	 * @return array
	 */
	public function register_styles( $el ) {
		list( $handle, $src, $deps, $ver, $media ) = $el + array( '', false, array(), false, 'all', null );
		wp_enqueue_style( $handle, $src, $deps, $ver, $media );
		return $el;
	}

	/**
	 * Localizes a registered script with data for a JavaScript variable.
	 *
	 * @param  array $el arguments for wp_localize_script.
	 * @return array
	 */
	public function localize( $el ) {
		list( $handle, $object_name, $l10n ) = $el + array( '', '', array(), null );
		wp_localize_script( $handle, $object_name, $l10n );
		return $el;
	}

	/**
	 * Deregister scipt from wp core
	 *
	 * @param  string $el handler.
	 * @return void
	 */
	public function deregister_scripts( $el ) {
		wp_deregister_script( $el );
	}

	/**
	 * Deregister style from wp core
	 *
	 * @param  string $el handler.
	 * @return void
	 */
	public function deregister_styles( $el ) {
		wp_deregister_style( $el );
	}
}
