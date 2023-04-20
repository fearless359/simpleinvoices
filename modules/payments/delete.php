<?php

use Inc\Claz\Payment;
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
            if (Payment::delete($id)) {
                $displayBlock = "<div class='si_message_ok'>{$LANG['deleteSuccess']}</div>";
                $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=invoices&amp;view=manage' />";
            } else {
                $displayBlock = "<div class='si_message_ok'>{$LANG['deleteFailed']}</div>";
                $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=payments&amp;view=manage' />";
            }
        } else {
            $displayBlock = "<div class='si_message_warning'>{$LANG['deleteCancelled']}</div>";
            $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=payments&amp;view=view&amp;id=$id&amp;message=Delete request cancelled' />";
        }

        $smarty->assign('refresh_redirect', $refreshRedirect);
        $smarty->assign('display_block', $displayBlock);
    } else {
        $payment = Payment::getOne($id);
        $smarty->assign("payment", $payment);

        if ($payment['warehouse_amount'] != 0) {
            $smarty->assign('warehouse_amount', $payment['warehouse_amount']);
            if ($payment['warehouse_amount'] > 0) {
                $adjInfo = $LANG['increased'];
            } else {
                $adjInfo = $LANG['decreased'];
            }

            $adjMsg = "{$LANG['fyi']}: {$LANG['paymentUc']} $adjInfo {$LANG['warehoused']} {$LANG['balance']} {$LANG['by']} " .
                Util::currency(abs($payment['warehouse_amount']), $payment['locale'], $payment['currency_code']);
            if (!empty($payment['ac_check_number'])) {
                $adjMsg .= " {$LANG['for']} {$LANG['checkNumber']}{$payment['ac_check_number']}";
            }
            $smarty->assign('adjustMessage', $adjMsg);

        }
        $smarty->assign('subPageActive', "paymentDelete");
    }
} catch (PdoDbException $pde) {
    exit("modules/payments/delete.php Unexpected error: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'payment');
$smarty->assign('activeTab', '#money');
