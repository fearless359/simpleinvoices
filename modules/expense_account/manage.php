<?php

use Inc\Claz\ExpenseAccount;
use Inc\Claz\Util;

global $LANG, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$displayBlock = "<div class='si_message_error'>{$LANG['noExpenseAccounts']}</div>";

$expenseAccounts = ExpenseAccount::getAll();

$smarty->assign('expense_accounts', $expenseAccounts);
$smarty->assign("number_of_rows", count($expenseAccounts));
$smarty->assign("display_block", $displayBlock);

$smarty->assign('pageActive', 'expense_account');
$smarty->assign('activeTab', '#money');
