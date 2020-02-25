<?php

namespace lolita;

use function lolita\arr\forget;
use function lolita\arr\flatten;
use function lolita\str\match_all;
use function lolita\arr\prepend;
use function lolita\arr\implode;
use function lolita\arr\pop;
use function lolita\arr\map;
use function lolita\arr\unshift;
use function lolita\str\explode;
use function lolita\str\replace;
use function lolita\str\concat;
use function lolita\arr\sort;
use function lolita\arr\get;
use function lolita\arr\to;

/**
 * Chain monad, useful for chaining certain array or string related functions.
 */
class Chain {

	/**
	 * Current value.
	 *
	 * @var mixed
	 */
	private $value;

	/**
	 * Class constructor.
	 *
	 * @param mixed $value Current value to start working with.
	 */
	public function __construct( $value ) {

		$this->value = $value;
	}

	/**
	 * Bind some function to value.
	 *
	 * @param mixed $fn Some function.
	 *
	 * @return Chain
	 */
	public function thru( $fn ) {

		$this->value = $fn( $this->value );

		return $this;
	}

	/**
	 * Bind some function to value without returning something.
	 *
	 * @param mixed $fn Some function.
	 *
	 * @return Chain
	 */
	public function tap( $fn ) {
		$fn( $this->value );
		return $this;
	}

	/**
	 * Get value.
	 *
	 * @return mixed
	 */
	public function value() {

		return $this->value;
	}

	/**
	 * Magic call.
	 *
	 * @param string $name Method name.
	 * @param array  $params Parameters.
	 *
	 * @throws \BadFunctionCallException Invalid function is called.
	 *
	 * @return Chain
	 */
	public function __call( $name, $params ) {
		$methods = $this->allowed_methods();

		if ( array_key_exists( $name, $methods ) ) {
			$fn     = $methods[ $name ];
			$params = null === $params ? array() : $params;
			array_unshift( $params, $this->value );
			$this->value = call_user_func_array( $fn, $params );

			return $this;
		}

		throw new \BadFunctionCallException( "Provided function { $name } is not allowed. See Chain::allowed_methods()." );
	}

	/**
	 * Run first or second callback based on a condition.
	 *
	 * @param callable $condition Condition function.
	 * @param callable $true_result If condition will return true we run this function.
	 * @param callable $false_result If condition will return false we run this function.
	 *
	 * @return Chain
	 */
	public function iif( $condition, $true_result, $false_result = null ) {

		if ( ! is_callable( $false_result ) ) {
			$false_result = function() {
				return '';
			};
		}
		if ( call_user_func( $condition, $this->value ) ) {
			$this->value = call_user_func( $true_result, $this->value );
		} else {
			$this->value = call_user_func( $false_result, $this->value );
		}

		return $this;
	}

	/**
	 * All allowed methods to work with data.
	 *
	 * @return array
	 */
	public function allowed_methods() {

		return array(
			'array_change_key_case'   => 'array_change_key_case',
			'array_chunk'             => 'array_chunk',
			'array_column'            => 'array_column',
			'array_combine'           => 'array_combine',
			'array_count_values'      => 'array_count_values',
			'array_diff_assoc'        => 'array_diff_assoc',
			'array_diff_key'          => 'array_diff_key',
			'array_diff_uassoc'       => 'array_diff_uassoc',
			'array_diff_ukey'         => 'array_diff_ukey',
			'array_diff'              => 'array_diff',
			'array_fill_keys'         => 'array_fill_keys',
			'array_fill'              => 'array_fill',
			'array_filter'            => 'array_filter',
			'array_flip'              => 'array_flip',
			'array_intersect_assoc'   => 'array_intersect_assoc',
			'array_intersect_key'     => 'array_intersect_key',
			'array_intersect_uassoc'  => 'array_intersect_uassoc',
			'array_intersect_ukey'    => 'array_intersect_ukey',
			'array_intersect'         => 'array_intersect',
			'array_key_first'         => 'array_key_first',
			'array_key_last'          => 'array_key_last',
			'array_keys'              => 'array_keys',
			'array_map'               => 'array_map',
			'array_merge_recursive'   => 'array_merge_recursive',
			'array_merge'             => 'array_merge',
			'array_pad'               => 'array_pad',
			'array_pop'               => 'array_pop',
			'array_product'           => 'array_product',
			'array_rand'              => 'array_rand',
			'array_reduce'            => 'array_reduce',
			'array_replace_recursive' => 'array_replace_recursive',
			'array_replace'           => 'array_replace',
			'array_reverse'           => 'array_reverse',
			'array_shift'             => 'array_shift',
			'array_slice'             => 'array_slice',
			'array_splice'            => 'array_splice',
			'array_sum'               => 'array_sum',
			'array_udiff_assoc'       => 'array_udiff_assoc',
			'array_udiff_uassoc'      => 'array_udiff_uassoc',
			'array_udiff'             => 'array_udiff',
			'array_uintersect_assoc'  => 'array_uintersect_assoc',
			'array_uintersect_uassoc' => 'array_uintersect_uassoc',
			'array_uintersect'        => 'array_uintersect',
			'array_unique'            => 'array_unique',
			'array_values'            => 'array_values',
			'count'                   => 'count',
			'current'                 => 'current',
			'end'                     => 'end',
			'key'                     => 'key',
			'next'                    => 'next',
			'prev'                    => 'prev',
			'range'                   => 'range',
			'reset'                   => 'reset',
			'implode'                 => 'implode',
			'ltrim'                   => 'ltrim',
			'rtrim'                   => 'rtrim',
			'md5'                     => 'md5',
			'str_getcsv'              => 'str_getcsv',
			'str_ireplace'            => 'str_ireplace',
			'str_pad'                 => 'str_pad',
			'str_repeat'              => 'str_repeat',
			'str_rot13'               => 'str_rot13',
			'str_shuffle'             => 'str_shuffle',
			'str_split'               => 'str_split',
			'str_word_count'          => 'str_word_count',
			'strcasecmp'              => 'strcasecmp',
			'strchr'                  => 'strchr',
			'strcmp'                  => 'strcmp',
			'strcoll'                 => 'strcoll',
			'strcspn'                 => 'strcspn',
			'strip_tags'              => 'strip_tags',
			'stripcslashes'           => 'stripcslashes',
			'stripos'                 => 'stripos',
			'stripslashes'            => 'stripslashes',
			'stristr'                 => 'stristr',
			'strlen'                  => 'strlen',
			'strnatcasecmp'           => 'strnatcasecmp',
			'strnatcmp'               => 'strnatcmp',
			'strncasecmp'             => 'strncasecmp',
			'strncmp'                 => 'strncmp',
			'strpbrk'                 => 'strpbrk',
			'strpos'                  => 'strpos',
			'strrchr'                 => 'strrchr',
			'strrev'                  => 'strrev',
			'strripos'                => 'strripos',
			'strrpos'                 => 'strrpos',
			'strspn'                  => 'strspn',
			'strstr'                  => 'strstr',
			'strtok'                  => 'strtok',
			'strtolower'              => 'strtolower',
			'strtoupper'              => 'strtoupper',
			'strtr'                   => 'strtr',
			'substr_compare'          => 'substr_compare',
			'substr_count'            => 'substr_count',
			'substr_replace'          => 'substr_replace',
			'substr'                  => 'substr',
			'trim'                    => 'trim',
			'ucfirst'                 => 'ucfirst',
			'ucwords'                 => 'ucwords',
			'vfprintf'                => 'vfprintf',
			'vprintf'                 => 'vprintf',
			'vsprintf'                => 'vsprintf',
			'wordwrap'                => 'wordwrap',
			'forget'                  => 'lolita\arr\forget',
			'flatten'                 => 'lolita\arr\flatten',
			'match_all'               => 'lolita\str\match_all',
			'prepend'                 => 'lolita\arr\prepend',
			'implode'                 => 'lolita\arr\implode',
			'pop'                     => 'lolita\arr\pop',
			'map'                     => 'lolita\arr\map',
			'only'                    => 'lolita\arr\only',
			'unshift'                 => 'lolita\arr\unshift',
			'explode'                 => 'lolita\str\explode',
			'basename'                => 'basename',
			'replace'                 => 'lolita\str\replace',
			'concat'                  => 'lolita\str\concat',
			'sort'                    => 'lolita\arr\sort',
			'get'                     => 'lolita\arr\get',
			'toArray'                 => 'lolita\arr\to',
			'head'                    => 'lolita\arr\head',
		);
	}

	/**
	 * Create myself.
	 *
	 * @param mixed $value Current.
	 *
	 * @return Chain
	 */
	public static function of( $value = null ) {
		return new self( $value );
	}
}

/**
 * Chain monad, useful for chaining certain array or string related functions.
 *
 * @param mixed $value Any data.
 *
 * @return Chain
 */
function chain( $value ) {
	return Chain::of( $value );
}
