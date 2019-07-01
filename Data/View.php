<?php

namespace LolitaFramework\Data;

use \LolitaFramework\Data\Arr;
use \LolitaFramework\Data\Path;

/**
 * Class for working with views
 */
class View extends Path {
	/**
	 * Render view
	 *
	 * @param array $data include data.
	 * @param type  $path view path.
	 * @return rendered html
	 */
	public static function render( $data = array(), $path ) {
		// If path setted like this "someview" then.
		// We need add default folder path and extension .php.
		if ( ! Path::is_have_extension( $path ) ) {
			$path = $path . '.php';
		}

		// phpcs:ignore
		extract( $data );
		ob_start();
		require( $path );

		// Return the compiled view and terminate the output buffer.
		return self::minimize( ltrim( ob_get_clean() ) );
	}

	/**
	 * Minimize before output
	 *
	 * @author Guriev Eugen <gurievcreative@gmail.com>
	 * @param  string $buffer to minimize.
	 * @return string minimized.
	 */
	public static function minimize( $buffer ) {
		if ( defined( 'WP_DEBUG' ) && true == WP_DEBUG ) {
			return $buffer;
		}
		$search = array(
			'/\>[^\S ]+/s',  // strip whitespaces after tags, except space.
			'/[^\S ]+\</s',  // strip whitespaces before tags, except space.
			'/(\s)+/s',      // shorten multiple whitespace sequences.
		);

		$replace = array(
			'>',
			'<',
			'\\1',
		);

		return preg_replace( $search, $replace, $buffer );
	}
}
