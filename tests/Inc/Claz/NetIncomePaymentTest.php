<?php
/**
 * @name NetIncomePaymentTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20201201
 */

namespace Inc\Claz;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class NetIncomePaymentTest extends TestCase
{

    public function testConstruct()
    {
        $amt = 25.50;
        $dt = '2020-01-15 01:59:30';
        $cFlags = '0010000000';
        $nip = new NetIncomePayment($amt, $dt, $cFlags);
        Assert::assertEquals($amt, $nip->getAmount());
        Assert::assertEquals($dt, $nip->getDate());
        Assert::assertEquals(['0','0','1','0','0','0','0','0','0','0',], $nip->getCFlags());
    }
}
