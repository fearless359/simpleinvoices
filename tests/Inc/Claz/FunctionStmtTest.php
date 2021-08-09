<?php
namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class FunctionStmtTest
 * @name FunctionStmtTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 * @package Inc\Claz
 */
class FunctionStmtTest extends TestCase
{

    public function testFunctionStmtClass()
    {
        $fn = new FunctionStmt('SUM', 'COALESCE(ii.total, 0)', 'inv_owing');
        try {
            $fn->addPart('-', 'COALESCE(ap.inv_paid, 0)');
        } catch (PdoDbException $pde) {
            self::assertTrue(false, "Unexpected error thrown by FunctionStmt addPart() method. Error {$pde->getMessage()}");
        }
        $stmt = $fn->build();
        self::assertEquals("((SUM(COALESCE(ii.total, 0))) - COALESCE(ap.inv_paid, 0)) AS inv_owing", $stmt, 'FunctionStmt class test failed');
    }
}
