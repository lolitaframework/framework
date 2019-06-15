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
	public function test_append() {
		$this->assertEquals(
			array( 1, 2, 3, 4 ),
			Arr::append( array( 1, 2, 3 ), 4 )
		);
	}

	/**
	 * Test prepend method
	 */
	public function test_prepend() {
		$this->assertEquals(
			array( 4, 1, 2, 3 ),
			Arr::prepend( array( 1, 2, 3 ), 4 )
		);
	}

	/**
	 * Test compact method
	 */
	public function test_compact() {
		$this->assertEquals(
			array( 1, 2, 3 ),
			Arr::compact( array( 0, 1, false, 2, '', 3 ) )
		);
	}

	/**
	 * Test accessible method
	 */
	public function test_accessible() {
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
	public function test_divide() {
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
	public function test_set() {
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
	public function test_get() {
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
	public function test_has() {
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
	public function test_is_assoc() {
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
	public function test_only() {
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
	public function test_pluck() {
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
	public function test_forget() {
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
	public function test_where() {
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

	/**
	 * Test map method
	 */
	public function test_map() {
		$this->assertEquals(
			array( 0, 0 ),
			Arr::map(
				array( 1, 1 ),
				function( $el ) {
					return 0;
				}
			)
		);
	}

	/**
	 * Test reduce method
	 */
	public function test_reduce() {
		$this->assertEquals(
			5,
			Arr::reduce(
				array( 1, 2, 2 ),
				function( $accumulator, $current ) {
					return $accumulator + $current;
				},
				0
			)
		);
	}

	/**
	 * Test change_key_case method
	 */
	public function test_change_key_case() {
		$this->assertEquals(
			array(
				'FIRST' => 1,
				'SECOND' => 4,
			),
			Arr::change_key_case(
				array(
					'First' => 1,
					'Second' => 4,
				),
				CASE_UPPER
			)
		);
	}

	/**
	 * Test combine method
	 */
	public function test_combine() {
		$this->assertEquals(
			array(
				'green'  => 'avocado',
				'red'    => 'apple',
				'yellow' => 'banana',
			),
			Arr::combine(
				array( 'green', 'red', 'yellow' ),
				array( 'avocado', 'apple', 'banana' )
			)
		);
	}

	/**
	 * Test count_values method
	 */
	public function test_count_values() {
		$this->assertEquals(
			array(
				1       => 2,
				'hello' => 2,
				'world' => 1,
			),
			Arr::count_values(
				array( 1, 'hello', 1, 'world', 'hello' )
			)
		);
	}

	/**
	 * Test diff_assoc method
	 */
	public function test_diff_assoc() {
		$this->assertEquals(
			array(
				'b' => 'brown',
				'c' => 'blue',
				'0' => 'red',
			),
			Arr::diff_assoc(
				array(
					'a' => 'green',
					'b' => 'brown',
					'c' => 'blue',
					'red',
				),
				array(
					'a' => 'green',
					'yellow',
					'red',
				)
			)
		);
	}

	/**
	 * Test diff_key method
	 */
	public function test_diff_key() {
		$this->assertEquals(
			array(
				'blue'   => 1,
				'red'    => 2,
				'purple' => 4,
			),
			Arr::diff_key(
				array(
					'blue'   => 1,
					'red'    => 2,
					'green'  => 3,
					'purple' => 4,
				),
				array(
					'green'  => 5,
					'yellow' => 7,
					'cyan'   => 8,
				)
			)
		);
	}

	/**
	 * Test diff method
	 */
	public function test_diff() {
		$this->assertEquals(
			array( 1 => 'blue' ),
			Arr::diff(
				array(
					'a' => 'green',
					'red',
					'blue',
					'red',
				),
				array(
					'b' => 'green',
					'yellow',
					'red',
				)
			)
		);
	}

	/**
	 * Test fill_keys method
	 */
	public function test_fill_keys() {
		$this->assertEquals(
			array(
				'foo' => 'banana',
				5     => 'banana',
				10    => 'banana',
				'bar' => 'banana',
			),
			Arr::fill_keys(
				array(
					'foo',
					5,
					10,
					'bar',
				),
				'banana'
			)
		);
	}

	/**
	 * Test fill method
	 */
	public function test_fill() {
		$this->assertEquals(
			array(
				'5'  => 'banana',
				'6'  => 'banana',
				'7'  => 'banana',
				'8'  => 'banana',
				'9'  => 'banana',
				'10' => 'banana',
			),
			Arr::fill( 5, 6, 'banana' )
		);
	}

	/**
	 * Test flip method
	 */
	public function test_flip() {
		$this->assertEquals(
			array(
				'oranges' => 0,
				'apples'  => 1,
				'pears'   => 2,
			),
			Arr::flip(
				array(
					'oranges',
					'apples',
					'pears',
				)
			)
		);
	}
}
