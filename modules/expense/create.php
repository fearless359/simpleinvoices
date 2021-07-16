<?php

use Inc\Claz\DomainId;
use Inc\Claz\Expense;
use Inc\Claz\PdoDbException;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// if valid then do save
if (!empty( $_POST ['expense_account_id'] )) {
    include "modules/expense/save.php";
} else {
    try {
        $smarty->assign('expenseAdd', Expense::additionalInfo(null, true));
    } catch (PdoDbException $pde) {
        exit("modules/expense/add.php Unexpected error: {$pde->getMessage()}");
    }
    $smarty->assign('domain_id', DomainId::get());
    $smarty->assign('taxes', Taxes::getActiveTaxes());
    $smarty->assign('defaults', SystemDefaults::loadValues());

    $smarty->assign('pageActive', 'expense');
    $smarty->assign('subPageActive', 'expenseCreate');
    $smarty->assign('activeTab', '#money');
}
