<?php

use Inc\Claz\ExpenseAccount;
use Inc\Claz\Util;

global $LANG, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$smarty->assign("display_block", "<div class='si_message_error'>{$LANG['noExpenseAccounts']}</div>");

$expenseAccounts = ExpenseAccount::manageTableInfo();

$data = json_encode(['data' => $expenseAccounts]);
if (file_put_contents("public/data.json", $data) === false) {
    exit("Unable to create public/data.json file");
}

$smarty->assign("numberOfRows", count($expenseAccounts));

$smarty->assign('pageActive', 'expenseAccount');
$smarty->assign('activeTab', '#money');
