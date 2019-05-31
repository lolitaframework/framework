<?php

namespace LolitaFramework\Data;

/**
 * Class for working with arrays
 */
class Arr {

	/**
	 * Append item to array
	 *
	 * @usage lf::append([1, 2, 3], 4);
	 *        >> [1, 2, 3, 4]
	 *
	 * @param array $array original array.
	 * @param mixed $value new item or value to append.
	 *
	 * @return array
	 */
	public static function append( $array = [], $value = null ) {
		$array[] = $value;
		return $array;
	}

	/**
	 * Creates  an  array  with  all  falsey  values removed. The values false, null, 0, "", undefined, and NaN are all
	 * falsey.
	 *
	 * @usage __::compact([0, 1, false, 2, '', 3]);
	 *        >> [1, 2, 3]
	 *
	 * @param array $array array to compact.
	 *
	 * @return array
	 */
	public static function compact( $array = [] ) {
		return array_values(
			array_filter(
				$array,
				function( $var ) {
					return $var;
				}
			)
		);
	}

	/**
	 * Determine whether the given value is array accessible.
	 *
	 * @param  mixed $value checkin to accessible.
	 * @return bool
	 */
	public static function accessible( $value ) {
		return is_array( $value ) || $value instanceof ArrayAccess;
	}

	/**
	 * Divide an array into two arrays. One with keys and the other with values.
	 *
	 * @param  array $array array to divide.
	 * @return array
	 */
	public static function divide( $array ) {
		return array( array_keys( $array ), array_values( $array ) );
	}

	/**
	 * Set an array item to a given value using "dot" notation.
	 *
	 * If no key is given to the method, the entire array will be replaced.
	 *
	 * @param  array  $array existing array.
	 * @param  string $key path to set.
	 * @param  mixed  $value value to set.
	 * @param  string $separator separator.
	 * @return array new array.
	 */
	public static function set( $array, $key, $value, $separator = '.' ) {
		if ( is_null( $key ) ) {
			$array = $value;
			return $array;
		}

		$keys = explode( $separator, $key );
		$count_keys = count( $keys );
		$tmp_array = &$array;

		while ( $count_keys > 1 ) {
			$key = array_shift( $keys );

			// If the key doesn't exist at this depth, we will just create an empty array
			// to hold the next value, allowing us to create the arrays to hold final
			// values at the correct depth. Then we'll keep digging into the array.
			if ( ! isset( $tmp_array[ $key ] ) || ! is_array( $tmp_array[ $key ] ) ) {
				$tmp_array[ $key ] = [];
			}

			$tmp_array = &$tmp_array[ $key ];
		}

		$tmp_array[ array_shift( $keys ) ] = $value;
		return $array;
	}
}
