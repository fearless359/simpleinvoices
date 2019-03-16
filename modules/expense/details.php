<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\Expense;
use Inc\Claz\ExpenseAccount;
use Inc\Claz\ExpenseTax;
use Inc\Claz\Invoice;
use Inc\Claz\Product;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty, $LANG;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// @formatter:off
$expense_id  = $_GET['id'];

$expense = Expense::getOne($expense_id);

$detail  = Expense::additionalInfo();
$detail['customer']            = Customer::getOne($expense['c_id']);
$detail['biller']              = Biller::getOne($expense['b_id']);
$detail['invoice']             = Invoice::getOne($expense['iv_id']);
$detail['product']             = Product::getOne($expense['p_id']);
$detail['expense_account']     = ExpenseAccount::getOne($expense['ea_id']);
$detail['expense_tax']         = ExpenseTax::getAll($expense_id);
$detail['expense_tax_total']   = $expense['amount'] + ExpenseTax::getSum($expense_id);
$detail['expense_tax_grouped'] = ExpenseTax::grouped($expense_id);

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
// @formatter:on
