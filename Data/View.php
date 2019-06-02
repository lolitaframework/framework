<?php

namespace LolitaFramework\Data;

use \LolitaFramework\Data\Arr;

/**
 * Class for working with views
 */
class View extends Arr {
  /**
   * Render view
   *
   * @author Guriev Eugen <gurievcreative@gmail.com>
   * @param type $path view path.
   * @param  array $data include data.
   * @return rendered html
   */
  public static function make( $path, $data = array() ) {
	  // If path setted like this "someview" then
	  // We need add default folder path and extension .php
	  if ( ! array_key_exists( 'extension', pathinfo( $path ) ) ) {
		  $path = $path . '.php';
	  }

	  extract($data);
	  ob_start();
	  require($path);

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
		if (true == WP_DEBUG) {
			return $buffer;
		}
		$search = array(
			'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
			'/[^\S ]+\</s',  // strip whitespaces before tags, except space
			'/(\s)+/s',      // shorten multiple whitespace sequences
		);

		$replace = array(
			'>',
			'<',
			'\\1',
		);

		return preg_replace( $search, $replace, $buffer );
	}
}