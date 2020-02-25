<?php

namespace lolita\fl;

use \Exception;

/**
 * Convert bytes to human readable string.
 *
 * @param  mixed $bytes Bytes to convert to a readable format.
 * @param  mixed $dec Decimals to show.
 *
 * @return string
 */
function human_size( $bytes, $dec = 2 ) {
	$size   = array( 'B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
	$factor = floor( ( strlen( $bytes ) - 1 ) / 3 );

	return sprintf( "%.{$dec}f", $bytes / pow( 1024, $factor ) ) . $size[ $factor ];
}

/**
 * Make dir with index.html.
 *
 * @param  mixed $path Path to directory.
 *
 * @return string
 */
function mkdir( $path ) {
	if ( ! file_exists( $path ) || ! wp_is_writable( $path ) ) {
		wp_mkdir_p( $path );
	}

	$index = trailingslashit( $path ) . 'index.html';
	if ( ! file_exists( $index ) ) {
		file_put_contents( $index, '' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}
	return $path;
}

/**
 * Move file to a permanent location.
 *
 * @throws Exception Upload Error, could not upload file: {from} | {to}.
 *
 * @param string $path_from From.
 * @param string $path_to   To.
 *
 * @return false|string False on error.
 */
function move_file( $path_from, $path_to ) {
	if ( false === move_uploaded_file( $path_from, $path_to ) ) {
		throw new Exception( 'Upload Error, could not upload file: ' . $path_from . ' | ' . $path_to );
	}
	return $path_to;
}

/**
 * Deletes a file
 *
 * @param  string $file File to delete.
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function unlink( $file ) {
	return \unlink( $file ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
}

/**
 * Set correct file permissions in the file system.
 *
 * @throws Exception Upload Error, could not upload file: {from} | {to}.
 *
 * @param string $path File to set permissions for.
 */
function set_file_permissions( $path ) {

	// Set correct file permissions.
	$stat = stat( dirname( $path ) );

	@chmod( $path, $stat['mode'] & 0000666 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	return $path;
}
