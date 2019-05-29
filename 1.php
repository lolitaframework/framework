<?php
// ==============================================================
// Bootstraping Lolita Framework 2.0
// ==============================================================
if (! class_exists('\LolitaFramework\lf')) {
    require_once 'LF.php';
}
use \LolitaFramework\LF;

LF::getInstance();
// LF::hello();
// LF::fuck();

var_dump(LF::append([1, 2, 3, 4, 5], 6));
