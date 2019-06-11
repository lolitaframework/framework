<?php

namespace LolitaFramework\Data;

use \LolitaFramework\Data\Arr;
use \LolitaFramework\Data\Path;
use \LolitaFramework\Data\View;

/**
 * Class for working with views
 */
class Chain {

	/**
	 * Current value.
	 *
	 * @var mixed
	 */
	private $value;

	/**
	 * Class constructor
	 *
	 * @param mixed $value current.
	 */
	public function __construct( $value ) {
		$this->value = $value;
	}

	/**
	 * Bind some function to value.
	 *
	 * @param  mixed $fn some function.
	 * @return Chain
	 */
	public function bind( $fn ) {
		$this->value = $fn( $this->value );
		return $this;
	}

	/**
	 * Get value
	 *
	 * @return mixed
	 */
	public function value() {
		return $this->value;
	}

	/**
	 * Create myself
	 *
	 * @param  mixed $value current.
	 * @return Chain
	 */
	public static function of( $value = null ) {
		return new self( $value );
	}
}
