<?php
/**
 * @name GroupByTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class GroupByTest
 * @package Inc\Claz
 */
class GroupByTest extends TestCase
{

    public function testGroupByClass()
    {
        $gr = new GroupBy(['ivl.customer_id', 'apl.domain_id']);
        $stmt = $gr->build();
        static::assertEquals('GROUP BY `ivl`.`customer_id`, `apl`.`domain_id`', $stmt);
    }
}
