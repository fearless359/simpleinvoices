<?php

use Inc\Claz\ExpenseAccount;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

$display_block = "<div class='si_message_error'>{$LANG['no_expense_accounts']}</div>";

$expense_accounts = ExpenseAccount::getAll();

$smarty->assign('expense_accounts', $expense_accounts);
$smarty->assign("number_of_rows", count($expense_accounts));
$smarty->assign("display_block", $display_block);

$smarty->assign('pageActive', 'expense_account');
$smarty->assign('active_tab', '#money');
