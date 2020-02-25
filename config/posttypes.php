<?php

namespace lolita\config\posttypes;

use function lolita\chain;
use function lolita\functions\call;

/**
 * Launch post types config.
 *
 * @param  mixed $items Config items.
 *
 * @return boolean
 */
function launch( $items ) {
	return add_action(
		'init',
		function() use ( $items ) {
			return chain( $items )
			->map( 'lolita\config\posttypes\pt_required' )
			->map( 'lolita\config\posttypes\pt_def' )
				->map( call( 'register_post_type' ) );
		}
	);
}

/**
 * Check by required keys.
 *
 * @param  mixed $el Config item.
 *
 * @throws Error This fields is required: %s!.
 *
 * @return array
 */
function pt_required( $el ) {
	$not_found = array_filter(
		array(
			'slug',
			'singular',
			'plural',
		),
		function( $key ) use ( $el ) {
			return ! array_key_exists( $key, $el );
		}
	);

	if ( count( $not_found ) ) {
		throw new Error(
			/* translators: %s - keys. */
			sprintf( __( 'This fields is required: %s!', 'beagl' ), implode( ', ', $not_found ) )
		);
	}

	return $el;
}

/**
 * Setup defaults for post type.
 *
 * @param array $el Config item.
 *
 * @return array
 */
function pt_def( $el ) {
	return array(
		'post_type' => $el['slug'],
		'args'      => array_merge(
			array(
				'label'         => ucfirst( $el['plural'] ),
				'labels'        => array(
					'name'               => __( 'Beagl', 'beagl' ) . ' ' . $el['plural'],
					'singular_name'      => $el['singular'],
					'add_new'            => __( 'Add New', 'beagl' ),
					'add_new_item'       => __( 'Add New', 'beagl' ) . ' ' . $el['singular'],
					'edit_item'          => __( 'Edit', 'beagl' ) . ' ' . $el['singular'],
					'new_item'           => __( 'New', 'beagl' ) . ' ' . $el['singular'],
					'all_items'          => __( 'All', 'beagl' ) . ' ' . $el['plural'],
					'view_item'          => __( 'View', 'beagl' ) . ' ' . $el['singular'],
					'search_items'       => __( 'Search', 'beagl' ) . ' ' . $el['singular'],                     /* translators: %s - singular. */
					'not_found'          => sprintf( __( 'No %s found', 'beagl' ), $el['singular'] ),            /* translators: %s - singular. */
					'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'beagl' ), $el['singular'] ),
					'parent_item_colon'  => '',
					'menu_name'          => __( 'Beagl', 'beagl' ) . ' ' . $el['plural'],
				),
				'description'   => '',
				'public'        => true,
				'menu_position' => 20,
				'has_archive'   => true,
				'slug'          => $el['slug'],
			),
			$el
		),
	);
}

