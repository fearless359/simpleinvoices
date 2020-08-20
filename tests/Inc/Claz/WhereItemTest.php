<?php
/**
 * @name WhereItemTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190312
 */

namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class WhereItemTest
 * @package Inc\Claz
 */
class WhereItemTest extends TestCase
{

    public function testEndOfClause()
    {
        $wi = new WhereItem(true, 'db_field', '=', '1', false, 'AND');
        static::assertFalse($wi->endOfClause(), 'Test for false endOfClause');

        $wi = new WhereItem(false, 'db_field', '=', '1', true);
        static::assertTrue($wi->endOfClause(), 'Test for true endOfClause');
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function testConstructAndBuild()
    {
        static::expectExceptionMessage('WhereItem - Invalid connector, 1, specified. Must be "AND" or "OR".');
        new WhereItem(false, 'db_field', '=', 'b', false, 1);

        static::expectExceptionMessage("WhereItem - Invalid operator, BAD, specified.");
        new WhereItem(false, 'db_field', 'BAD', 'b', false, 1);

        static::expectExceptionMessage("WhereItem - Invalid value for BETWEEN operator. Must be an array of two elements.");
        new WhereItem(false, 'db_field', 'BETWEEN', 'b', false);

        static::expectExceptionMessage("WhereItem - Invalid value for IN operator. Must be an array.");
        new WhereItem(false, 'db_field', 'IN', 'b', false);

        static::expectExceptionMessage("WhereItem - Value must be blank for 'IS NULL' operator.");
        new WhereItem(false, 'db_field', 'IS NULL', 'b', false);

        static::expectExceptionMessage("WhereItem - Value must be blank for 'IS NOT NULL' operator.");
        new WhereItem(false, 'db_field', 'IS NOT NULL', 'b', false);
    }

    public function testParenCount()
    {
        $wi = new WhereItem(true, 'db_field', '=', '1', false, 'AND');
        static::assertEquals(1, $wi->parenCount());
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function testBuild()
    {
        // Test standard equality where item
        $cnt = 0;
        $keyPairs = [];
        $wi = new WhereItem(true, 'db_field', '=', '10', false, "AND");
        $stmt = trim($wi->build($cnt, $keyPairs));
        static::assertEquals("(`db_field` = :db_field_000  AND", $stmt, 'Test build for WhereItem "="');
        static::assertEquals(1, $cnt, 'Test build for WhereItem "=" cnt should be 1');
        static::assertEquals(10, $keyPairs[':db_field_000'], 'Test build for WhereItem "=" keyPairs should contain 10');

        // Test BETWEEN where item
        $cnt = 0;
        $keyPairs = [];
        $wi = new WhereItem(false, 'db_field', 'BETWEEN', [1, 10], true);
        $stmt = trim($wi->build($cnt, $keyPairs));
        static::assertEquals('`db_field` BETWEEN :db_field_000 AND :db_field_001)', $stmt, 'Test build for WhereItem "BETWEEN"');
        static::assertEquals(2, $cnt, 'Test build for WhereItem "BETWEEN" cnt should be 2');
        static::assertEquals(1, $keyPairs[':db_field_000'], 'Test build for WhereItem "BETWEEN" keyPairs should contain 1');
        static::assertEquals(10, $keyPairs[':db_field_001'], 'Test build for WhereItem "BETWEEN" keyPairs should contain 10');

        // Test IN where item
        $cnt = 0;
        $keyPairs = [];
        $wi = new WhereItem(false, 'db_field', 'IN', [1, 3, 7, 11], false);
        $stmt = trim($wi->build($cnt, $keyPairs));
        static::assertEquals('`db_field` IN (:db_field_000, :db_field_001, :db_field_002, :db_field_003)', $stmt, 'Test build for WhereItem "IN"');
        static::assertEquals(4, $cnt, 'Test build for WhereItem "IN" cnt should be 4');
        static::assertEquals(1, $keyPairs[':db_field_000'], 'Test build for WhereItem "IN" keyPairs should contain 1');
        static::assertEquals(3, $keyPairs[':db_field_001'], 'Test build for WhereItem "IN" keyPairs should contain 3');
        static::assertEquals(7, $keyPairs[':db_field_002'], 'Test build for WhereItem "IN" keyPairs should contain 7');
        static::assertEquals(11, $keyPairs[':db_field_003'], 'Test build for WhereItem "IN" keyPairs should contain 10');

        // Test IS where item
        $cnt = 0;
        $keyPairs = [];
        $wi = new WhereItem(false, 'db_field', 'IS NULL', '', false);
        trim($wi->build($cnt, $keyPairs));
        static::assertEquals(0, $cnt, 'Test build for WhereItem "IS NULL" cnt should be 0');

        // Test IS where item
        $cnt = 0;
        $keyPairs = [];
        $wi = new WhereItem(false, 'db_field', 'IS NOT NULL', '', false);
        trim($wi->build($cnt, $keyPairs));
        static::assertEquals(0, $cnt, 'Test build for WhereItem "IS NOT NULL" cnt should be 0');

        // Test REGEXP where item
        $cnt = 0;
        $keyPairs = [];
        $wi = new WhereItem(false, 'db_field', 'REGEXP', '^[a-z][a-z0-9]*', false);
        $stmt = trim($wi->build($cnt, $keyPairs));
        static::assertEquals('(`db_field` REGEXP :db_field_000 )', $stmt, 'Test build for WhereItem "REGEXP"');
        static::assertEquals(1, $cnt, 'Test build for WhereItem "REGEXP" cnt should be 1');
        static::assertEquals('^[a-z][a-z0-9]*', $keyPairs[':db_field_000'], "Test build for WhereItem \"REGEXP\" keyPairs should contain '^[a-z][a-z0-9]*'");
    }
}
