<?php
namespace LolitaFramework\Configuration\Modules;

/**
 * Widgets configuration module
 */
class Widgets {

	/**
	 * Class constructor
	 *
	 * @param mixed $lf Lolita Framework instance.
	 * @param mixed $data config data.
	 */
	public function __construct( $lf, $data = array() ) {
		add_action(
			'widgets_init',
			function() use ( $data ) {
				$data
					->map(
						function( $el ) {
							return current( array_values( $el ) );
						}
					)
					->map( 'register_widget' );
			}
		);
	}

	/**
	 * Get required arguments
	 *
	 * @return array
	 */
	public static function required() {
		return array(
			'widget_class',
		);
	}
}
