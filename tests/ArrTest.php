<?php
namespace Tests;

use LolitaFramework\Data\Arr;
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
			Arr::append( array( 1, 2, 3 ), 4 )
		);
	}

	/**
	 * Test prepend method
	 */
	public function testPrepend() {
		$this->assertEquals(
			array( 4, 1, 2, 3 ),
			Arr::prepend( array( 1, 2, 3 ), 4 )
		);
	}

	/**
	 * Test compact method
	 */
	public function testCompact() {
		$this->assertEquals(
			array( 1, 2, 3 ),
			Arr::compact( array( 0, 1, false, 2, '', 3 ) )
		);
	}

	/**
	 * Test accessible method
	 */
	public function testAccessible() {
		$this->assertEquals(
			true,
			Arr::accessible( array() )
		);

		$this->assertEquals(
			false,
			Arr::accessible( 0 )
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
			Arr::divide(
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
			Arr::set(
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
			Arr::get(
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
			Arr::has(
				array(
					'products' => array( 'desk' => array( 'price' => 100 ) ),
				),
				'products.desk'
			)
		);

		$this->assertEquals(
			false,
			Arr::has(
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
			Arr::is_assoc( array( 'key' => 'value' ) )
		);
		$this->assertEquals(
			false,
			Arr::is_assoc( array( 1, 2, 3 ) )
		);
	}

	/**
	 * Test only method
	 */
	public function testOnly() {
		$this->assertEquals(
			array(
				'name' => 'Desk',
				'price' => 100,
			),
			Arr::only(
				array(
					'name' => 'Desk',
					'price' => 100,
					'orders' => 10,
				),
				array( 'name', 'price' )
			)
		);
	}

	/**
	 * Test pluck method
	 */
	public function testPluck() {
		$this->assertEquals(
			array( 'Taylor', 'Abigail' ),
			Arr::pluck(
				array(
					array(
						'developer' => array(
							'id' => 1,
							'name' => 'Taylor',
						),
					),
					array(
						'developer' => array(
							'id' => 2,
							'name' => 'Abigail',
						),
					),
				),
				'developer.name'
			)
		);
	}

	/**
	 * Test forget method
	 */
	public function testForget() {
		$this->assertEquals(
			array( 'products' => array() ),
			Arr::forget(
				array( 'products' => array( 'desk' => array( 'price' => 100 ) ) ),
				'products.desk'
			)
		);

		$this->assertEquals(
			array(
				array( 'name' => 'John Doe' ),
				array(
					'id' => 2,
					'name' => 'Jane Doe',
				),
			),
			Arr::forget(
				array(
					array(
						'id' => 1,
						'name' => 'John Doe',
					),
					array(
						'id' => 2,
						'name' => 'Jane Doe',
					),
				),
				'0.id'
			)
		);
	}

	/**
	 * Test where method
	 */
	public function testWhere() {
		$this->assertEquals(
			array(
				0 => 100,
				2 => 200,
				4 => 300,
			),
			Arr::where(
				array( 100, '100', 200, '200', 300 ),
				function( $value ) {
					return ! is_string( $value );
				}
			)
		);
	}
}
