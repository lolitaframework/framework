<?php
namespace LolitaFramework\Configuration\Modules;

use \LolitaFramework\LF;
use \Exception;

/**
 * PostTypes configuration module
 */
class PostTypes {

	/**
	 * Class constructor
	 *
	 * @param mixed $lf Lolita Framework instance.
	 * @param mixed $data config data.
	 */
	public function __construct( $lf, $data ) {
		add_action(
			'init',
			function() use ( $data ) {
				$data->map( array( $this, 'register_post_type' ) );
			}
		);
	}

	/**
	 * Register post type
	 *
	 * @param  array $item input.
	 * @return mixed
	 */
	public function register_post_type( $item ) {
		return register_post_type( $item['slug'], $item );
	}

	/**
	 * Set dafault data
	 *
	 * @param array $item Input.
	 */
	public static function defaults( $item ) {
		$singular = $item['singular'];
		$plural = $item['plural'];

		$labels = array(
			'name'               => $plural,
			'singular_name'      => $singular,
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New ' . $singular,
			'edit_item'          => 'Edit ' . $singular,
			'new_item'           => 'New ' . $singular,
			'all_items'          => 'All ' . $plural,
			'view_item'          => 'View ' . $singular,
			'search_items'       => 'Search ' . $singular,
			'not_found'          => 'No ' . $singular . ' found',
			'not_found_in_trash' => 'No ' . $singular . ' found in Trash',
			'parent_item_colon'  => '',
			'menu_name'          => $plural,
		);
		$defaults = array(
			'label'         => $plural,
			'labels'        => $labels,
			'description'   => '',
			'public'        => true,
			'menu_position' => 20,
			'has_archive'   => true,
			'slug'          => sanitize_title( $singular ),
		);
		return array_merge( $defaults, $item );
	}

	/**
	 * Get required arguments
	 *
	 * @return array
	 */
	public static function required() {
		return array(
			'singular',
			'plural',
		);
	}
}
