<?php
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$display_block = "<div class='si_message_error'>{$LANG['no_expense_accounts']}</div>";

$number_of_rows  = ExpenseAccount::count();

$smarty->assign("number_of_rows", $number_of_rows );
$smarty->assign("display_block", $display_block);

$smarty->assign('pageActive', 'expense_account');
$smarty->assign('active_tab', '#money');
