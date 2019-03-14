<?php
/**
 * @name OnItemTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace test\Inc\Claz;

use Inc\Claz\OnItem;
use PHPUnit\Framework\TestCase;

class OnItemTest extends TestCase
{

    public function testOnItemClass()
    {
        $oi = new OnItem(true, 'db_field', "=", 10, false, 'OR');
        $stmt = $oi->build($cnt, $keyPairs);
        $this->assertEquals("(`db_field` = :db_field_000  OR ", $stmt, 'OnItem build test failed');
        $this->assertEquals(1, $cnt, 'OnItem cnt test failed');
        $this->assertEquals(10, $keyPairs[':db_field_000'], 'OnItem keyPairs test failed');
    }
}
