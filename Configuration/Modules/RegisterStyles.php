<?php
namespace LolitaFramework\Configuration\Modules;

use \LolitaFramework\LF;
use \LolitaFramework\Configuration\Modules\RegisterScripts;

/**
 * RegisterStyles config module
 */
class RegisterStyles extends RegisterScripts {

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
				'media'     => 'all',
				'context'   => array( 'wp', 'admin', 'login', 'customize_controls' ),
				'condition' => true,
			),
			$el
		);
	}

	/**
	 * Enqueue style
	 *
	 * @param array $item style object.
	 */
	public function add( $item ) {
		$condition = LF::run_or_return( $item['condition'] );
		$src       = LF::run_or_return( $item['src'] );
		$deps      = LF::run_or_return( $item['deps'] );
		$ver       = LF::run_or_return( $item['ver'] );
		$media     = $item['media'];
		$handle    = $item['handle'];
		if ( $condition ) {
			wp_enqueue_style( $handle, $src, $deps, $ver, $media );
		}
	}
}
