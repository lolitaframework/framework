<?php

namespace LolitaFramework\Data;

use \Exception;
use \LolitaFramework\Data\Path;

/**
 * Class for working with arrays
 */
abstract class Arr {

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
	public static function append( $array = array(), $value = null ) {
		$array[] = $value;
		return $array;
	}

	/**
	 * Prepend item to array
	 *
	 * @usage lf::prepend([1, 2, 3], 4);
	 *         >> [4, 1, 2, 3]
	 *
	 * @param  array $array original array.
	 * @param  mixed $value new item or value to prepend.
	 * @param  mixed $key new key of item.
	 * @return array
	 */
	public static function prepend( $array = array(), $value, $key = null ) {
		if ( is_null( $key ) ) {
			array_unshift( $array, $value );
		} else {
			$array = [ $key => $value ] + $array;
		}

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
	public static function compact( $array = array() ) {
		return array_values( array_filter( $array ) );
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
		$last_key = array_values( $keys )[ $count_keys - 1 ];
		$tmp_array = &$array;

		for ( $i = 0; $i < $count_keys - 1; $i++ ) {
			$k = $keys[ $i ];
			$tmp_array = &$tmp_array[ $k ];
		}
		$tmp_array[ $last_key ] = $value;
		return $array;
	}

	/**
	 * Determine if the given key exists in the provided array.
	 *
	 * @param  \ArrayAccess|array $array existing array.
	 * @param  string|int         $key to check.
	 * @return bool
	 */
	public static function exists( $array, $key ) {
		if ( $array instanceof ArrayAccess ) {
			return $array->offsetExists( $key );
		}

		return array_key_exists( $key, $array );
	}

	/**
	 * Get an item from an array using "dot" notation.
	 *
	 * @param  \ArrayAccess|array $array where we want to get.
	 * @param  string             $key key with dot's.
	 * @param  mixed              $default value.
	 * @return mixed
	 */
	public static function get( $array, $key, $default = null ) {
		if ( ! static::accessible( $array ) ) {
			return $default;
		}

		if ( is_null( $key ) ) {
			return $array;
		}

		if ( static::exists( $array, $key ) ) {
			return $array[ $key ];
		}

		foreach ( explode( '.', $key ) as $segment ) {
			if ( static::accessible( $array ) && static::exists( $array, $segment ) ) {
				$array = $array[ $segment ];
			} else {
				return $default;
			}
		}

		return $array;
	}

	/**
	 * Check if an item exists in an array using "dot" notation.
	 *
	 * @param  \ArrayAccess|array $array to check.
	 * @param  string             $key keys with dot's.
	 * @return bool
	 */
	public static function has( $array, $key ) {
		if ( ! $array ) {
			return false;
		}

		if ( is_null( $key ) ) {
			return false;
		}

		if ( static::exists( $array, $key ) ) {
			return true;
		}

		foreach ( explode( '.', $key ) as $segment ) {
			if ( static::accessible( $array ) && static::exists( $array, $segment ) ) {
				$array = $array[ $segment ];
			} else {
				return false;
			}
		}

		return true;
	}

	/**
	 * Determines if an array is associative.
	 *
	 * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
	 *
	 * @param  array $array to check.
	 * @return bool
	 */
	public static function is_assoc( $array ) {
		$keys = array_keys( $array );

		return array_keys( $keys ) !== $keys;
	}

	/**
	 * Get a subset of the items from the given array.
	 *
	 * @param  array        $array to get.
	 * @param  array|string $keys to filter.
	 * @return array
	 */
	public static function only( $array, $keys ) {
		return array_intersect_key( $array, array_flip( (array) $keys ) );
	}

	/**
	 * Pluck an array of values from an array.
	 *
	 * @param  array             $array source.
	 * @param  string|array      $value values.
	 * @param  string|array|null $key keys.
	 * @return array
	 */
	public static function pluck( $array, $value, $key = null ) {
		$results = array();
		foreach ( $array as $item ) {
			$item_value = static::get( $item, $value );

			// If the key is "null", we will just append the value to the array and keep
			// looping. Otherwise we will key the array using the value of the key we
			// received from the developer. Then we'll return the final array form.
			if ( is_null( $key ) ) {
				$results[] = $item_value;
			} else {
				$item_key = static::get( $item, $key );

				$results[ $item_key ] = $item_value;
			}
		}

		return $results;
	}

	/**
	 * Remove one or many array items from a given array using "dot" notation.
	 *
	 * @param  array        $array to forget.
	 * @param  array|string $keys to exclude.
	 * @return void
	 */
	public static function forget( $array, $keys ) {
		$tmp_array = &$array;

		$keys = (array) $keys;

		if ( count( $keys ) === 0 ) {
			return;
		}

		foreach ( $keys as $key ) {
			// if the exact key exists in the top-level, remove it.
			if ( static::exists( $array, $key ) ) {
				unset( $array[ $key ] );

				continue;
			}

			$parts = explode( '.', $key );

			$count_keys = count( $parts );
			$last_key = array_values( $parts )[ $count_keys - 1 ];

			for ( $i = 0; $i < $count_keys - 1; $i++ ) {
				$k = $parts[ $i ];
				$tmp_array = &$tmp_array[ $k ];
			}
			unset( $tmp_array[ $last_key ] );
		}
		return $array;
	}

	/**
	 * Filter the array using the given callback.
	 *
	 * @param  array    $array input.
	 * @param  Function $cb callback.
	 * @return array
	 */
	public static function where( $array, $cb ) {
		return array_filter( $array, $cb, ARRAY_FILTER_USE_BOTH );
	}

	/**
	 * Applies the callback to the elements of the given arrays
	 *
	 * @param  array    $arr array.
	 * @param  function $cb callback.
	 * @return array
	 */
	public static function map( $arr, $cb ) {
		return array_map( $cb, $arr );
	}

	/**
	 * Checks if a value exists in an array
	 *
	 * @param  array   $haystack The array.
	 * @param  string  $key The searched value.
	 * @param  boolean $strict If the third parameter strict is set to TRUE then the in_array() function will also check the types of the needle in the haystack.
	 * @return boolean
	 */
	public static function in( $haystack, $key, $strict = false ) {
		return in_array( $key, $haystack, $strict );
	}

	/**
	 * Searches the array for a given value and returns the first corresponding key if successful
	 *
	 * @param  array   $haystack The array.
	 * @param  string  $needle The searched value.
	 * @param  boolean $strict If the third parameter strict is set to TRUE then the search() function will search for identical elements in the haystack. This means it will also perform a strict type comparison of the needle in the haystack, and objects must be the same instance.
	 * @return mixed
	 */
	public static function search( $haystack, $needle, $strict = false ) {
		return array_search( $needle, $haystack, $strict );
	}

	/**
	 * Implement default array functions.
	 *
	 * @param  string $name function name like Arr::merge -> array_merge.
	 * @param  array  $arguments function arguments.
	 * @return mixed
	 *
	 * @throws Exception Function {function_name}. Not Found.
	 */
	public static function __callStatic( $name, $arguments ) {
		$full_name = 'array_' . $name;
		$allowed_methods = array(
			'array_change_key_case',
			'array_chunk',
			'array_column',
			'array_combine',
			'array_count_values',
			'array_diff_assoc',
			'array_diff_key',
			'array_diff_uassoc',
			'array_diff_ukey',
			'array_diff',
			'array_fill_keys',
			'array_fill',
			'array_filter',
			'array_flip',
			'array_intersect_assoc',
			'array_intersect_key',
			'array_intersect_uassoc',
			'array_intersect_ukey',
			'array_intersect',
			'array_key_first',
			'array_key_last',
			'array_keys',
			'array_map',
			'array_merge_recursive',
			'array_merge',
			'array_pad',
			'array_pop',
			'array_product',
			'array_rand',
			'array_reduce',
			'array_replace_recursive',
			'array_replace',
			'array_reverse',
			'array_shift',
			'array_slice',
			'array_splice',
			'array_sum',
			'array_udiff_assoc',
			'array_udiff_uassoc',
			'array_udiff',
			'array_uintersect_assoc',
			'array_uintersect_uassoc',
			'array_uintersect',
			'array_unique',
			'array_unshift',
			'array_values',
			'array_walk_recursive',
			'array_walk',
		);

		if ( self::in( $allowed_methods, $full_name ) ) {
			return call_user_func_array( $full_name, $arguments );
		}
		throw new Exception( 'Function:' . $full_name . '. Not Found!' );
	}
}
