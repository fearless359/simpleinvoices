<?php
/**
 * @name JoinTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

const TB_PREFIX = "si_";

/**
 * Class JoinTest
 * @package Inc\Claz
 */
class JoinTest extends TestCase
{

    public function testJoinClass()
    {

        try {
            $onClause = new OnClause();
            $onClause->addSimpleItem("t.tax_id", "et.tax_id", "AND");
            $onClause->addSimpleItem("t.domain_id", "e.domain_id");

            $jn = new Join('LEFT', 'preferences', 'pref');
            try {
                $jn->setOnClause($onClause);
            } catch (PdoDbException $pde) {
                static::assertTrue(false, "testJoinClass() Unexpected error from setOnClause. Error: {$pde->getMessage()}");
            }

            $jn->addGroupBy(new GroupBy(['t.tax_id', 't.domain_id']));
            try {
                $stmt = $jn->build($keyPairs);
                static::assertEquals("LEFT JOIN `si_preferences` AS pref ON (`t`.`tax_id` = :t_tax_id_000  AND `t`.`domain_id` = :t_domain_id_001 ) GROUP BY `t`.`tax_id`, `t`.`domain_id`", $stmt);
            } catch (PdoDbException $pde) {
                static::assertTrue(false, "testJoinClass() Unexpected error from build. Error: {$pde->getMessage()}");
            }
        } catch (PdoDbException $pde) {
            static::assertTrue(false, "testJoinClass() Unexpected error onClause setup. Error: {$pde->getMessage()}");
        }

        $jn = new Join('LEFT', 'invoices', 'iv');
        try {
            $jn->addSimpleItem('iv.customer_id', new DbField('c.id'), 'AND');
            $jn->addSimpleItem('iv.domain_id', new DbField('c.domain_id'));

            $stmt = $jn->build($keyPairs);
            static::assertEquals("LEFT JOIN `si_invoices` AS iv ON (`iv`.`customer_id` = `c`.`id` AND `iv`.`domain_id` = `c`.`domain_id`)", $stmt, 'Invalid build result');
        } catch (PdoDbException $pde) {
            static::assertTrue(false, "testJoinClass() Unexpected error from Join build. Error: {$pde->getMessage()}");
        }
    }
}
