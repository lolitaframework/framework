<?php

namespace lolita\config\rest;

use function lolita\chain;
use function lolita\functions\call;

/**
 * Launch rest config.
 *
 * @param  mixed $items Config items.
 *
 * @return boolean
 */
function launch( $items ) {
	return add_action(
		'rest_api_init',
		function() use ( $items ) {
			return chain( $items )
				->map(
					function( $el ) {
						return array(
							'arr1'   => array(
								'override' => false,
							),
							'arrays' => $el,
						);
					}
				)
				->map( call( 'array_merge' ) )
				->map( call( 'register_rest_route' ) );
		}
	);
}

