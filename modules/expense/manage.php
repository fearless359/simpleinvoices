<?php

use Inc\Claz\Expense;
use Inc\Claz\ExpenseAccount;
use Inc\Claz\Util;

global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$add_button_link = "index.php?module=expense&amp;view=add";
$add_button_msg = $LANG['add_new_expense'];
$display_block = "<div class='si_message_error'>{$LANG['no_expenses']}</div>";

$expenses = Expense::getAll();
$number_of_rows = count($expenses);
if ($number_of_rows == 0) {
    $count = ExpenseAccount::count();
    if ($count == 0) {
        $display_block = "<div class='si_message_error'>{$LANG['no_expense_accounts']}</div>";
        $add_button_link = "index.php?module=expense_account&amp;view=add";
        $add_button_msg = $LANG['add_new_expense_account'];
    }
}

$smarty->assign('expenses', $expenses);
$smarty->assign("number_of_rows", $number_of_rows );
$smarty->assign("display_block", $display_block);
$smarty->assign("add_button_link", $add_button_link);
$smarty->assign("add_button_msg", $add_button_msg);

$smarty->assign('pageActive', 'expense');
$smarty->assign('active_tab', '#money');
