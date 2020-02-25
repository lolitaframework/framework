<?php

namespace lolita\config\filters;

use function lolita\functions\call;

/**
 * Launch filters config.
 *
 * @param  mixed $items Config items.
 *
 * @return Chain
 */
function launch( $items ) {
	return filters_and_actions( $items )
		->map( call( 'add_filter' ) );
}
