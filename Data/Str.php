<?php

namespace LolitaFramework\Data;

use \LolitaFramework\Data\Arr;

/**
 * Class for working with strings
 */
abstract class Str extends Arr {

	/**
	 * Convert the given string to lower-case.
	 *
	 * @param  string $value upeer case string.
	 * @return string
	 */
	public static function lower( $value ) {
		return mb_strtolower( $value, 'UTF-8' );
	}


	/**
	 *  Replace all occurrences of the search string with the replacement string
	 *
	 * @param  string $subject The string or array being searched and replaced on, otherwise known as the haystack.
	 * @param  string $search The value being searched for, otherwise known as the needle. An array may be used to designate multiple needles.
	 * @param  string $replace The replacement value that replaces found search values. An array may be used to designate multiple replacements.
	 * @param  int    $count   If passed, this will be set to the number of replacements performed.
	 * @return string
	 */
	public static function str_replace( $subject, $search, $replace, $count = 0 ) {
		return str_replace( $search, $replace, $subject, $count );
	}
}
