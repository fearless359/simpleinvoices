<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\Expense;
use Inc\Claz\ExpenseAccount;
use Inc\Claz\ExpenseTax;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\Product;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $LANG, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$expenseId  = $_GET['id'];
$action     = $_GET['action'];

$expense = Expense::getOne($expenseId);
try {
    // @formatter:of
    if ($action == 'view') {
        $detail = Expense::additionalInfo($expense);
    } else {
        $detail = Expense::additionalInfo();
    }

    $detail['expense_tax']         = ExpenseTax::getAll($expenseId);
    $detail['expense_tax_total']   = $expense['amount'] + ExpenseTax::getSum($expenseId);
    $detail['expense_tax_grouped'] = ExpenseTax::grouped($expenseId);
} catch (PdoDbException $pde) {
    exit("modules/expense/details.php Unexpected error: [{$pde->getMessage()}]");
}

$taxes = Taxes::getActiveTaxes();
$defaults = SystemDefaults::loadValues();

$smarty->assign('expense' ,$expense);
$smarty->assign('detail'  ,$detail);
$smarty->assign('taxes'   ,$taxes);
$smarty->assign('defaults',$defaults);

$smarty->assign('pageActive', 'expense');

$subPageActive = $_GET['action'] =="view"  ? "view" : "edit" ;

$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab', '#money');
