<?php
namespace Tests;

use LolitaFramework\LF;
use PHPUnit\Framework\TestCase;

/**
 * Testing Arr::*
 */
class ArrTest extends TestCase {

	/**
	 * Test append method
	 */
	public function testAppend() {
		$this->assertEquals(
			array( 1, 2, 3, 4 ),
			LF::append( [ 1, 2, 3 ], 4 )
		);
	}

	/**
	 * Test compact method
	 */
	public function testCompact() {
		$this->assertEquals(
			array( 1, 2, 3 ),
			LF::compact( array( 0, 1, false, 2, '', 3 ) )
		);
	}

	/**
	 * Test accessible method
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
	 * Test divide method
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

	/**
	 * Test set method
	 */
	public function testSet() {
		$this->assertEquals(
			array(
				'products' => array(
					'desk' => array( 'price' => 'TESTING' ),
				),
			),
			LF::set(
				array(
					'products' => array(
						'desk' => array( 'price' => 100 ),
					),
				),
				'products.desk.price',
				'TESTING'
			)
		);
	}
}
