<?php

use Inc\Claz\PaymentWarehouse;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;

/*
 *  Script: delete.php
 *      Do the deletion of a payment
 *
 *  Authors:
 *      Richard Rowley
 *
 *  Last edited:
 *      2022-10-21
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
            if (PaymentWarehouse::delete($id)) {
                $displayBlock = "<div class='si_message_ok'>{$LANG['deleteSuccess']}</div>";
            } else {
                $displayBlock = "<div class='si_message_error'>{$LANG['deleteFailed']}</div>";
            }
            $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=payment_warehouse&amp;view=manage' />";
        } else {
            $displayBlock = "<div class='si_message_warning'>{$LANG['deleteCancelled']}</div>";
            $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=payment_warehouse&amp;view=view&amp;id=$id&amp;message=Delete request cancelled' />";
        }

        $smarty->assign('refresh_redirect', $refreshRedirect);
        $smarty->assign('display_block', $displayBlock);
    } else {
        $pw = PaymentWarehouse::getOne($id, 0);
        $smarty->assign("paymentWarehouse", $pw);

        $smarty->assign('subPageActive', "delete");
    }
} catch (PdoDbException $pde) {
    exit("modules/payment_warehouse/delete.php Unexpected error: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'paymentWarehouse');
$smarty->assign('activeTab', '#money');
