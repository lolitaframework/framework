<?php

namespace LolitaFramework\Data;

use \LolitaFramework\Data\Arr;
use \LolitaFramework\Data\Path;
use \LolitaFramework\Data\View;
use \ReflectionClass;
use \ReflectionMethod;

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
	 * All methods.
	 *
	 * @var mixed
	 */
	private $methods;

	/**
	 * Class constructor
	 *
	 * @param mixed $value current.
	 */
	public function __construct( $value ) {
		$this->value = $value;
		$this->methods = $this->get_all_methods();
	}

	/**
	 * Get all methods
	 *
	 * @return array
	 */
	private function get_all_methods() {
		$all_methods = Arr::map(
			array(
				'Arr',
				'Path',
				'View',
			),
			function( $el ) {
				$reflection = new ReflectionClass( implode( '\\', array( __NAMESPACE__, $el ) ) );
				return $reflection->getMethods( ReflectionMethod::IS_STATIC );
			}
		);

		$all_methods = Arr::reduce(
			$all_methods,
			function( $accumulator, $current ) {
				return array_merge( $accumulator, $current );
			},
			array()
		);

		$all_methods = Arr::reduce(
			$all_methods,
			function( $accumulator, $current ) {
				$accumulator[ $current->name ] = $current->class;
				return $accumulator;
			},
			array()
		);

		return $all_methods;
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
	 * @return string
	 */
	public function __toString() {
		return $this->value();
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
	 * Is have method in methods
	 *
	 * @param  string $name method.
	 * @return boolean
	 */
	private function is_have_method( $name ) {
		return isset( $this->methods[ $name ] );
	}

	/**
	 * Magic call
	 *
	 * @param  string $name method name.
	 * @param  array  $params parameters.
	 * @return Chain
	 * @throws Exception Invalid function.
	 */
	public function __call( $name, $params ) {
		$fn = array( $this->methods[ $name ], $name );
		if ( $this->is_have_method( $name ) && is_callable( $fn, true ) ) {

			$params = null == $params ? array() : $params;
			$params = Arr::prepend( $params, $this->value );
			$this->value = call_user_func_array( $fn, $params );

			return $this;
		} else {
			throw new Exception( "Invalid function { $name }" );
		}
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
