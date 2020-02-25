<?php

namespace lolita\str;

use function lolita\chain;

/**
 * Perform a global regular expression match.
 *
 * @param mixed $value The input string.
 * @param mixed $pattern The pattern to search for, as a string.
 *
 * @return Chain
 */
function match_all( $value, $pattern ) {
	preg_match_all( $pattern, $value, $matches );
	$value = $matches;

	return $value;
}

/**
 * Split a string by a string.
 *
 * @param string $str The input string.
 * @param string $delimiter The boundary string.
 *
 * @return Chain
 */
function explode( $str, $delimiter ) {
	return \explode( $delimiter, $str );
}

/**
 * Add suffix to string.
 *
 * @param  string $str Initial string.
 * @param  string $preffix Preffix.
 * @param  string $suffix Suffix.
 *
 * @return string
 */
function concat( $str, $preffix = '', $suffix = '' ) {
	return $preffix . $str . $suffix;
}

/**
 * Replace all occurrences of the search string with the replacement string.
 *
 * @param  mixed $subject The string or array being searched and replaced on, otherwise known as the haystack.
 * @param  mixed $search The value being searched for, otherwise known as the needle. An array may be used to designate multiple needles.
 * @param  mixed $replace The replacement value that replaces found search values. An array may be used to designate multiple replacements.
 *
 * @return string
 */
function replace( $subject, $search, $replace ) {
	return str_replace( $search, $replace, $subject );
}

/**
 * Interpret data to value
 *
 * @param  mixed $data String with constants or function.
 *
 * @return mixed Compiled string.
 */
function interpret( $data ) {
	if ( is_callable( $data ) ) {
		return $data();
	}
	if ( ! is_string( $data ) ) {
		return $data;
	}
	return chain( $data )
		->match_all( '/{{.*?}}/' )
		->pop()
		->map(
			function( $el ) {
				return array(
					'key'      => $el,
					'constant' => trim( str_replace( array( '{{', '}}' ), '', $el ) ),
				);
			}
		)
		->array_filter(
			function( $el ) {
				return defined( $el['constant'] );
			}
		)
		->map(
			function( $el ) use ( $data ) {
				return str_replace( $el['key'], constant( $el['constant'] ), $data );
			}
		)
		->thru(
			function( $value ) use ( $data ) {
				if ( is_array( $value ) && count( $value ) ) {
					return array_pop( $value );
				}
				return $data;
			}
		)
		->value();
}

/**
 * Generate a globally unique identifier (GUID).
 *
 * @return string
 */
function guid() {
	return sprintf(
		'%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
		wp_rand( 0, 65535 ),
		wp_rand( 0, 65535 ),
		wp_rand( 0, 65535 ),
		wp_rand( 16384, 20479 ),
		wp_rand( 32768, 49151 ),
		wp_rand( 0, 65535 ),
		wp_rand( 0, 65535 ),
		wp_rand( 0, 65535 )
	);
}


/**
 * Get string length
 *
 * @param  mixed $str Some string.
 *
 * @return int
 */
function len( $str ) {
	return mb_strlen( $str, 'utf8' );
}
