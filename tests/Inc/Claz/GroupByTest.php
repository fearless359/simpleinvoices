<?php
namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class GroupByTest
 * @name GroupByTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 * @package Inc\Claz
 */
class GroupByTest extends TestCase
{

    public function testGroupByClass()
    {
        $gr = new GroupBy(['ivl.customer_id', 'apl.domain_id']);
        $stmt = $gr->build();
        self::assertEquals('GROUP BY `ivl`.`customer_id`, `apl`.`domain_id`', $stmt);
    }
}
