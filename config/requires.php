<?php

namespace lolita\config\requires;

use function lolita\chain;
use function lolita\functions\call;

/**
 * Launch requires config.
 *
 * @param  mixed $items Config items.
 *
 * @return Chain
 */
function launch( $items ) {
	return chain( $items )
		->map( 'lolita\str\interpret' )
		->map( 'trailingslashit' )
		->map( call( 'lolita\str\concat', array( 'suffix' => '*.php' ) ) )
		->map( 'glob' )
		->flatten()
		->map(
			function( $file ) {
				require_once $file;
				return $file;
			}
		);
}
