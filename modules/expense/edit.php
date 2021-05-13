<?php

use Inc\Claz\Expense;
use Inc\Claz\ExpenseTax;
use Inc\Claz\PdoDbException;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$expenseId = $_GET['id'];

$expense = Expense::getOne($expenseId);
try {
    $detail = Expense::additionalInfo();
    $detail['expense_tax'] = ExpenseTax::getAll($expenseId);
    $detail['expense_tax_total'] = $expense['amount'] + ExpenseTax::getSum($expenseId);
    $detail['expense_tax_grouped'] = ExpenseTax::grouped($expenseId);
} catch (PdoDbException $pde) {
    exit("modules/expense/details.php Unexpected error: [{$pde->getMessage()}]");
}

$smarty->assign('expense', $expense);
$smarty->assign('detail', $detail);
$smarty->assign('taxes', Taxes::getActiveTaxes());
$smarty->assign('defaults', SystemDefaults::loadValues());

$smarty->assign('pageActive', 'expense');
$smarty->assign('subPageActive', 'expenseEdit');
$smarty->assign('activeTab', '#money');
