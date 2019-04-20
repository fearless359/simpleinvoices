<?php
/**
 * @name WhereItemTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190312
 */

namespace test\Inc\Claz;

use Inc\Claz\WhereItem;
use PHPUnit\Framework\TestCase;

class WhereItemTest extends TestCase
{

    public function testEndOfClause()
    {
        $wi = new WhereItem(true, 'db_field', '=', '1', false, 'AND');
        $this->assertFalse($wi->endOfClause(), 'Test for false endOfClause');

        $wi = new WhereItem(false, 'db_field', '=', '1', true);
        $this->assertTrue($wi->endOfClause(), 'Test for true endOfClause');
    }

    public function testConstructAndBuild()
    {
        $this->expectExceptionMessage('WhereItem - Invalid connector, 1, specified. Must be "AND" or "OR".');
        $wi = new WhereItem(false, 'db_field', '=', 'b', false, 1);

        $this->expectExceptionMessage("WhereItem - Invalid operator, BAD, specified.");
        $wi = new WhereItem(false, 'db_field', 'BAD', 'b', false, 1);

        $this->expectExceptionMessage("WhereItem - Invalid value for BETWEEN operator. Must be an array of two elements.");
        $wi = new WhereItem(false, 'db_field', 'BETWEEN', 'b', false);

        $this->expectExceptionMessage("WhereItem - Invalid value for IN operator. Must be an array.");
        $wi = new WhereItem(false, 'db_field', 'IN', 'b', false);

        $this->expectExceptionMessage("WhereItem - Value must be blank for 'IS NULL' operator.");
        $wi = new WhereItem(false, 'db_field', 'IS NULL', 'b', false);

        $this->expectExceptionMessage("WhereItem - Value must be blank for 'IS NOT NULL' operator.");
        $wi = new WhereItem(false, 'db_field', 'IS NOT NULL', 'b', false);
    }

    public function testParenCount()
    {
        $wi = new WhereItem(true, 'db_field', '=', '1', false, 'AND');
        $this->assertEquals(1, $wi->parenCount());
    }

    public function testBuild()
    {
        // Test standard equality where item
        $wi = new WhereItem(true, 'db_field', '=', '10', false, "AND");
        $stmt = trim($wi->build($cnt, $keyPairs));
        $this->assertEquals("(`db_field` = :db_field_000  AND", $stmt, 'Test build for WhereItem "="');
        $this->assertEquals(1, $cnt, 'Test build for WhereItem "=" cnt should be 1');
        $this->assertEquals(10, $keyPairs[':db_field_000'], 'Test build for WhereItem "=" keyPairs should contain 10');

        // Test BETWEEN where item
        $cnt = null;
        $keyPairs = null;
        $wi = new WhereItem(false, 'db_field', 'BETWEEN', array(1,10), true);
        $stmt = trim($wi->build($cnt, $keyPairs));
        $this->assertEquals('`db_field` BETWEEN :db_field_000 AND :db_field_001)', $stmt, 'Test build for WhereItem "BETWEEN"');
        $this->assertEquals(2, $cnt, 'Test build for WhereItem "BETWEEN" cnt should be 2');
        $this->assertEquals(1, $keyPairs[':db_field_000'], 'Test build for WhereItem "BETWEEN" keyPairs should contain 1');
        $this->assertEquals(10, $keyPairs[':db_field_001'], 'Test build for WhereItem "BETWEEN" keyPairs should contain 10');

        // Test IN where item
        $cnt = null;
        $keyPairs = null;
        $wi = new WhereItem(false, 'db_field', 'IN', array(1, 3, 7, 11), false);
        $stmt = trim($wi->build($cnt, $keyPairs));
        $this->assertEquals('`db_field` IN (:db_field_000, :db_field_001, :db_field_002, :db_field_003)', $stmt, 'Test build for WhereItem "IN"');
        $this->assertEquals(4, $cnt, 'Test build for WhereItem "IN" cnt should be 4');
        $this->assertEquals(1, $keyPairs[':db_field_000'], 'Test build for WhereItem "IN" keyPairs should contain 1');
        $this->assertEquals(3, $keyPairs[':db_field_001'], 'Test build for WhereItem "IN" keyPairs should contain 3');
        $this->assertEquals(7, $keyPairs[':db_field_002'], 'Test build for WhereItem "IN" keyPairs should contain 7');
        $this->assertEquals(11, $keyPairs[':db_field_003'], 'Test build for WhereItem "IN" keyPairs should contain 10');

        // Test IS where item
        $cnt = null;
        $keyPairs = null;
        $wi = new WhereItem(false, 'db_field', 'IS NULL', '', false);
        $stmt = trim($wi->build($cnt, $keyPairs));
        $this->assertEquals(0, $cnt, 'Test build for WhereItem "IS NULL" cnt should be 0');

        // Test IS where item
        $cnt = null;
        $keyPairs = null;
        $wi = new WhereItem(false, 'db_field', 'IS NOT NULL', '', false);
        $stmt = trim($wi->build($cnt, $keyPairs));
        $this->assertEquals(0, $cnt, 'Test build for WhereItem "IS NOT NULL" cnt should be 0');

        // Test REGEXP where item
        $cnt = null;
        $keyPairs = null;
        $wi = new WhereItem(false, 'db_field', 'REGEXP', '^[a-z][a-z0-9]*', false);
        $stmt = trim($wi->build($cnt, $keyPairs));
        $this->assertEquals('(`db_field` REGEXP :db_field_000 )', $stmt, 'Test build for WhereItem "REGEXP"');
        $this->assertEquals(1, $cnt, 'Test build for WhereItem "REGEXP" cnt should be 1');
        $this->assertEquals('^[a-z][a-z0-9]*', $keyPairs[':db_field_000'], "Test build for WhereItem \"REGEXP\" keyPairs should contain '^[a-z][a-z0-9]*'");
    }
}
