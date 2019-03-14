<?php
/**
 * @name WhereClauseTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190313
 */

namespace test\Inc\Claz;

use Inc\Claz\WhereClause;
use Inc\Claz\WhereItem;
use PHPUnit\Framework\TestCase;

class WhereClauseTest extends TestCase
{

    public function test__construct()
    {
        $wc = new WhereClause();
        $this->assertEquals(0, $wc->getTokenCnt(), 'getTokenCnt result invalid');
        $this->assertEquals(0, $wc->getParenCnt(), 'getParenCnt result invalid');

        $wc = new WhereClause(new WhereItem(true, 'db_field', '=', 10, false, 'AND'));
        $this->assertEquals(1, $wc->getParenCnt(), 'getParenCnt result invalid');
    }

    public function testAddSimpleItem()
    {
        $wc = new WhereClause();
        $wc->addSimpleItem('db_field', 5);
        $stmt = trim($wc->build($keyPairs));
        $this->assertEquals('WHERE `db_field` = :db_field_000', $stmt, "Unexpected WhereClause found");
    }

    public function testAddItem()
    {
        $wc = new WhereClause(new WhereItem(true, 'db_field', '=', 10, false, 'AND'));
        $wc->addSimpleItem('db_field2', 5,"AND");
        $wc->addItem(new WhereItem(false, 'db_field3', '<>', 0, true));
        $stmt = trim($wc->build($keyPairs));
        $this->assertEquals('WHERE (`db_field` = :db_field_000  AND `db_field2` = :db_field2_001  AND `db_field3` <> :db_field3_002 )', $stmt, "Unexpected WhereClause found");
    }
}
