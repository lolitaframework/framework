<?php

namespace LolitaFramework\Data;

use \Exception;
use \ArrayAccess;
use \LolitaFramework\Data\Base;

/**
 * Class for working with arrays
 */
abstract class Arr extends Base {

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
	 * Sort an array in reverse order and maintain index association
	 *
	 * @param  array $array The input array.
	 * @param  int   $sort_flags You may modify the behavior of the sort using the optional parameter sort_flags, for details see sort().
	 * @return array
	 */
	public static function arsort( $array, $sort_flags = SORT_REGULAR ) {
		arsort( $array, $sort_flags );
		return $array;
	}

	/**
	 * Sort an array in reverse order and maintain index association
	 *
	 * @param  array $array The input array.
	 * @param  int   $sort_flags You may modify the behavior of the sort using the optional parameter sort_flags, for details see sort().
	 * @return array
	 */
	public static function asort( $array, $sort_flags = SORT_REGULAR ) {
		asort( $array, $sort_flags );
		return $array;
	}

	/**
	 * Sort an array using a case insensitive "natural order" algorithm
	 *
	 * @param  array $array The input array.
	 * @return array
	 */
	public static function natcasesort( $array ) {
		natcasesort( $array );
		return $array;
	}

	/**
	 * Sort an array by key in reverse order
	 *
	 * @param  array $array The input array.
	 * @param  int   $sort_flags You may modify the behavior of the sort using the optional parameter sort_flags, for details see sort().
	 * @return array
	 */
	public static function krsort( $array, $sort_flags = SORT_REGULAR ) {
		krsort( $array, $sort_flags );
		return $array;
	}

	/**
	 * Sort an array by key
	 *
	 * @param  array $array The input array.
	 * @param  int   $sort_flags You may modify the behavior of the sort using the optional parameter sort_flags, for details see sort().
	 * @return array
	 */
	public static function ksort( $array, $sort_flags = SORT_REGULAR ) {
		ksort( $array, $sort_flags );
		return $array;
	}

	/**
	 * Sort an array using a "natural order" algorithm
	 *
	 * @param  array $array The input array.
	 * @return array
	 */
	public static function natsort( $array ) {
		natsort( $array );
		return $array;
	}

	/**
	 * Sort an array in reverse order
	 *
	 * @param  array $array The input array.
	 * @param  int   $sort_flags You may modify the behavior of the sort using the optional parameter sort_flags, for details see sort().
	 * @return array
	 */
	public static function rsort( $array, $sort_flags = SORT_REGULAR ) {
		rsort( $array, $sort_flags );
		return $array;
	}

	/**
	 * Sort an array
	 *
	 * @param  array $array The input array.
	 * @param  int   $sort_flags You may modify the behavior of the sort using the optional parameter sort_flags, for details see sort().
	 * @return array The optional second parameter sort_flags may be used to modify the sorting behavior using these values.
	 */
	public static function sort( $array, $sort_flags = SORT_REGULAR ) {
		sort( $array, $sort_flags );
		return $array;
	}

	/**
	 * Sort an array with a user-defined comparison function and maintain index association
	 *
	 * @param  array    $array The input array.
	 * @param  callable $value_compare_func See usort() and uksort() for examples of user-defined comparison functions.
	 * @return array
	 */
	public static function uasort( $array, $value_compare_func ) {
		uasort( $array, $value_compare_func );
		return $array;
	}

	/**
	 * Sort an array by keys using a user-defined comparison function
	 *
	 * @param  array    $array The input array.
	 * @param  callable $key_compare_func The comparison function must return an integer less than, equal to, or greater than zero if the first argument is considered to be respectively less than, equal to, or greater than the second. Note that before PHP 7.0.0 this integer had to be in the range from -2147483648 to 2147483647.
	 * @return array
	 */
	public static function uksort( $array, $key_compare_func ) {
		uksort( $array, $key_compare_func );
		return $array;
	}

	/**
	 * Sort an array by values using a user-defined comparison function
	 *
	 * @param  array    $array The input array.
	 * @param  callable $value_compare_func The comparison function must return an integer less than, equal to, or greater than zero if the first argument is considered to be respectively less than, equal to, or greater than the second. Note that before PHP 7.0.0 this integer had to be in the range from -2147483648 to 2147483647.
	 * @return array
	 */
	public static function usort( $array, $value_compare_func ) {
		usort( $array, $value_compare_func );
		return $array;
	}

	/**
	 * Shuffle an array
	 *
	 * @param  array $array The array.
	 * @return array
	 */
	public static function shuffle( $array ) {
		shuffle( $array );
		return $array;
	}

	/**
	 * Run first ro second cb based on condition
	 *
	 * @param  array    $array input.
	 * @param  Function $condition    condition function.
	 * @param  Function $true_result  if codition will return true we run this function.
	 * @param  Function $false_result if codition will return false we run this function.
	 * @return array
	 */
	public static function iif( $array, $condition, $true_result, $false_result = null ) {
		if ( ! is_callable( $false_result ) ) {
			$false_result = function() {
				return '';
			};
		}
		return self::map(
			$array,
			function( $el ) use ( $condition, $true_result, $false_result ) {
				if ( call_user_func( $condition, $el ) ) {
					return call_user_func( $true_result, $el );
				}
				return call_user_func( $false_result, $el );
			}
		);
	}

	/**
	 * Decode json to array
	 *
	 * @param  string $str some string.
	 * @return array
	 */
	public static function json_to_array( $str ) {
		try {
			$result = json_decode( $str, true );
		} catch ( Exception $e ) {
			$result = array();
		}
		return $result;
	}
}
