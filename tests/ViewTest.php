<?php
namespace Tests;

use LolitaFramework\Data\View;
use PHPUnit\Framework\TestCase;

/**
 * Testing View::*
 */
class ViewTest extends TestCase {

  /**
   * Test minimize method
   */
  public function testMinimize() {
    $this->assertEquals(
      "1\nHello",
      View::minimize(
        "1\n\n\n\n\n\n\nHello"
      )
    );
  }

  /**
   * Test render method
   */
  public function testRender() {
    $this->assertEquals(
      '<h1>Hello it is test!</h1>',
      View::render(
        __DIR__ . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'view-make.php',
        array(
          'title' => 'Hello it is test!'
        )
      )
    );
  }
}
