<?php

use Inc\Claz\ExpenseAccount;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$smarty->assign('expense_account', ExpenseAccount::getOne($_GET['id']));

$smarty->assign('pageActive', 'expenseAccount');
$smarty->assign('subPageActive', "expenseAccountView");
$smarty->assign('activeTab', '#money');
