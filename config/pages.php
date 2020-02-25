<?php

namespace lolita\config\pages;

use function lolita\chain;
use function lolita\functions\call;
use function lolita\functions\maybe_run;
use function lolita\functions\iif;

/**
 * Launch actions config.
 *
 * @param  mixed $items Config items.
 *
 * @return Boolean
 */
function launch( $items ) {
	return add_action( 'admin_menu', pages_add( $items ) );
}

/**
 * Run chain logic for pages.
 *
 * @param  mixed $items Config items.
 *
 * @return Function
 */
function pages_add( $items ) {
	return function() use ( $items ) {
		return chain( $items )
			->map(
				function( $el ) {
					return array(
						'arr1'   => array(
							'icon_url' => '',
							'position' => null,
						),
						'arrays' => $el,
					);
				}
			)
			->map( call( 'array_merge' ) )
			->map(
				function( $el ) {
					$el['icon_url'] = maybe_run( $el['icon_url'] );
					return $el;
				}
			)
			->map(
				iif(
					'lolita\config\pages\pages_is_sub',
					call( 'add_submenu_page' ),
					call( 'add_menu_page' )
				)
			);
	};
}

/**
 * Is submenu.
 *
 * @param  mixed $el Config item.
 *
 * @return boolean
 */
function pages_is_sub( $el ) {
	return array_key_exists( 'parent_slug', $el );
}
