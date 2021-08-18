<?php
namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class WhenTest
 * @name WhenTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 * @package Inc\Claz
 */
class WhenTest extends TestCase
{

    public function testWhenClass()
    {
        try {
            $wh = new When('db_field', '=', 5, 'FIVE');
            $stmt = trim($wh->build());
            self::assertEquals("WHEN db_field = 5 THEN 'FIVE'", $stmt, 'When "=" class test failed');
        } catch (PdoDbException $pde) {
            self::fail("WhenTest::testWhenClass() - Unexpected error thrown. Error: {$pde->getMessage()}");
        }

        try {
            $wh = new When('db_field', '!=', 5, 'IS NOT FIVE');
            $stmt = trim($wh->build());
            self::assertEquals("WHEN db_field != 5 THEN 'IS NOT FIVE'", $stmt, 'When "!=" class test failed');
        } catch (PdoDbException $pde) {
            self::fail("WhenTest::testWhenClass() - Unexpected error thrown. Error: {$pde->getMessage()}");
        }

        try {
            $wh = new When('db_field', '<>', 5, 'NOT EQUAL FIVE');
            $stmt = trim($wh->build());
            self::assertEquals("WHEN db_field <> 5 THEN 'NOT EQUAL FIVE'", $stmt, 'When "<>" class test failed');
        } catch (PdoDbException $pde) {
            self::fail("WhenTest::testWhenClass() - Unexpected error thrown. Error: {$pde->getMessage()}");
        }

        try {
            $wh = new When('db_field', '<', 5, 'LESS THAN FIVE');
            $stmt = trim($wh->build());
            self::assertEquals("WHEN db_field < 5 THEN 'LESS THAN FIVE'", $stmt, 'When "<" class test failed');
        } catch (PdoDbException $pde) {
            self::fail("WhenTest::testWhenClass() - Unexpected error thrown. Error: {$pde->getMessage()}");
        }

        try {
            $wh = new When('db_field', '<=', 5, 'LESS THAN OR EQUAL TO FIVE');
            $stmt = trim($wh->build());
            self::assertEquals("WHEN db_field <= 5 THEN 'LESS THAN OR EQUAL TO FIVE'", $stmt, 'When "<=" class test failed');
        } catch (PdoDbException $pde) {
            self::fail("WhenTest::testWhenClass() - Unexpected error thrown. Error: {$pde->getMessage()}");
        }

        try {
            $wh = new When('db_field', '>', 5, 'GREATER THAN FIVE');
            $stmt = trim($wh->build());
            self::assertEquals("WHEN db_field > 5 THEN 'GREATER THAN FIVE'", $stmt, 'When ">" class test failed');
        } catch (PdoDbException $pde) {
            self::fail("WhenTest::testWhenClass() - Unexpected error thrown. Error: {$pde->getMessage()}");
        }

        try {
            $wh = new When('db_field', '>=', 5, 'GREATER THAN OR EQUAL TO FIVE');
            $stmt = trim($wh->build());
            self::assertEquals("WHEN db_field >= 5 THEN 'GREATER THAN OR EQUAL TO FIVE'", $stmt, 'When ">=" class test failed');
        } catch (PdoDbException $pde) {
            self::fail("WhenTest::testWhenClass() - Unexpected error thrown. Error: {$pde->getMessage()}");
        }

        static::expectExceptionMessage("When - Invalid operator, ><, specified.");
        /** @noinspection PhpUnhandledExceptionInspection */
        new When('db_field', '><', 5, 'BAD OPERATOR TEST');
    }
}
