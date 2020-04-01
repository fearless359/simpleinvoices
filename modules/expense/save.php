<?php

use Inc\Claz\Expense;
use Inc\Claz\Util;

global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// Deal with op and add some basic sanity checking
$op = ! empty ( $_POST ['op'] ) ? addslashes ( $_POST ['op'] ) : null;

$display_block = "<div class='si_message_error'>{$LANG['save_expense_failure']}</div>";
$refresh_redirect = '<meta http-equiv="refresh" content="2;URL=index.php?module=expense&amp;view=manage" />';

if (empty($_POST['customer_id'])) {
}

if (!$error) {
    if (empty($_POST['customer_id'])) {
        $_POST['customer_id'] = null;
    }

    if (empty($_POST['invoice_id'])) {
        $_POST['invoice_id'] = null;
    }

    if (empty($_POST['product_id'])) {
        $_POST['product_id'] = null;
    }

    if ($op === 'add') {
        if (Expense::insert()) {
            $display_block = "<div class='si_message_ok'>{$LANG['save_expense_success']}</div>";
        }
    } else if ($op === 'edit') {
        if (Expense::update()) {
            $display_block = "<div class='si_message_ok'>{$LANG['save_expense_success']}</div>";
        }
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_redirect', $refresh_redirect);

$smarty->assign('pageActive', 'expense');
$smarty->assign('active_tab', '#money');

