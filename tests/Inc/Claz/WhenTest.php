<?php
/**
 * @name WhenTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace test\Inc\Claz;

use Inc\Claz\When;
use PHPUnit\Framework\TestCase;

class WhenTest extends TestCase
{

    public function testWhenClass()
    {
        $wh = new When('db_field', '=', 5, 'FIVE');
        $stmt = trim($wh->build());
        $this->assertEquals("WHEN db_field = 5 THEN 'FIVE'", $stmt, 'When "=" class test failed');

        $wh = new When('db_field', '!=', 5, 'IS NOT FIVE');
        $stmt = trim($wh->build());
        $this->assertEquals("WHEN db_field != 5 THEN 'IS NOT FIVE'", $stmt, 'When "!=" class test failed');

        $wh = new When('db_field', '<>', 5, 'NOT EQUAL FIVE');
        $stmt = trim($wh->build());
        $this->assertEquals("WHEN db_field <> 5 THEN 'NOT EQUAL FIVE'", $stmt, 'When "<>" class test failed');

        $wh = new When('db_field', '<', 5, 'LESS THAN FIVE');
        $stmt = trim($wh->build());
        $this->assertEquals("WHEN db_field < 5 THEN 'LESS THAN FIVE'", $stmt, 'When "<" class test failed');

        $wh = new When('db_field', '<=', 5, 'LESS THAN OR EQUAL TO FIVE');
        $stmt = trim($wh->build());
        $this->assertEquals("WHEN db_field <= 5 THEN 'LESS THAN OR EQUAL TO FIVE'", $stmt, 'When "<=" class test failed');

        $wh = new When('db_field', '>', 5, 'GREATER THAN FIVE');
        $stmt = trim($wh->build());
        $this->assertEquals("WHEN db_field > 5 THEN 'GREATER THAN FIVE'", $stmt, 'When ">" class test failed');

        $wh = new When('db_field', '>=', 5, 'GREATER THAN OR EQUAL TO FIVE');
        $stmt = trim($wh->build());
        $this->assertEquals("WHEN db_field >= 5 THEN 'GREATER THAN OR EQUAL TO FIVE'", $stmt, 'When ">=" class test failed');

        $this->expectExceptionMessage("When - Invalid operator, ><, specified.");
        $wh = new When('db_field', '><', 5, 'BAD OPERATOR TEST');
    }
}
