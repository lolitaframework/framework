<?php

namespace lolita\config\constants;

use function lolita\chain;
use function lolita\functions\maybe_run;

/**
 * Launch constants config.
 *
 * @param  array $items Config items.
 *
 * @return Chain
 */
function launch( $items ) {
	return chain( $items )
		->map(
			function( $el ) {
				if ( ! defined( $el['name'] ) ) {
					define( $el['name'], maybe_run( $el['value'] ) );
				}
				return $el;
			}
		);
}

/**
 * Get constants module priority.
 *
 * @return int
 */
function constants_priority() {
	return 99;
}
