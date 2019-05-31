<?php
namespace Tests;

use LolitaFramework\LF;
use PHPUnit\Framework\TestCase;

/**
 * Testing Arr::*
 */
class ArrTest extends TestCase {

	/**
	 * Test append function
	 */
	public function testAppend() {
		$this->assertEquals(
			[ 1, 2, 3, 4 ],
			LF::append( [ 1, 2, 3 ], 4 )
		);
	}

	/**
	 * Test compact function
	 */
	public function testCompact() {
		$this->assertEquals(
			[1, 2, 3],
			LF::compact([0, 1, false, 2, '', 3])
		);
	}
}
