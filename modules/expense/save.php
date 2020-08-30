<?php

use Inc\Claz\Expense;
use Inc\Claz\Util;

global $LANG, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// Deal with op and add some basic sanity checking
$op = !empty($_POST['op']) ? addslashes($_POST['op']) : null;

$displayBlock = "<div class='si_message_error'>{$LANG['save_expense_failure']}</div>";
$refreshRedirect = '<meta http-equiv="refresh" content="2;URL=index.php?module=expense&amp;view=manage" />';

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
        $displayBlock = "<div class='si_message_ok'>{$LANG['save_expense_success']}</div>";
    }
} elseif ($op === 'edit') {
    if (Expense::update()) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['save_expense_success']}</div>";
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign('pageActive', 'expense');
$smarty->assign('active_tab', '#money');
