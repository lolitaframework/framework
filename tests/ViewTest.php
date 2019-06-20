<?php
namespace Tests;

use LolitaFramework\Data\View;
use LolitaFramework\Data\Path;
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
				array( 'title' => 'Hello it is test!' ),
				Path::join( array( __DIR__, 'views', 'view-make.php' ) )
			)
		);
	}
}
