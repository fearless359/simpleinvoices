<?php
/**
 * @name FunctionStmtTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace test\Inc\Claz;

use Inc\Claz\FunctionStmt;
use PHPUnit\Framework\TestCase;

class FunctionStmtTest extends TestCase
{

    public function testFunctionStmtClass()
    {
        $fn = new FunctionStmt('SUM', 'COALESCE(ii.total, 0)', 'inv_owing');
        $fn->addPart('-', 'COALESCE(ap.inv_paid, 0)');
        $stmt = $fn->build();
        $this->assertEquals("((SUM(COALESCE(ii.total, 0))) - COALESCE(ap.inv_paid, 0)) AS inv_owing", $stmt, 'FunctionStmt class test failed');
    }
}
