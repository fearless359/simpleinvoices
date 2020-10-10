<?php

use Inc\Claz\Expense;
use Inc\Claz\ExpenseAccount;
use Inc\Claz\Util;

global $LANG, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$addButtonLink = "index.php?module=expense&amp;view=create";
$addButtonMsg = $LANG['addNewExpense'];
$displayBlock = "<div class='si_message_error'>{$LANG['noExpenses']}</div>";

$expenses = Expense::getAll();
$numberOfRows = count($expenses);
if ($numberOfRows == 0) {
    if (ExpenseAccount::count() == 0) {
        $displayBlock = "<div class='si_message_error'>{$LANG['noExpenseAccounts']}</div>";
        $addButtonLink = "index.php?module=expense_account&amp;view=create";
        $addButtonMsg = $LANG['addNewExpenseAccount'];
    }
}

$smarty->assign('expenses', $expenses);
$smarty->assign("number_of_rows", $numberOfRows );
$smarty->assign("display_block", $displayBlock);
$smarty->assign("add_button_link", $addButtonLink);
$smarty->assign("add_button_msg", $addButtonMsg);

$smarty->assign('pageActive', 'expense');
$smarty->assign('activeTab', '#money');
