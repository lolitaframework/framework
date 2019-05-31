<?php
namespace Tests;

use LolitaFramework\LF;
use PHPUnit\Framework\TestCase;

/**
 * Testing Arr::*
 */
class ArrTest extends TestCase {

	/**
	 * Test append methods
	 */
	public function testAppend() {
		$this->assertEquals(
			array( 1, 2, 3, 4 ),
			LF::append( [ 1, 2, 3 ], 4 )
		);
	}

	/**
	 * Test compact methods
	 */
	public function testCompact() {
		$this->assertEquals(
			array( 1, 2, 3 ),
			LF::compact( array( 0, 1, false, 2, '', 3 ) )
		);
	}

	/**
	 * Test accessible methods
	 */
	public function testAccessible() {
		$this->assertEquals(
			true,
			LF::accessible( array() )
		);

		$this->assertEquals(
			false,
			LF::accessible( 0 )
		);
	}

	/**
	 * Test divide methods
	 */
	public function testDivide() {
		$this->assertEquals(
			array(
				array( 'a', 'b', 'c' ),
				array( '1', '2', '3' ),
			),
			LF::divide(
				array(
					'a' => 1,
					'b' => 2,
					'c' => 3,
				)
			)
		);
	}
}
