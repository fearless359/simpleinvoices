<?php

use Inc\Claz\Expense;
use Inc\Claz\Util;

global $LANG, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// Deal with op and add some basic sanity checking
$op = !empty($_POST['op']) ? $_POST['op'] : "";

$displayBlock = "<div class='si_message_error'>{$LANG['saveExpenseFailure']}</div>";
$refreshRedirect = '<meta http-equiv="refresh" content="2;URL=index.php?module=expense&amp;view=manage" />';

$_POST['customer_id'] = empty($_POST['customer_id']) ? null : $_POST['customer_id'];
$_POST['invoice_id'] = empty($_POST['invoice_id']) ? null : $_POST['invoice_id'];
$_POST['product_id'] = empty($_POST['product_id']) ? null : $_POST['product_id'];

if ($op === 'create') {
    if (Expense::insert()) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['saveExpenseSuccess']}</div>";
    }
} elseif ($op === 'edit') {
    if (Expense::update()) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['saveExpenseSuccess']}</div>";
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign('pageActive', 'expense');
$smarty->assign('activeTab', '#money');
