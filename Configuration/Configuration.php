<?php
namespace LolitaFramework\Configuration;

use \Exception;
use \LolitaFramework\Data\Path;
use \LolitaFramework\Data\Chain;
use \LolitaFramework\Data\Arr;
use \LolitaFramework\Data\Str;
use \LolitaFramework\Data\Loc;

/**
 * Configuration class
 */
class Configuration {
	/**
	 * Configuration file extension
	 */
	const CONFIG_EXTENSION = '.json';

	/**
	 * Loaded modules
	 *
	 * @var array
	 */
	public $modules = [];

	/**
	 * Class constructor
	 *
	 * @param LolitaFramework $lf Lolita Framework istance.
	 */
	public function __construct( $lf ) {
		$configs_path = Path::join(
			array(
				dirname( $lf->lf_folder ),
				'app',
				'config',
			)
		);
		$this->modules = Chain::of( $this->get_all_configuration_modules() )
			->map( 'pathinfo' )
			->map( array( &$this, 'add_class_name' ) )
			->map( array( &$this, 'add_priority' ) )
			->usort( array( &$this, 'sort_by_priority' ) )
			->map( $this->add_config_path( $configs_path ) )
			->where( array( &$this, 'is_config_exists' ) )
			->map( array( &$this, 'load_config' ) )
			->map( $this->init( $lf ) )
			->value();
	}

	/**
	 * Get all configuration modules
	 *
	 * @author Guriev Eugen <gurievcreative@gmail.com>
	 * @return array configuration modules.
	 */
	private function get_all_configuration_modules() {
		return (array) glob(
			Path::join(
				array(
					__DIR__,
					'Modules',
					'*.php',
				)
			)
		);
	}

	/**
	 * Add class name to array
	 *
	 * @param array $path_info array.
	 */
	public function add_class_name( $path_info ) {
		return Arr::set(
			$path_info,
			'class_name',
			Path::join(
				array(
					__NAMESPACE__,
					'Modules',
					$path_info['filename'],
				),
				NS
			)
		);
	}

	/**
	 * Add priority to path_info
	 *
	 * @param array $path_info with all info.
	 */
	public function add_priority( $path_info ) {
		$constant = $path_info['class_name'] . '::PRIORITY';
		return Arr::set(
			$path_info,
			'priority',
			defined( $constant ) ? constant( $constant ) : 100
		);
	}

	/**
	 * Sorting by priority key
	 *
	 * @param  array $a first element.
	 * @param  array $b second element.
	 * @return int
	 */
	public function sort_by_priority( $a, $b ) {
		if ( $a['priority'] > $b['priority'] ) {
			return 1;
		}
		return -1;
	}

	/**
	 * Add config path to path info
	 *
	 * @param string $base path.
	 */
	public function add_config_path( $base ) {
		return function( $path_info ) use ( $base ) {
			return Arr::set(
				$path_info,
				'config_path',
				Path::join(
					array(
						$base,
						Str::lower( $path_info['filename'] ) . self::CONFIG_EXTENSION,
					)
				)
			);
		};
	}

	/**
	 * Is module have config file
	 *
	 * @param  array $path_info array.
	 * @return boolean
	 */
	public function is_config_exists( $path_info ) {
		return is_file( $path_info['config_path'] );
	}

	/**
	 * Load config data
	 *
	 * @param  array $path_info path info.
	 * @return array
	 *
	 * @throws Exception JSON can be converted to Array:{ path }.
	 */
	public function load_config( $path_info ) {
		$data = json_decode( Loc::wp_file_system()->get_contents( $path_info['config_path'] ), true );
		if ( null === $data || false === $data ) {
			throw new Exception( 'JSON can be converted to Array:' . $path_info['config_path'], 1 );
		}
		return Arr::set(
			$path_info,
			'config_data',
			$data
		);
	}

	/**
	 * Init module
	 *
	 * @param  mixed $lf Lolita Framework instance.
	 * @return function
	 */
	public function init( $lf ) {
		return function( $path_info ) use ( $lf ) {
			$class_name = $path_info['class_name'];
			$config_data = $path_info['config_data'];
			return new $class_name( $lf, $config_data );
		};
	}
}
