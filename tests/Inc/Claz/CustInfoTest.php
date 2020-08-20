<?php

/**
 * @name CustInfoTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190310
 */

namespace Inc\Claz;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Class CustInfoTest
 * @package test\Inc\Claz
 */
class CustInfoTest extends TestCase
{

    public static function testConstruct()
    {
        $custInfo = new CustInfo('myName', '50.49', '50.00', '.49', []);

        Assert::assertEquals('myName', $custInfo->name);
        Assert::assertEquals('50.49', $custInfo->fmtdBilled);
        Assert::assertEquals('50.00', $custInfo->fmtdPaid);
        Assert::assertEquals('.49', $custInfo->fmtdOwed);
        Assert::assertEmpty($custInfo->invInfo);
    }
}
