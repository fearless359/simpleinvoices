<?php

use Inc\Claz\ExpenseAccount;
use Inc\Claz\Util;

global $LANG, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// Deal with op and add some basic sanity checking
$op = !empty($_POST ['op']) ? $_POST['op'] : "";

$displayBlock = "<div class='si_message_error'>{$LANG['saveExpenseAccountFailure']}</div>";
$refreshRedirect = '<meta http-equiv="refresh" content="2;URL=index.php?module=expense_account&amp;view=manage" />';

if ($op === 'create') {
    if (ExpenseAccount::insert()) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['saveExpenseAccountSuccess']}</div>";
    }
} elseif ($op === 'edit') {
    if (ExpenseAccount::update()) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['saveExpenseAccountSuccess']}</div>";
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign ( 'pageActive', 'expenseAccount' );
$smarty->assign ( 'activeTab', '#money' );
