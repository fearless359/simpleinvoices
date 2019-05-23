<?php

use Inc\Claz\Customer;
use Inc\Claz\Invoice;
use Inc\Claz\Payment;
use Inc\Claz\Preferences;
use Inc\Claz\Util;

global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// @formatter:off
$payments   = array();
$inv_id     = null;
$c_id       = null;
$preference = null;
$customer   = null;
// @formatter:on

if (!empty($_GET['id'])) {
    // Filter by just one invoice
    $inv_id        = $_GET['id'];
    $payments      = Payment::getInvoicePayments($inv_id, true);
    $subPageActive = "payment_filter_invoice";
    $no_entry_msg  = $LANG['no_payments_invoice'];
} else if (!empty($_GET['c_id'])) {
    // Filter by just one customer
    $c_id          = $_GET['c_id'];
    $payments      = Payment::getCustomerPayments($c_id, true);
    $subPageActive = "payment_filter_customer";
    $no_entry_msg  = $LANG['no_payments_customer'];
} else {
    // No filters
    $payments = Payment::getAll(true);
    $subPageActive = "payment_manage";
    $no_entry_msg  = $LANG['no_payments'];
}

$data = json_encode(array('data' => $payments));
if (file_put_contents("public/data.json", $data) === false) {
    die("Unable to create public/data.json file");
}

$smarty->assign("number_of_rows", count($payments));
//$smarty->assign("preference", $preference);
//$smarty->assign("customer", $customer);
$smarty->assign('no_entry_msg', $no_entry_msg);

//$smarty->assign("c_id", $c_id);
//$smarty->assign("inv_id", $inv_id);

$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('pageActive', 'payment');
$smarty->assign('active_tab', '#money');
