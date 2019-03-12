<?php
/**
 * @name CustInfoTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190310
 */

namespace test\Inc\Claz;

use Inc\Claz\CustInfo;
use PHPUnit\Framework\TestCase;

class CustInfoTest extends TestCase
{

    public function test__construct()
    {
        $custInfo = new CustInfo('myName', '50.49', '50.00', '.49', null);

        $this->assertEquals('myName', $custInfo->name);
        $this->assertEquals('50.49', $custInfo->billed);
        $this->assertEquals('50.00', $custInfo->paid);
        $this->assertEquals('.49', $custInfo->owed);
        $this->assertNull($custInfo->inv_info);
    }
}
