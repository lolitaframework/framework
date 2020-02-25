<?php

namespace lolita\loc;

/**
 * Get wp_filesystem
 *
 * @author Guriev Eugen <gurievcreative@gmail.com>
 * @return Object
 */
function wp_file_system() {
	global $wp_filesystem;
	if ( empty( $wp_filesystem ) ) {
		include_once ABSPATH . '/wp-admin/includes/class-wp-filesystem-base.php';
		include_once ABSPATH . '/wp-admin/includes/class-wp-filesystem-direct.php';
	}
	return new \WP_Filesystem_Direct( null );
}


/**
 * Get wpdb object.
 *
 * @return wpdb
 */
function wpdb() {
	global $wpdb;
	return $wpdb;
}

/**
 * Location meta boxes.
 *
 * @return array
 */
function meta_boxes() {
	global $wp_meta_boxes;
	return $wp_meta_boxes;
}

/**
 * Handle upload without reference.
 *
 * @param string[]       $file      Reference to a single element of `$_FILES`. Call the function once for each uploaded file.
 * @param string[]|false $overrides An associative array of names => values to override default variables. Default false.
 * @param string         $time      Time formatted in 'yyyy/mm'.
 * @param string         $action    Expected value for `$_POST['action']`.
 * @return string[] On success, returns an associative array of file attributes. On failure, returns
 *               `$overrides['upload_error_handler'](&$file, $message )` or `array( 'error'=>$message )`.
 */
function handle_upload( $file, $overrides, $time, $action ) {
	// Include necessary code from core.
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	return _wp_handle_upload( $file, $overrides, $time, $action );
}
