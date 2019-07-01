<?php
namespace LolitaFramework\Configuration\Modules;

use \LolitaFramework\Data\Chain;
use \LolitaFramework\Data\Arr;
use \LolitaFramework\Data\Path;

/**
 * Customs configuration module.
 */
class Customs {

	/**
	 * Module priority
	 */
	const PRIORITY = 99;

	/**
	 * Class constructor
	 *
	 * @param mixed $lf Lolita Framework instance.
	 * @param mixed $data config data.
	 */
	public function __construct( $lf, $data = array() ) {
		Chain::of( $data )
			->map( $this->prepare_patterns( dirname( $lf->lf_folder ) ) )
			->array_reduce( array( &$this, 'get_paths' ), array() )
			->map( array( &$this, 'load_classes' ) )
			->value();
	}

	/**
	 * Prepare folder patterns for glob function
	 *
	 * @param  string $base_folder base folder path.
	 * @return function
	 */
	public function prepare_patterns( $base_folder ) {
		return function( $folder ) use ( $base_folder ) {
			return Path::join(
				array(
					$base_folder,
					'app',
					$folder,
					'*.php',
				)
			);
		};
	}

	/**
	 * Get paths
	 *
	 * @param  array  $accumulator for paths.
	 * @param  string $path folder.
	 * @return array
	 */
	public function get_paths( $accumulator, $path ) {
		return Arr::array_merge( $accumulator, (array) glob( $path ) );
	}

	/**
	 * Require custom classes
	 *
	 * @param  string $path class path.
	 * @return string
	 */
	public function load_classes( $path ) {
		require_once $path;
		return $path;
	}
}
