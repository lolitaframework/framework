<?php
namespace LolitaFramework\Configuration\Modules;

use \LolitaFramework\LF;
use \Exception;

/**
 * Taxonomies configuration module
 */
class Taxonomies {

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
				$data->map( array( $this, 'register_taxonomy' ) );
			}
		);
	}

	/**
	 * Register taxonomy
	 *
	 * @param  array $item input.
	 * @return mixed
	 */
	public function register_taxonomy( $item ) {
		return register_taxonomy( $item['slug'], $item['post_type_slug'], $item );
	}

	/**
	 * Set dafault data
	 *
	 * @param array $item Input.
	 */
	public static function defaults( $item ) {
		$plural         = $item['plural'];
		$singular       = $item['singular'];
		$post_type_slug = LF::get( $item, 'post_type_slug', 'post' );

		$labels = array(
			'name'              => $plural,
			'singular_name'     => $singular,
			'search_items'      => 'Search ' . $plural,
			'all_items'         => 'All ' . $plural,
			'parent_item'       => 'Parent ' . $singular,
			'parent_item_colon' => 'Parent ' . $singular . ' :',
			'edit_item'         => 'Edit ' . $singular,
			'update_item'       => 'Update ' . $singular,
			'add_new_item'      => 'Add New ' . $singular,
			'new_item_name'     => 'New ' . $singular . ' Name',
			'menu_name'         => $plural,
		);
		$defaults = array(
			'slug'              => sanitize_title( $singular ),
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $post_type_slug ),
			'post_type_slug'    => $post_type_slug,
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
			'plural',
			'singular',
		);
	}
}
