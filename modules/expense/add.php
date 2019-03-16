<?php

use Inc\Claz\DomainId;
use Inc\Claz\Expense;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$expense_add = Expense::additionalInfo();
$defaults    = SystemDefaults::loadValues();
$taxes       = Taxes::getActiveTaxes();

// if valid then do save
if (!empty( $_POST ['expense_account_id'] )) {
    include "modules/expense/save.php";
} else {
    $smarty->assign('domain_id', DomainId::get());
    $smarty->assign('taxes', $taxes);
    $smarty->assign('expense_add', $expense_add);
    $smarty->assign('defaults', $defaults);

    $smarty->assign('pageActive', 'expense');
    $smarty->assign('subPageActive', 'add');
    $smarty->assign('active_tab', '#money');
}
