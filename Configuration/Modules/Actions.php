<?php
namespace LolitaFramework\Configuration\Modules;

use \LolitaFramework\Data\Chain;
use \LolitaFramework\Data\Arr;

/**
 * Actions configuration module
 */
class Actions {

	/**
	 * Class constructor
	 *
	 * @param mixed $lf Lolita Framework instance.
	 * @param mixed $data config data.
	 */
	public function __construct( $lf, $data = array() ) {
		Chain::of( $data )
			->array_keys()
			->array_reduce( $this->prepare( $data ), array() )
			->map( array( &$this, 'add' ) )
			->value();
	}

	/**
	 * Add action
	 *
	 * @param array $el action data.
	 */
	public function add( $el ) {
		list( $tag, $function_to_add, $priority, $accepted_args ) = $el;
		add_action( $tag, $function_to_add, $priority, $accepted_args );
		return $el;
	}

	/**
	 * Prepare action data
	 *
	 * @param  array $data all actions.
	 * @return function
	 */
	public function prepare( $data ) {
		return function( $accumulator, $current ) use ( $data ) {
			return Arr::array_merge(
				$accumulator,
				Chain::of( (array) $data[ $current ] )
					->map( $this->add_default_priority_args( $current ) )
					->value()
			);
		};
	}

	/**
	 * Add default priority and args
	 *
	 * @param string $tag name.
	 */
	public function add_default_priority_args( $tag ) {
		return function( $el ) use ( $tag ) {
			return array(
				$tag,
				Arr::get( $el, 0, '' ),
				Arr::get( $el, 1, 10 ),
				Arr::get( $el, 2, 1 ),
			);
		};
	}
}
