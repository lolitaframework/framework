<?php

namespace lolita\config\actions;

use function lolita\chain;
use function lolita\functions\call;

/**
 * Launch actions config.
 *
 * @param  array $items Config items.
 *
 * @return Chain
 */
function launch( $items ) {
	return filters_and_actions( $items )
		->map( call( 'add_action' ) );
}

/**
 * Filters and actions base chain.
 *
 * @param  array $items Config items.
 *
 * @return Chain
 */
function filters_and_actions( $items ) {
	return chain( $items )
		->map(
			function( $el ) {
				return array(
					'arr1'   => array(
						'priority'      => 10,
						'accepted_args' => 1,
					),
					'arrays' => $el,
				);
			}
		)
		->map( call( 'array_merge' ) );
}
