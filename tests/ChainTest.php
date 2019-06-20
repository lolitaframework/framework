<?php
namespace Tests;

use LolitaFramework\Data\Chain;
use PHPUnit\Framework\TestCase;

/**
 * Testing Arr::*
 */
class ChainTest extends TestCase {
	/**
	 * Test value method
	 */
	public function testValue() {
		$this->assertEquals(
			1,
			Chain::of( 1 )->value()
		);
	}

	/**
	 * Test bind method
	 */
	public function testBind() {
		$this->assertEquals(
			4,
			Chain::of( 1 )
				->bind(
					function( $v ) {
						return $v + 2;
					}
				)
				->bind(
					function( $v ) {
						return $v + 1;
					}
				)
				->value()
		);
	}

	/**
	 * Test chaining
	 */
	public function testChaining() {
		$this->assertEquals(
			array( 0, 1, 2, 3 ),
			Chain::of( array( 1, 0, false, '', 2 ) )
				->compact()
				->append( 3 )
				->prepend( 0 )
				->value()
		);
	}
}
