<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\Email;
use Inc\Claz\Export;
use Inc\Claz\Invoice;
use Inc\Claz\Preferences;
use Inc\Claz\Util;

use Mpdf\Output\Destination;

/*
 *  Script: email.php
 *      Email invoice page
 *
 *  Last Modified:
 *      2016-08-15
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

// @formatter:off
$invoice_id  = $_GET['id'];
$invoice     = Invoice::getOne($invoice_id);
$preference  = Preferences::getOne($invoice['preference_id']);
$biller      = Biller::getOne($invoice['biller_id']);
$customer    = Customer::getOne($invoice['customer_id']);

$smarty->assign('biller'     , $biller);
$smarty->assign('customer'   , $customer);
$smarty->assign('invoice'    , $invoice);
$smarty->assign('preferences', $preference);

$error = false;
$message = "Unable to process email request.";
if ($_GET['stage'] == 2 ) {
    $export = new Export(Mpdf\Output\Destination::STRING_RETURN);
    $export->setBiller($biller);
    $export->setCustomer($customer);
    $export->setFormat("pdf");
    $export->setId($invoice_id);
    $export->setInvoice($invoice);
    $export->setModule('invoice');
    $export->setPreference($preference);
    $pdf_string = $export->execute();

    $email = new Email;
    $email->setBcc($_POST['email_bcc']);
    $email->setBody($_POST['email_notes']);
    $email->setFormat('invoice');
    $email->setFrom($_POST['email_from']);
    $email->setFromFriendly($biller['name']);
    $email->setPdfFileName($export->getFileName() . '.pdf');
    $email->setPdfString($pdf_string);
    $email->setSubject($_POST['email_subject']);
    $email->setTo($_POST['email_to']);

    $results = $email->send();
    $smarty->assign('display_block', $results['display_block']);
    $smarty->assign('refresh_redirect', $results['refresh_redirect']);

    $message = '';
} else if ($_GET['stage'] == 3 ) {
    //stage 3 = assemble email and send
    $message = "Invalid routing to stage 3 of email processing. Probably a process error.";
    $error = true;
}

$smarty->assign('error'      , ($error ? "1":"0"));
$smarty->assign('message'    , $message);

$smarty->assign('pageActive', 'invoice');
$smarty->assign('active_tab', '#money');
// @formatter:on
