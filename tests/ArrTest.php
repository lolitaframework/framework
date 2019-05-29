<?php
namespace Tests;

use LolitaFramework\LF;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase {
    protected function setUp(): void {
        LF::getInstance();
    }

    public function testAppend() {
        // Arrange
        $a = [1, 2, 3];
        // Act
        $x = LF::append($a, 4);
        // Assert
        $this->assertEquals([1, 2, 3, 4], $x);
    }
}