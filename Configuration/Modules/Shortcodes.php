<?php
namespace LolitaFramework\Configuration\Modules;

/**
 * Shortcodes configuration module
 */
class Shortcodes {

	/**
	 * Class constructor
	 *
	 * @param mixed $lf Lolita Framework instance.
	 * @param mixed $data config data.
	 */
	public function __construct( $lf, $data = array() ) {
		$data->map( array( &$this, 'add' ) );
	}

	/**
	 * Add action
	 *
	 * @param array $el action data.
	 */
	public function add( $el ) {
		add_shortcode( $el['tag'], $el['func'] );
		return $el;
	}

	/**
	 * Get required arguments
	 *
	 * @return array
	 */
	public static function required() {
		return array(
			'tag',
			'func',
		);
	}
}
