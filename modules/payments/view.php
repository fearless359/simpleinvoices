<?php

use Inc\Claz\Invoice;
use Inc\Claz\Payment;
use Inc\Claz\PaymentType;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

try {
    $payment = [];
    // If the invoice ID is present, access payments for it but only first.
    if (isset($_GET['ac_inv_id'])) {
        // Can result in multiple payments being retrieved.
        $payment = Payment::getOne($_GET['ac_inv_id'], false);
    } elseif (isset($_GET['id'])) {
        $payment = Payment::getOne($_GET['id']);
    }

    $numPymtRecs = empty($payment) ? 0 : $payment['num_payment_recs'];
    if ($numPymtRecs > 1 && isset($_GET['id'])) {
        // If multiple payments and have unique ID, get the requested record.
        $payment = Payment::getOne($_GET['id']);
    }

    if (isset($_GET['message'])) {
        $smarty->assign('message', $_GET['message']);
    }
} catch (PdoDbException $pde) {
    error_log("payments/view.php - Invalid id error: " . $pde->getMessage());
    exit("Unable to get payment to display. View error log for more information.");
}

$smarty->assign('num_payment_recs', $numPymtRecs);
$smarty->assign("payment", $payment);

if ($numPymtRecs > 0) {
    try {
        /*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
        $invoice = Invoice::getOne($payment['ac_inv_id']);

        $smarty->assign("invoice", $invoice);
        $smarty->assign("invoiceType", Invoice::getInvoiceType($invoice['type_id']));
        $smarty->assign("paymentType", PaymentType::getOne($payment['ac_payment_type']));
    } catch (PdoDbException $pde) {
        exit("modules/payments/details.php Unexpected error: {$pde->getMessage()}");
    }
}

$smarty->assign('pageActive', 'payment');
$smarty->assign('activeTab', '#money');
