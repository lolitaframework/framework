<?php

namespace LolitaFramework\Data;

use \LolitaFramework\Data\View;

/**
 * Class for working with functions
 */
abstract class Fn extends View {

	/**
	 * Run function and get value or return
	 *
	 * @param  mixed $fn function or value.
	 * @return mixed
	 */
	public static function run_or_return( $fn ) {
		if ( is_callable( $fn ) ) {
			return $fn();
		}
		return $fn;
	}
}
