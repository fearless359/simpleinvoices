<?php
/**
 * @name OrderByTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace test\Inc\Claz;

use Inc\Claz\OrderBy;
use PHPUnit\Framework\TestCase;

class OrderByTest extends TestCase
{

    public function testOrderByClass()
    {
        $ob = new OrderBy('enable_txt', 'D');
        $ob->addField('id', 'D');
        $ob->addField('name', 'A');
        $stmt = $ob->build();
        $this->assertEquals("ORDER BY `enable_txt` DESC, `id` DESC, `name` ASC", $stmt, 'OrderBy class test failed');
    }
}
