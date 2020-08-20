<?php

use Inc\Claz\Payment;
use Inc\Claz\Util;

global $LANG, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// @formatter:off
$payments    = [];
$invId      = null;
$cId        = null;
$preference = null;
$customer   = null;
// @formatter:on

if (!empty($_GET['id'])) {
    // Filter by just one invoice
    $invId         = $_GET['id'];
    $payments      = Payment::getInvoicePayments($invId, true);
    $subPageActive = "payment_filter_invoice";
    $noEntryMsg    = $LANG['no_payments_invoice'];
} elseif (!empty($_GET['c_id'])) {
    // Filter by just one customer
    $cId           = $_GET['c_id'];
    $payments      = Payment::getCustomerPayments($cId, true);
    $subPageActive = "payment_filter_customer";
    $noEntryMsg    = $LANG['no_payments_customer'];
} else {
    // No filters
    $payments      = Payment::getAll(true);
    $subPageActive = "payment_manage";
    $noEntryMsg    = $LANG['no_payments'];
}

$data = json_encode(['data' => $payments]);
if (file_put_contents("public/data.json", $data) === false) {
    die("Unable to create public/data.json file");
}

$smarty->assign("numberOfRows", count($payments));
$smarty->assign('noEntryMsg', $noEntryMsg);

$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('pageActive', 'payment');
$smarty->assign('active_tab', '#money');
