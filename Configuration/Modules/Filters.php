<?php
namespace LolitaFramework\Configuration\Modules;

use \LolitaFramework\Configuration\Modules\Actions;

/**
 * Filter module
 */
class Filters extends Actions {

	/**
	 * Add filter
	 *
	 * @param array $el filter data.
	 */
	public function add( $el ) {
		list( $tag, $function_to_add, $priority, $accepted_args ) = $el;
		add_filter( $tag, $function_to_add, $priority, $accepted_args );
		return $el;
	}
}
