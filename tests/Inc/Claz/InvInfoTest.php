<?php
/**
 * @name InvInfoTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190310
 */

namespace test\Inc\Claz;

use Inc\Claz\InvInfo;
use PHPUnit\Framework\TestCase;

class InvInfoTest extends TestCase
{

    public function test__construct()
    {
        $invInfo = new InvInfo(1, 2, "$50.49", "$50.00", "$0.49");
        $this->assertEquals(1, $invInfo->id);
        $this->assertEquals(2, $invInfo->index_id);
        $this->assertEquals("$50.49", $invInfo->billed);
        $this->assertEquals("$50.00", $invInfo->paid);
        $this->assertEquals("$0.49", $invInfo->owed);
    }
}
