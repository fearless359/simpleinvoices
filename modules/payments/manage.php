<?php

use Inc\Claz\Payment;
use Inc\Claz\Util;

global $LANG, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// @formatter:off
if (!empty($_GET['id'])) {
    // Filter by just one invoice
    $payments      = Payment::getInvoicePayments($_GET['id'], true);
    $subPageActive = "paymentFilterInvoice";
    $noEntryMsg    = $LANG['noPaymentsInvoice'];
} elseif (!empty($_GET['c_id'])) {
    // Filter by just one customer
    $payments      = Payment::getCustomerPayments($_GET['c_id'], true);
    $subPageActive = "paymentFilterCustomer";
    $noEntryMsg    = $LANG['noPaymentsCustomer'];
} else {
    // No filters
    $payments      = Payment::getAll(true);
    $subPageActive = "payment_manage";
    $noEntryMsg    = $LANG['noPayments'];
}
// @formatter:on

$data = json_encode(['data' => $payments]);
if (file_put_contents("public/data.json", $data) === false) {
    exit("Unable to create public/data.json file");
}

$smarty->assign("numberOfRows", count($payments));
$smarty->assign('noEntryMsg', $noEntryMsg);

$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('pageActive', 'payment');
$smarty->assign('activeTab', '#money');
