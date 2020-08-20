<?php
/**
 * @name HavingTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class HavingTest
 * @package Inc\Claz
 */
class HavingTest extends TestCase
{
    public function testHavingClass()
    {
        try {
            $having = new Having(true, 'db_field', '<>', 5, false, 'AND');

            static::assertEquals('db_field', $having->getField(), 'Invalid getField test');
            static::assertEquals('<>', $having->getOperator(), 'Invalid getOperator test');
            static::assertEquals(5, $having->getValue(), 'Invalid getValue test');
            static::assertEquals('AND', $having->getConnector(), 'Invalid getConnector test');
            static::assertTrue($having->isLeftParen(), 'Invalid isLeftParen test');
            static::assertFalse($having->isRightParen(), 'Invalid isRightParen test');

            $stmt = $having->build();
            static::assertEquals("(db_field <> '5' AND", $stmt);
        } catch (PdoDbException $pde) {
            static::assertTrue(false, "Unexpected error thrown by Having instantiation. Error: {$pde->getMessage()}");
        }
    }
}
