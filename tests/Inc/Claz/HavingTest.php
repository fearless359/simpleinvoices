<?php
/**
 * @name HavingTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace test\Inc\Claz;

use Inc\Claz\Having;
use PHPUnit\Framework\TestCase;

class HavingTest extends TestCase
{
    public function testHavingClass()
    {
        $having = new Having(true, 'db_field', '<>', 5, false, 'AND');

        $this->assertEquals('db_field', $having->getField(), 'Invalid getField test');
        $this->assertEquals('<>', $having->getOperator(), 'Invalid getOperator test');
        $this->assertEquals(5, $having->getValue(), 'Invalid getValue test');
        $this->assertEquals('AND', $having->getConnector(), 'Invalid getConnector test');
        $this->assertTrue($having->isLeftParen(), 'Invalid isLeftParen test');
        $this->assertFalse($having->isRightParen(), 'Invalid isRightParen test');

        $stmt = $having->build($keyPairs);
        $this->assertEquals("(db_field <> '5' AND", $stmt);
    }
}
