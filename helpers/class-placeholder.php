<?php

namespace lolita;

/**
 * This class is created simply to define a special type
 * for the placeholder. As defining a constant, even
 * a random one, could collide with other values.
 */
class Placeholder {
	/**
	 * Class instance.
	 *
	 * @var Placeholder
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Get the instance.
	 *
	 * @return Placeholder
	 */
	public static function get() {
		if ( null === static::$instance ) {
			static::$instance = new Placeholder();
		}
		return static::$instance;
	}

	/**
	 * Convert to string.
	 *
	 * @return string
	 */
	public function __toString() {
		return '__';
	}
}
