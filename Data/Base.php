<?php
namespace LolitaFramework\Data;

use \Exception;

/**
 * Class for working with arrays
 */
abstract class Base {

	/**
	 * All alowed methods to work with data
	 */
	public static function allowed_methods() {
		return array(
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
			'array_values',
			'count',
			'current',
			'end',
			'key',
			'next',
			'prev',
			'range',
			'reset',
			'implode',
			'ltrim',
			'rtrim',
			'md5',
			'str_getcsv',
			'str_ireplace',
			'str_pad',
			'str_repeat',
			'str_rot13',
			'str_shuffle',
			'str_split',
			'str_word_count',
			'strcasecmp',
			'strchr',
			'strcmp',
			'strcoll',
			'strcspn',
			'strip_tags',
			'stripcslashes',
			'stripos',
			'stripslashes',
			'stristr',
			'strlen',
			'strnatcasecmp',
			'strnatcmp',
			'strncasecmp',
			'strncmp',
			'strpbrk',
			'strpos',
			'strrchr',
			'strrev',
			'strripos',
			'strrpos',
			'strspn',
			'strstr',
			'strtok',
			'strtolower',
			'strtoupper',
			'strtr',
			'substr_compare',
			'substr_count',
			'substr_replace',
			'substr',
			'trim',
			'ucfirst',
			'ucwords',
			'vfprintf',
			'vprintf',
			'vsprintf',
			'wordwrap',
		);
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
		if ( in_array( $name, self::allowed_methods() ) ) {
			return call_user_func_array( $name, $arguments );
		}

		throw new Exception( 'Function:' . $name . '. Not Found!' );
	}
}
