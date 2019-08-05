<?php
namespace LolitaFramework\Configuration\Modules;

use \LolitaFramework\Data\Chain;
use \LolitaFramework\LF;
use \Exception;

/**
 * MetaBoxes configuration module
 */
class MetaBoxes {

	/**
	 * Class constructor
	 *
	 * @param mixed $lf Lolita Framework instance.
	 * @param mixed $data config data.
	 */
	public function __construct( $lf, $data ) {
		add_action(
			'add_meta_boxes',
			function() use ( $data ) {
				$data->map( array( $this, 'register' ) );
			}
		);
	}

	/**
	 * Register taxonomy
	 *
	 * @param  array $item input.
	 * @return mixed
	 */
	public function register( $item ) {
		return add_meta_box(
			$item['id'],
			$item['title'],
			$item['callback'],
			$item['screen'],
			$item['context'],
			$item['priority'],
			$item['callback_args']
		);
	}

	/**
	 * Set dafault data
	 *
	 * @param array $item Input.
	 */
	public static function defaults( $item ) {
		return array_merge(
			array(
				'screen'        => array( 'post', 'page' ),
				'context'       => 'advanced',
				'priority'      => 'default',
				'callback_args' => null,
			),
			$item
		);
	}

	/**
	 * Get required arguments
	 *
	 * @return array
	 */
	public static function required() {
		return array(
			'id',
			'title',
			'callback',
		);
	}
}
