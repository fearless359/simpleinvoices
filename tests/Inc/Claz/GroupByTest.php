<?php
/**
 * @name GroupByTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace test\Inc\Claz;

use Inc\Claz\GroupBy;
use PHPUnit\Framework\TestCase;

class GroupByTest extends TestCase
{

    public function testGroupByClass()
    {
        $gr = new GroupBy(array('ivl.customer_id', 'apl.domain_id'));
        $stmt = $gr->build();
        $this->assertEquals('GROUP BY `ivl`.`customer_id`, `apl`.`domain_id`', $stmt);
    }
}
