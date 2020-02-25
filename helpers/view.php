<?php

namespace lolita\view;

/**
 * Render view.
 *
 * @param array $data Include data.
 * @param type  $path View path.
 *
 * @return rendered html
 */
function render( $data = array(), $path ) {
	if ( ! array_key_exists( 'extension', pathinfo( $path ) ) ) {
		$path = $path . '.php';
	}

	// phpcs:ignore
	extract( $data );
	ob_start();
	// phpcs:ignore
	require( $path );

	// Return the compiled view and terminate the output buffer.
	return ltrim( ob_get_clean() );
}

