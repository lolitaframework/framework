<?php

namespace lolita\arr;

/**
 * Flatten a multi-dimensional array into a single level.
 *
 * @param array $value Some multi-dimensional array.
 *
 * @return array
 */
function flatten( $value ) {
	if ( is_array( $value ) && count( $value ) ) {
		return array_reduce(
			$value,
			function( $acc, $cur ) {
				return array_merge( $acc, $cur );
			},
			array()
		);
	}

	return $value;
}


/**
 * Prend item or value to an array
 *
 * @param mixed $arr Input array.
 * @param mixed $value Some value to prepend.
 *
 * @return array
 */
function prepend( $arr, $value ) {
	array_unshift( $arr, $value );

	return $arr;
}

/**
 * Shift an element off the beginning of array
 *
 * @param  mixed $arr Input array.
 *
 * @return mixed Returns the shifted value, or NULL if array is empty or is not an array.
 */
function head( $arr ) {
	return array_shift( $arr );
}

/**
 * Join array elements with a string.
 *
 * @param mixed  $arr Input array.
 * @param string $glue Defaults to an empty string.
 *
 * @return string
 */
function implode( $arr, $glue = '' ) {
	return \implode( $glue, $arr );
}

/**
 * Applies the callback to the elements of the given arrays.
 *
 * @param mixed    $arr Input array.
 * @param callable $cb Callback.
 *
 * @return array
 */
function map( $arr, $cb ) {
	return array_map( $cb, $arr );
}

/**
 * Prepend one or more elements to the beginning of an array
 *
 * @param  mixed $arr The input array.
 * @param  mixed $value The values to prepend.
 *
 * @return array
 */
function unshift( $arr, $value ) {
	array_unshift( $arr, $value );
	return $arr;
}

/**
 * Pop the element off the end of array
 *
 * @param  mixed $arr The array to get the value from.
 *
 * @return mixed
 */
function pop( $arr ) {
	return array_pop( $arr );
}

/**
 * Sort an array by values using a user-defined comparison function.
 *
 * @param  array    $arr Input array.
 * @param  Function $fn The comparison function must return an integer less than, equal to, or greater than zero if the first argument is considered to be respectively less than, equal to, or greater than the second.
 *
 * @return array
 */
function sort( $arr, $fn ) {
	usort( $arr, $fn );
	return $arr;
}

/**
 * Determine whether the given value is array accessible.
 *
 * @param mixed $value Checkin to accessible.
 *
 * @return bool
 */
function accessible( $value ) {

	return is_array( $value ) || $value instanceof ArrayAccess;
}

/**
 * Set an array item to a given value using "dot" notation.
 *
 * If no key is given to the method, the entire array will be replaced.
 *
 * @param array  $array     Existing array.
 * @param string $key       Path to set.
 * @param mixed  $value     Value to set.
 * @param string $separator Separator.
 *
 * @return array New array.
 */
function set( $array, $key, $value, $separator = '.' ) {

	if ( ! accessible( $array ) ) {
		return $value;
	}

	if ( is_null( $key ) ) {
		$array = $value;

		return $array;
	}

	$keys       = explode( $separator, $key );
	$count_keys = count( $keys );
	$values     = array_values( $keys );
	$last_key   = $values[ $count_keys - 1 ];
	$tmp_array  = &$array;

	for ( $i = 0; $i < $count_keys - 1; $i ++ ) {
		$k         = $keys[ $i ];
		$tmp_array = &$tmp_array[ $k ];
	}
	$tmp_array[ $last_key ] = $value;

	return $array;
}

/**
 * Determine if the given key exists in the provided array.
 *
 * @param \ArrayAccess|array $array Existing array.
 * @param string|int         $key   To check.
 *
 * @return bool
 */
function exists( $array, $key ) {

	if ( ! accessible( $array ) ) {
		return false;
	}
	if ( $array instanceof ArrayAccess ) {
		return $array->offsetExists( $key );
	}

	return array_key_exists( $key, $array );
}

/**
 * Get an item from an array using "dot" notation.
 *
 * @param \ArrayAccess|array $array   Where we want to get.
 * @param string             $key     Key with dot's.
 * @param mixed              $default Value.
 *
 * @return mixed
 */
function get( $array, $key, $default = null ) {

	if ( ! accessible( $array ) ) {
		return $default;
	}
	if ( is_null( $key ) ) {
		return $array;
	}
	if ( ! is_string( $key ) ) {
		return $default;
	}
	if ( exists( $array, $key ) ) {
		return $array[ $key ];
	}
	foreach ( explode( '.', $key ) as $segment ) {
		if ( accessible( $array ) && exists( $array, $segment ) ) {
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
 * @param \ArrayAccess|array $array To check.
 * @param string             $key   Keys with dot's.
 *
 * @return bool
 */
function has( $array, $key ) {

	if ( ! $array ) {
		return false;
	}
	if ( is_null( $key ) || ! is_string( $key ) ) {
		return false;
	}
	if ( exists( $array, $key ) ) {
		return true;
	}
	foreach ( explode( '.', $key ) as $segment ) {
		if ( accessible( $array ) && exists( $array, $segment ) ) {
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
 * @param array $array To check.
 *
 * @return bool
 */
function is_assoc( $array ) {

	$keys = array_keys( $array );

	return array_keys( $keys ) !== $keys;
}

/**
 * Get a subset of the items from the given array.
 *
 * @param array        $array To get.
 * @param array|string $keys  To filter.
 *
 * @return array
 */
function only( $array, $keys ) {

	return array_intersect_key( $array, array_flip( (array) $keys ) );
}

/**
 * Remove one or many array items from a given array using "dot" notation.
 *
 * @param array        $array To forget.
 * @param array|string $keys  To exclude.
 *
 * @return array
 */
function forget( $array, $keys ) {

	$tmp_array = &$array;
	$keys      = (array) $keys;

	if ( count( $keys ) === 0 ) {
		return $array;
	}

	foreach ( $keys as $key ) {
		// if the exact key exists in the top-level, remove it.
		if ( exists( $array, $key ) ) {
			unset( $array[ $key ] );
			continue;
		}

		$parts      = explode( '.', $key );
		$count_keys = count( $parts );
		$values     = array_values( $parts );
		$last_key   = $values[ $count_keys - 1 ];

		for ( $i = 0; $i < $count_keys - 1; $i ++ ) {
			$k         = $parts[ $i ];
			$tmp_array = &$tmp_array[ $k ];
		}
		unset( $tmp_array[ $last_key ] );
	}

	return $array;
}

/**
 * Convert value to array.
 *
 * @param  mixed $value Some value.
 * @param  mixed $key Some key.
 *
 * @return array
 */
function to( $value, $key = null ) {
	if ( $key ) {
		return array( $key => $value );
	}
	return (array) $value;
}

/**
 * Move elements inside of array.
 *
 * @param  mixed $array Input array.
 * @param  mixed $from From key.
 * @param  mixed $to To Index.
 *
 * @return array
 */
function move( $array, $from, $to ) {
	$val_to_move = $array[ $from ];
	unset( $array[ $from ] );

	return array_slice( $array, 0, $to, true ) +
		array( $from => $val_to_move ) +
		array_slice( $array, $to, count( $array ), true );
}

/**
 * Get the rest of the array except first one.
 *
 * @param array $args Input array.
 * @return array Array without first element.
 */
function rest( $args ) {
	return array_slice( $args, 1 );
}

/**
 * Get the last element in an array.
 *
 * @param  array $array Input array.
 * @return mixed
 */
function last( $array ) {
	return $array[ count( $array ) - 1 ];
}

