<?php
/**
 * @name JoinTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace test\Inc\Claz;

use Inc\Claz\DbField;
use Inc\Claz\GroupBy;
use Inc\Claz\Join;
use Inc\Claz\OnClause;
use PHPUnit\Framework\TestCase;

class JoinTest extends TestCase
{

    public function testJoinClass()
    {
        $onClause = new OnClause();
        $onClause->addSimpleItem("t.tax_id", "et.tax_id", "AND");
        $onClause->addSimpleItem("t.domain_id", "e.domain_id");
        $jn = new Join('LEFT', 'preferences', 'pref');
        $jn->setOnClause($onClause);
        $jn->addGroupBy(new GroupBy(array('t.tax_id', 't.domain_id')));
        $stmt = $jn->build($keyPairs);
        $this->assertEquals( "LEFT JOIN `si_preferences` AS pref ON (`t`.`tax_id` = :t_tax_id_000  AND `t`.`domain_id` = :t_domain_id_001 ) GROUP BY `t`.`tax_id`, `t`.`domain_id`", $stmt);

        $jn = new Join('LEFT', 'invoices', 'iv');
        $jn->addSimpleItem('iv.customer_id', new DbField('c.id'), 'AND');
        $jn->addSimpleItem('iv.domain_id', new DbField('c.domain_id'));

        $stmt = $jn->build($keyPairs);
        $this->assertEquals( "LEFT JOIN `si_invoices` AS iv ON (`iv`.`customer_id` = `c`.`id` AND `iv`.`domain_id` = `c`.`domain_id`)", $stmt, 'Invalid build result');
    }
}
