<?php
namespace LolitaFramework\Configuration\Modules;

use \LolitaFramework\Data\Chain;
use \LolitaFramework\Data\Arr;

/**
 * Pages configuration module
 */
class Pages {

	/**
	 * Class constructor
	 *
	 * @param mixed $lf Lolita Framework instance.
	 * @param mixed $data config data.
	 */
	public function __construct( $lf, $data = array() ) {
		add_action( 'admin_menu', $this->add_pages( $data ) );
	}

	/**
	 * Add pages
	 *
	 * @param array $data all data.
	 */
	public function add_pages( $data ) {
		return function() use ( $data ) {
			$a = Chain::of( $data )
				->iif(
					array( &$this, 'is_sub_menu' ),
					array( &$this, 'add_sub_menu' ),
					array( &$this, 'add_menu' )
				)
				->value();
		};
	}

	/**
	 * Is sub menu?
	 *
	 * @param  array $el configuration element.
	 * @return boolean
	 */
	public function is_sub_menu( $el ) {
		return Arr::exists( $el, 'parent_slug' );
	}

	/**
	 * Add sub menu
	 *
	 * @param array $el configuration.
	 */
	public function add_sub_menu( $el ) {
		add_submenu_page(
			$el['parent_slug'],
			$el['page_title'],
			$el['menu_title'],
			$el['capability'],
			$el['menu_slug'],
			$el['function']
		);
		return $el;
	}

	/**
	 * Add menu
	 *
	 * @param array $el configuration.
	 */
	public function add_menu( $el ) {
		add_menu_page(
			$el['page_title'],
			$el['menu_title'],
			$el['capability'],
			$el['menu_slug'],
			Arr::get( $el, 'function' ),
			Arr::get( $el, 'icon_url' ),
			Arr::get( $el, 'position', null )
		);
		return $el;
	}
}
