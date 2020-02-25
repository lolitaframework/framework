<?php

namespace lolita\config\config;

use function lolita\chain;
use function lolita\functions\maybe_run;
use function lolita\loc\wp_file_system;

require_once 'constants.php';
require_once 'requires.php';
require_once 'actions.php';
require_once 'filters.php';
require_once 'pages.php';
require_once 'posttypes.php';
require_once 'rest.php';

/**
 * Launch main config entry.
 *
 * @param  mixed $app_path Application path.
 *
 * @return Chain
 */
function launch( $app_path ) {
	return chain( glob( $app_path . '/config/*.json' ) )
		->map( 'lolita\config\config\load' )
		->map( 'lolita\config\config\parse_json' )
		->map( 'lolita\config\config\determine_fn' )
		->map( 'lolita\config\config\get_priority' )
		->sort( 'lolita\config\config\sort' )
		->map( 'lolita\config\config\run_actions' );
}

/**
 * Load config file.
 *
 * @param  string $path Config path.
 *
 * @return array
 */
function load( $path ) {
	return array(
		'path'    => $path,
		'content' => wp_file_system()->get_contents( $path ),
	);
}

/**
 * Parse JSON
 *
 * @param  array $el Config item.
 *
 * @throws Exception JSON can be converted to Array: $filename.
 *
 * @return array
 */
function parse_json( $el ) {
	$decoded = json_decode( $el['content'], true );
	if ( null === $decoded || false === $decoded ) {
		throw new Exception( 'JSON can be converted to Array:' . $file_name, 1 );
	}
	$el['json'] = $decoded;
	return $el;
}

/**
 * Determine function name and priority function for each config.
 *
 * @param  array $el Config item.
 *
 * @return array
 */
function determine_fn( $el ) {
	$chain = chain( $el['path'] )
		->basename()
		->replace( '.json', '' )
		->concat( 'lolita\\config\\', '\\launch' );

	$el['fn']          = $chain->value();
	$el['priority_fn'] = $chain->concat( '', '_priority' )->value();
	return $el;
}

/**
 * Get priority.
 *
 * @param  array $el Config item.
 *
 * @return array
 */
function get_priority( $el ) {
	$el['priority'] = maybe_run( $el['priority_fn'] );
	return $el;
}

/**
 * Run config function.
 *
 * @param  array $el Config item.
 *
 * @return array
 */
function run_actions( $el ) {
	$el['result'] = $el['fn']( $el['json'] );
	return $el;
}

/**
 * Sort by priority;
 *
 * @param  mixed $prev Previous item.
 * @param  mixed $next Next item.
 *
 * @return int
 */
function sort( $prev, $next ) {
	return strnatcmp( $prev['priority'], $next['priority'] );
}
