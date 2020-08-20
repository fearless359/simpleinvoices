<?php
/**
 * @name WhereClauseTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190313
 */

namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class WhereClauseTest
 * @package Inc\Claz
 */
class WhereClauseTest extends TestCase
{

    public function testConstruct()
    {
        $wc = new WhereClause(); // Note no error can be thrown when no parameters
        static::assertEquals(0, $wc->getTokenCnt(), 'getTokenCnt result invalid');
        static::assertEquals(0, $wc->getParenCnt(), 'getParenCnt result invalid');

        try {
            $wc = new WhereClause(new WhereItem(true, 'db_field', '=', 10, false, 'AND'));
            static::assertEquals(1, $wc->getParenCnt(), 'getParenCnt result invalid');
        } catch (PdoDbException $pde) {
            static::assertTrue(false, "WhereClauseTest::testConstruct() - Unexpected exception thrown. Error: {$pde->getMessage()}");
        }
    }

    public function testAddSimpleItem()
    {
        try {
            $wc = new WhereClause();
            $wc->addSimpleItem('db_field', 5);
            $stmt = trim($wc->build($keyPairs));
            static::assertEquals('WHERE `db_field` = :db_field_000', $stmt, "Unexpected WhereClause found");
        } catch (PdoDbException $pde) {
            static::assertTrue(false, "WhereClauseTest::testAddSimpleItem() - Unexpected exception thrown. Error: {$pde->getMessage()}");
        }
    }

    public function testAddItem()
    {
        try {
            $wc = new WhereClause(new WhereItem(true, 'db_field', '=', 10, false, 'AND'));
            $wc->addSimpleItem('db_field2', 5, "AND");
            $wc->addItem(new WhereItem(false, 'db_field3', '<>', 0, true));
            $stmt = trim($wc->build($keyPairs));
            static::assertEquals('WHERE (`db_field` = :db_field_000  AND `db_field2` = :db_field2_001  AND `db_field3` <> :db_field3_002 )', $stmt, "Unexpected WhereClause found");
        } catch (PdoDbException $pde) {
            static::assertTrue(false, "WhereClauseTest::testAddItem() - Unexpected exception thrown. Error: {$pde->getMessage()}");
        }
    }
}
