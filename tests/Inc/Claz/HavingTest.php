<?php
namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class HavingTest
 * @name HavingTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 * @package Inc\Claz
 */
class HavingTest extends TestCase
{
    public function testHavingClass()
    {
        $having = new Having(true, 'db_field', '<>', "foo", false, 'AND');

        self::assertEquals('db_field', $having->getField(), 'Invalid getField test');
        self::assertEquals('<>', $having->getOperator(), 'Invalid getOperator test');
        self::assertEquals("foo", $having->getValue(), 'Invalid getValue test');
        self::assertEquals('AND', $having->getConnector(), 'Invalid getConnector test');
        self::assertTrue($having->isLeftParen(), 'Invalid isLeftParen test');
        self::assertFalse($having->isRightParen(), 'Invalid isRightParen test');

        $stmt = $having->build();
        self::assertEquals("(db_field <> 'foo' AND", $stmt);
    }
}
