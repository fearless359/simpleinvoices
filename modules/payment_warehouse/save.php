<?php

use Inc\Claz\PaymentWarehouse;
use Inc\Claz\Util;

global $LANG, $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed.
Util::directAccessAllowed();

$displayBlock = "<div class='si_message_error'>{$LANG['savePaymentWarehouseFailure']}</div>";
$refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=payment_warehouse&amp;view=manage'/>";

if (isset($_POST['savePaymentWarehouse'])) {
    $id = $_GET['id'] ?? 0;
    $op = $_POST['op'] ?? '';

    $customerId = $_POST['customer'] ?? 0;
    $lastPymtId = $_POST['last_payment_id'] ?? null;
    $balance = $_POST['balance'] ?? 0;
    $paymentType = $_POST['payment_type'] ?? 0;
    $checkNumber = $_POST['check_number'] ?? '';

    if ($op === 'create') {
        if (PaymentWarehouse::insert($customerId, null, $balance, $paymentType, $checkNumber)) {
            $displayBlock = "<div class='si_message_ok'>{$LANG['savePaymentWarehouseSuccess']}</div>";
        }
    } elseif ($op === 'edit') {
        if (PaymentWarehouse::update($id, $balance, $paymentType, $checkNumber)) {
            $displayBlock = "<div class='si_message_ok'>{$LANG['savePaymentWarehouseSuccess']}</div>";
        }
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign('pageActive', 'paymentWarehouse');
$smarty->assign('activeTab', '#money');
