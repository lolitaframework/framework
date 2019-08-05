<?php
namespace LolitaFramework\Configuration\Modules;

use \LolitaFramework\LF;

/**
 * Assets config module
 */
class RegisterScripts {
	/**
	 * Class constructor
	 *
	 * @param mixed $lf Lolita Framework instance.
	 * @param mixed $data config data.
	 */
	public function __construct( $lf, $data = array() ) {
		$data->array_reduce(
			array( $this, 'group_by_context' ),
			array(
				'wp'                 => array(),
				'admin'              => array(),
				'login'              => array(),
				'customize_controls' => array(),
			)
		)
		->where( array( $this, 'filter_empty_context' ) )
		->walk( array( $this, 'add_actions' ) );
	}

	/**
	 * Set defaults
	 *
	 * @param array $el input.
	 */
	public static function defaults( $el ) {
		return array_merge(
			array(
				'src'       => '',
				'deps'      => array(),
				'ver'       => false,
				'in_footer' => false,
				'context'   => array( 'wp', 'admin', 'login', 'customize_controls' ),
				'condition' => true,
			),
			$el
		);
	}

	/**
	 * Enqueue script
	 *
	 * @param array $item script object.
	 */
	public function add( $item ) {
		$condition = LF::run_or_return( $item['condition'] );
		$src       = LF::run_or_return( $item['src'] );
		$deps      = LF::run_or_return( $item['deps'] );
		$ver       = LF::run_or_return( $item['ver'] );
		$in_footer = $item['in_footer'];
		$handle    = $item['handle'];
		if ( $condition ) {
			wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
		}
	}

	/**
	 * Add actions
	 *
	 * @param array  $items scripts array.
	 * @param string $key   action key.
	 */
	public function add_actions( $items, $key ) {
		add_action(
			$key . '_enqueue_scripts',
			function() use ( $items ) {
				array_walk( $items, array( $this, 'add' ) );
			}
		);
		return;
	}

	/**
	 * Filter empty context
	 *
	 * @param  array $context input.
	 * @return boolean
	 */
	public function filter_empty_context( $context ) {
		return count( $context ) > 0;
	}

	/**
	 * Group by context
	 *
	 * @param  array $accumulator input.
	 * @param  array $current     current item.
	 * @return array
	 */
	public function group_by_context( $accumulator, $current ) {
		array_walk(
			$current['context'],
			function( $context ) use ( &$accumulator, $current ) {
				$accumulator[ $context ][] = $current;
			}
		);
		return $accumulator;
	}

	/**
	 * Get required parameters
	 *
	 * @return array
	 */
	public static function required() {
		return array(
			'handle',
		);
	}
}
