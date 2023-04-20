<?php

use Inc\Claz\Expense;
use Inc\Claz\Payment;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;

/*
 *  Script: delete.php
 *      Do the deletion of an expense
 *
 *  Authors:
 *      Richard Rowley
 *
 *  Last edited:
 *      2023-04-17
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $LANG, $pdoDb, $smarty;

// Stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$id = $_GET['id'];
try {
    if ($_GET['stage'] == 2 && $_POST['doDelete'] == 'y') {
        if (isset($_POST['submit'])) {
            if (Expense::delete($id)) {
                $displayBlock = "<div class='si_message_ok'>{$LANG['deleteSuccess']}</div>";
                $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=expense&amp;view=manage' />";
            } else {
                $displayBlock = "<div class='si_message_ok'>{$LANG['deleteFailed']}</div>";
                $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=expense&amp;view=manage' />";
            }
        } else {
            $displayBlock = "<div class='si_message_warning'>{$LANG['deleteCancelled']}</div>";
            $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=expense&amp;view=view&amp;id=$id&amp;message=Delete request cancelled' />";
        }

        $smarty->assign('refresh_redirect', $refreshRedirect);
        $smarty->assign('display_block', $displayBlock);
    } else {
        $expense = Expense::getOne($id);
        $smarty->assign("expense", $expense);
        $smarty->assign('subPageActive', "expenseDelete");
    }
} catch (PdoDbException $pde) {
    exit("modules/expense/delete.php Unexpected error: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'expense');
$smarty->assign('activeTab', '#money');
