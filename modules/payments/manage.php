<?php

use Inc\Claz\Customer;
use Inc\Claz\Invoice;
use Inc\Claz\Payment;
use Inc\Claz\Preferences;
use Inc\Claz\Util;

global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

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
    $payments      = Payment::getInvoicePayments($inv_id);
    $invoice       = Invoice::getInvoice($inv_id);
    $preference    = Preferences::getOne($invoice['preference_id']);
    $subPageActive = "payment_filter_invoice";
    $no_entry_msg  = $LANG['no_payments_invoice'];
} else if (!empty($_GET['c_id'])) {
    // Filter by just one customer
    $c_id          = $_GET['c_id'];
    $payments      = Payment::getCustomerPayments($c_id);
    $customer      = Customer::getOne($c_id);
    $subPageActive = "payment_filter_customer";
    $no_entry_msg  = $LANG['no_payments_customer'];
} else {
    // No filters
    $payments = Payment::getAll();
    $subPageActive = "payment_manage";
    $no_entry_msg  = $LANG['no_payments'];
}

$smarty->assign("payments"    , $payments);
$smarty->assign("preference"  , $preference);
$smarty->assign("customer"    , $customer);
$smarty->assign('no_entry_msg', $no_entry_msg);

$smarty->assign("c_id"  , $c_id);
$smarty->assign("inv_id", $inv_id);

$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('pageActive'   , 'payment');
$smarty->assign('active_tab'   , '#money');
