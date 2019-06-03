<?php

namespace LolitaFramework\Data;

/**
 * Class for working with path
 */
abstract class Path {

	/**
	* Join path pieces
	*
	* @param  array  $array     pieces.
	* @param  string $separator directory separator.
	* @return string
	*/
	public static function join( $array = array(), $separator = DIRECTORY_SEPARATOR ) {
		return implode( $separator, $array );
	}

	/**
	* Is path have extension?
	*
	* @param  string  $path file.
	* @return boolean YES / NO.
	*/
	public static function is_have_extension( $path ) {
		return array_key_exists( 'extension', pathinfo( $path ) );
	}
}