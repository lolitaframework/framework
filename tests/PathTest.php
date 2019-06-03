<?php
namespace Tests;

use LolitaFramework\Data\Path;
use PHPUnit\Framework\TestCase;

/**
 * Testing Path::*
 */
class PathTest extends TestCase {
  /**
   * Test join method
   */
  public function testHas() {
    $this->assertEquals(
      'folder/folder',
      Path::join( array( 'folder', 'folder' ), '/' )
    );
  }
}