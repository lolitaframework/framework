<?php
namespace Tests;

use LolitaFramework\LF;
use PHPUnit\Framework\TestCase;

/**
 * Testing View::*
 */
class ViewTest extends TestCase {

  /**
   * Test append method
   */
  public function testMinimize() {
    $a = LF::minimize(
      "\n\n\n\n\n\n\nHello"
    );
    echo '<pre>';
    var_dump($a);
    echo '</pre>';
  }
}
