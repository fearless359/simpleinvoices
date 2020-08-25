<?php

use Inc\Claz\ExpenseAccount;
use Inc\Claz\Util;

global $LANG, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// Deal with op and add some basic sanity checking
$op = ! empty ( $_POST ['op'] ) ? addslashes ( $_POST ['op'] ) : NULL;

$displayBlock = "<div class='si_message_error'>{$LANG['save_expense_account_failure']}</div>";
$refreshRedirect = '<meta http-equiv="refresh" content="2;URL=index.php?module=expense_account&amp;view=manage" />';

if ($op === 'insert') {
    if (ExpenseAccount::insert()) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['save_expense_account_success']}</div>";
    }
} elseif ($op === 'edit') {
    if (ExpenseAccount::update()) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['save_expense_account_success']}</div>";
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign ( 'pageActive', 'expense_account_manage' );
$smarty->assign ( 'active_tab', '#money' );
