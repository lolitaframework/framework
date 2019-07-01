<?php

namespace LolitaFramework\Data;

use \LolitaFramework\Data\Base;
use \LolitaFramework\Data\View;
use \ReflectionClass;
use \ReflectionMethod;
use \Exception;

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
	 * All methods
	 *
	 * @var array
	 */
	private $methods = [];

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
		$reflection = new ReflectionClass( implode( '\\', array( __NAMESPACE__, 'View' ) ) );
		$all_methods = $reflection->getMethods( ReflectionMethod::IS_STATIC );
		$all_methods = array_merge( $all_methods, View::allowed_methods() );

		$all_methods = Base::array_reduce(
			$all_methods,
			function( $accumulator, $current ) {
				if ( $current instanceof ReflectionMethod ) {
					$accumulator[ $current->name ] = $current->class;
				} else {
					$accumulator[ $current ] = implode( '\\', array( __NAMESPACE__, 'Base' ) );
				}
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
	 * Magic call
	 *
	 * @param  string $name method name.
	 * @param  array  $params parameters.
	 * @return Chain
	 * @throws Exception Invalid function.
	 */
	public function __call( $name, $params ) {
		$fn = array( $this->methods[ $name ], $name );
		if ( is_callable( $fn, true ) ) {

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
