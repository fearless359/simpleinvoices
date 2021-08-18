<?php
namespace Inc\Claz;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Class CustInfoTest
 * @name CustInfoTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190310
 * @package test\Inc\Claz
 */
class CustInfoTest extends TestCase
{

    public static function testConstruct()
    {
        $custInfo = new CustInfo('myName', '50.49', '50.00', '.49', []);

        Assert::assertEquals('myName', $custInfo->name);
        Assert::assertEquals('50.49', $custInfo->billed);
        Assert::assertEquals('50.00', $custInfo->paid);
        Assert::assertEquals('.49', $custInfo->owed);
        Assert::assertEmpty($custInfo->invInfo);
    }
}
