<?php
/**
 * @name SelectTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace test\Inc\Claz;

use Inc\Claz\DbField;
use Inc\Claz\FunctionStmt;
use Inc\Claz\FromStmt;
use Inc\Claz\GroupBy;
use Inc\Claz\Select;
use Inc\Claz\WhereClause;
use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{

    public function testSelectClass()
    {
        $ls = array(new DbField('ivl.customer_id'), new DbField('apl.domain_id'));
        $ls[] = new FunctionStmt("SUM", "COALESCE(apl.ac_amount, 0)", "inv_paid");
        $fr = new FromStmt("payment", 'apl');
        $wh = new WhereClause();
        $wh->addSimpleItem('prl.status', ENABLED);
        $gr = new GroupBy(array('ivl.customer_id', 'apl.domain_id'));
        $se = new Select($ls, $fr, $wh, $gr, "ap");
        $stmt = $se->build($keyPairs);
        $this->assertEquals('(SELECT `ivl`.`customer_id`, `apl`.`domain_id`, (SUM(COALESCE(apl.ac_amount, 0))) AS inv_paid FROM `si_payment` `apl` WHERE `prl`.`status` = :prl_status_000  GROUP BY `ivl`.`customer_id`, `apl`.`domain_id`) AS ap', $stmt);
    }
}
