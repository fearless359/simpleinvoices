<?php
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin ();

$add_button_link = "index.php?module=expense&view=add";
$add_button_msg = $LANG['add_new_expense'];
$display_block = "<div class='si_message_error'>{$LANG['no_expenses']}</div>";

$number_of_rows = Expense::count();
if ($number_of_rows == 0) {
    $count = ExpenseAccount::count();
    if ($count == 0) {
        $display_block = "<div class='si_message_error'>{$LANG['no_expense_accounts']}</div>";
        $add_button_link = "index.php?module=expense_account&view=add";
        $add_button_msg = $LANG['add_new_expense_account'];
    }
}
$smarty->assign("number_of_rows", $number_of_rows );
$smarty->assign("display_block", $display_block);
$smarty->assign("add_button_link", $add_button_link);
$smarty->assign("add_button_msg", $add_button_msg);

$url = "index.php?module=expense&view=xml";
if (isset($_GET['query'])) {
    $url .= "&amp;query={$_GET['query']}&amp;qtype={$_GET['qtype']}";
}
$smarty->assign('url', $url);

$smarty->assign('pageActive', 'expense');
$smarty->assign('active_tab', '#money');
