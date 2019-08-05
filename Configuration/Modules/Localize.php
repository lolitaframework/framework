<?php
namespace LolitaFramework\Configuration\Modules;

use \LolitaFramework\LF;
use \LolitaFramework\Configuration\Modules\RegisterScripts;

/**
 * Localize config module
 */
class Localize extends RegisterScripts {

	/**
	 * Module priority
	 */
	const PRIORITY = 101;

	/**
	 * Set defaults
	 *
	 * @param array $el input.
	 */
	public static function defaults( $el ) {
		return array_merge(
			array(
				'condition' => true,
				'context'   => array( 'wp', 'admin', 'login', 'customize_controls' ),
			),
			$el
		);
	}

	/**
	 * Localize script
	 *
	 * @param array $item script object.
	 */
	public function add( $item ) {
		$condition = LF::run_or_return( $item['condition'] );
		$handle    = LF::run_or_return( $item['handle'] );
		$name      = LF::run_or_return( $item['name'] );
		$data      = LF::run_or_return( $item['data'] );
		if ( $condition ) {
			wp_localize_script( $handle, $name, $data );
		}
	}

	/**
	 * Get required parameters
	 *
	 * @return array
	 */
	public static function required() {
		return array(
			'handle',
			'name',
			'data',
		);
	}
}
