<?php
/**
 * @name NetIncomeItemTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20201201
 */

namespace Inc\Claz;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class NetIncomeItemTest extends TestCase
{

    public function testConstruct()
    {
        $amt = 25.50;
        $desc = 'Labor';
        $cFlags = '0010000000';
        $nii = new NetIncomeItem($amt, $desc, $cFlags);
        Assert::assertEquals($amt, $nii->getAmount());
        Assert::assertEquals($desc, $nii->getDescription());
        Assert::assertEquals(0, $nii->getNonIncAmt());
        Assert::assertEquals(['0','0','1','0','0','0','0','0','0','0',], $nii->getCFlags());
    }
}
