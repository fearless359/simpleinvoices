<?php

use Inc\Claz\DynamicJs;
use Inc\Claz\Invoice;
use Inc\Claz\Payment;
use Inc\Claz\PaymentType;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

/*validation code*/
DynamicJs::begin();
DynamicJs::formValidationBegin("frmpost");
DynamicJs::validateRequired("name","Biller name");
DynamicJs::formValidationEnd();
DynamicJs::end();
/*end validation code*/

// If the invoice ID is present, access payments for it but only first.
// TODO Handle chance of multiple payments on an invoice
if (isset($_GET['ac_inv_id'])) {
    $payment = Payment::getOne($_GET['ac_inv_id'], false);
} else {
    $payment = Payment::getOne($_GET['id']);
}

$smarty->assign("payment"    , $payment);
if (isset($payment)) {
    /*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
    $invoice     = Invoice::getInvoice($payment['ac_inv_id']);
    $invoiceType = Invoice::getInvoiceType($invoice['type_id']);
    $paymentType = PaymentType::getOne($payment['ac_payment_type']);

    $smarty->assign("invoice"    , $invoice);
    $smarty->assign("invoiceType", $invoiceType);
    $smarty->assign("paymentType", $paymentType);
}

$smarty->assign('pageActive', 'payment');
$smarty->assign('active_tab', '#money');
