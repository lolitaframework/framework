<?php

require_once 'helpers/arr.php';
require_once 'helpers/str.php';
require_once 'helpers/loc.php';
require_once 'helpers/view.php';
require_once 'helpers/class-chain.php';
require_once 'helpers/class-placeholder.php';
require_once 'config/config.php';
require_once 'helpers/fn.php';
require_once 'helpers/fl.php';

use function lolita\config\config\launch;

/**
 * Launch lolita framework.
 *
 * @param  string $app_path Application path.
 *
 * @return void
 */
function lolita( $app_path ) {
	launch( $app_path );
}

