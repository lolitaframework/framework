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
	 */
	const KEYS = array(
		'register.scripts',
		'register.styles',
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
		return Chain::of( self::KEYS )
			->map(
				function( $key ) use ( $data ) {
					return LF::get( $data, $key, array() );
				}
			)
			->map(
				function( $data ) {
					return LF::map(
						$data,
						function( $values ) {
							return LF::map( $values, array( '\\LolitaFramework\\LF', 'runOrReturn' ) );
						}
					);
				}
			)
			->bind(
				function( $values ) {
					return LF::array_combine( self::KEYS, $values );
				}
			)
			->value();
	}

	/**
	 * Launch action
	 *
	 * @param  array $data actions data.
	 * @return function
	 */
	public function launch( $data ) {
		return function() use ( $data ) {
			return Chain::of( self::KEYS )
				->map(
					function( $key ) use ( $data ) {
						$fn = LF::str_replace( $key, '.', '_' );
						LF::map(
							$data[ $key ],
							array( &$this, $fn )
						);
						return $data[ $key ];
					}
				)->value();
		};
	}

	/**
	 * Register script to wp core
	 *
	 * @param  array $el script data.
	 * @return array
	 */
	public function register_scripts( $el ) {
		list( $handle, $src, $deps, $ver, $media ) = $el + array( '', false, array(), false, false );
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
		list( $handle, $src, $deps, $ver, $media ) = $el + array( '', false, array(), false, 'all' );
		wp_enqueue_style( $handle, $src, $deps, $ver, $media );
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
