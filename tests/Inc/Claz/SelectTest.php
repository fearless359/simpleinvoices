<?php
/**
 * @name SelectTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

if (!defined('TB_PREFIX')) {
    define('TB_PREFIX', "si_");
}

/**
 * Class SelectTest
 * @package Inc\Claz
 */
class SelectTest extends TestCase
{
    /**
     * @preserveGlobalState disabled
     * @runTestsInSeparateProcesses
     */
    public function testSelectClass()
    {
        $ls = [new DbField('ivl.customer_id'), new DbField('apl.domain_id')];
        $ls[] = new FunctionStmt("SUM", "COALESCE(apl.ac_amount, 0)", "inv_paid");
        $fr = new FromStmt("payment", 'apl');
        $wh = new WhereClause();
        try {
            $wh->addSimpleItem('prl.status', 1);
            $gr = new GroupBy(['ivl.customer_id', 'apl.domain_id']);
            $stmt = null;
            try {
                $se = new Select($ls, $fr, $wh, $gr, "ap");
                try {
                    $stmt = $se->build($keyPairs);
                } catch (PdoDbException $pde) {
                    self::assertTrue(false, "testOrderByClass() Unexpected error thrown for select object build. Error: {$pde->getMessage()}");
                }
            } catch (PdoDbException $pde) {
                self::assertTrue(false, "testOrderByClass() Unexpected error thrown for select object instantiation. Error: {$pde->getMessage()}");
            }
            self::assertEquals('(SELECT `ivl`.`customer_id`, `apl`.`domain_id`, (SUM(COALESCE(apl.ac_amount, 0))) AS inv_paid FROM `si_payment` `apl` WHERE `prl`.`status` = :prl_status_000  GROUP BY `ivl`.`customer_id`, `apl`.`domain_id`) AS ap', $stmt);
        } catch (PdoDbException $pde) {
            self::assertTrue(false, "testOrderByClass() Unexpected error thrown for addSimpleItem. Error: {$pde->getMessage()}");
        }

    }
}
