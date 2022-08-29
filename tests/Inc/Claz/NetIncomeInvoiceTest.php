<?php
namespace Inc\Claz;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Class NetIncomeInvoiceTest
 * @name NetIncomeInvoiceTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20201201
 * @package Inc\Claz
 */
class NetIncomeInvoiceTest extends TestCase
{
    private int $id = 123;
    private int $indexId = 987;
    private string $dt = '2020-01-15 01:59:30';
    private string $custName = 'Richard Rowley';

    public function testConstruct()
    {
        $nii = new NetIncomeInvoice($this->id, $this->indexId, $this->dt, $this->custName);
        Assert::assertEquals($this->id, $nii->getId());
        Assert::assertEquals($this->indexId, $nii->getIndexId());
        Assert::assertEquals($this->dt, $nii->getDate());
        Assert::assertEquals($this->custName, $nii->getCustomerName());
    }

    public function testAddPayment()
    {
        $pymt1 = 27.50;
        $pymt2 = 12.57;
        $nii = new NetIncomeInvoice($this->id, $this->indexId, $this->dt, $this->custName);
        $nii->addPayment($pymt1, $this->dt, false);
        $nii->addPayment($pymt2, $this->dt, true);
        Assert::assertEquals($pymt1 + $pymt2, $nii->getTotalPayments());
        Assert::assertEquals($pymt2, $nii->getTotalPeriodPayments());
    }

    public function testAddItem()
    {
        $amt1 = 27.50;
        $amt2 = 12.57;
        $cflags = '0000000000';
        $nii = new NetIncomeInvoice($this->id, $this->indexId, $this->dt, $this->custName);
        $nii->addItem($amt1, 'Amt1', $cflags);
        $nii->addItem($amt2, 'Amt2', $cflags);
        Assert::assertEquals($amt1 + $amt2, $nii->getTotalAmount());
        Assert::assertCount(2, $nii->getItems());
    }

    public function testAdjustPymtsForNonIncome()
    {
        $pymt1 = 100.00;
        $pymt2 =  5.50;
        $amt = 50.00;
        $cflags = '0000000000';
        $nii = new NetIncomeInvoice($this->id, $this->indexId, $this->dt, $this->custName);
        $nii->addItem($amt, 'Amt', $cflags);
        $nii->addPayment($pymt1, $this->dt, false);
        $nii->addPayment($pymt2, $this->dt, true);
        $nii->adjustPymtsForNonIncome();
        Assert::assertCount(2, $nii->getPymts());
        Assert::assertEquals($amt, $nii->getTotalAmount());
        Assert::assertEquals($amt, $nii->getTotalPayments());
        Assert::assertEquals($pymt2, $nii->getTotalPeriodPayments());
    }
}
