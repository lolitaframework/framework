<?php
namespace LolitaFramework\Configuration\Modules;

use \LolitaFramework\Data\Chain;
use \LolitaFramework\LF;
use \Exception;

/**
 * Ajax configuration module
 */
class Ajax {

	/**
	 * Class constructor
	 *
	 * @param mixed $lf Lolita Framework instance.
	 * @param mixed $data config data.
	 */
	public function __construct( $lf, $data ) {
		$data->map( array( $this, 'add' ) );
	}

	/**
	 * Add ajax action
	 *
	 * @param array $el input.
	 */
	public function add( $el ) {
		add_action( 'wp_ajax_' . $el['action'], $el['cb'] );
	}

	/**
	 * Get required arguments
	 *
	 * @return array
	 */
	public static function required() {
		return array(
			'action',
			'cb',
		);
	}
}
