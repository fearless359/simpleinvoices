<?php

use Inc\Claz\Expense;
use Inc\Claz\Util;

global $redirect_redirect, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

// Deal with op and add some basic sanity checking
$op = ! empty ( $_POST ['op'] ) ? addslashes ( $_POST ['op'] ) : NULL;

$display_block = "<div class='si_message_error'>{$LANG['save_expense_failure']}</div>";
$refresh_redirect = '<meta http-equiv="refresh" content="2;URL=index.php?module=expense&amp;view=manage" />';

$saved = false;

if ($op === 'add') {
    if (Expense::insert()) {
        $display_block = "<div class='si_message_ok'>{$LANG['save_expense_success']}</div>";
    }
} else if ($op === 'edit') {
    if (Expense::update()) {
        $display_block = "<div class='si_message_ok'>{$LANG['save_expense_success']}</div>";
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_redirect', $refresh_redirect);

$smarty->assign( 'pageActive', 'product_manage' );
$smarty->assign( 'active_tab', '#product' );
