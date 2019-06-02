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
			LF::append( array( 1, 2, 3 ), 4 )
		);
	}

	/**
	 * Test prepend method
	 */
	public function testPrepend() {
		$this->assertEquals(
			array( 4, 1, 2, 3 ),
			LF::prepend( array( 1, 2, 3 ), 4 )
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

	/**
	 * Test get method
	 */
	public function testGet() {
		$this->assertEquals(
			'TESTING',
			LF::get(
				array(
					'products' => array(
						'desk' => array( 'price' => 'TESTING' ),
					),
				),
				'products.desk.price'
			)
		);
	}

	/**
	 * Test has method
	 */
	public function testHas() {
		$this->assertEquals(
			true,
			LF::has(
				array(
					'products' => array( 'desk' => array( 'price' => 100 ) ),
				),
				'products.desk'
			)
		);

		$this->assertEquals(
			false,
			LF::has(
				array(
					array(
						'id' => 1,
						'name' => 'John Doe',
					),
					array(
						'id' => 2,
						'name' => 'John Doe',
					),
				),
				'2.name'
			)
		);
	}

	/**
	 * Test isAssoc method
	 */
	public function testIsAssoc() {
		$this->assertEquals(
			true,
			LF::is_assoc( array( 'key' => 'value' ) )
		);
		$this->assertEquals(
			false,
			LF::is_assoc( array( 1, 2, 3 ) )
		);
	}
}
