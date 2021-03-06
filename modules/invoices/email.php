<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\Email;
use Inc\Claz\Export;
use Inc\Claz\Invoice;
use Inc\Claz\Log;
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
Util::directAccessAllowed();

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
Log::out("email.php - _GET stage[{$_GET['stage']}]", Zend_Log::DEBUG);
if ($_GET['stage'] == 2 ) {
    $export = new Export(Destination::STRING_RETURN);
    $export->setBiller($biller);
    $export->setCustomer($customer);
    $export->setFormat("pdf");
    $export->setId($invoice_id);
    $export->setInvoice($invoice);
    $export->setModule('invoice');
    $export->setPreference($preference);
    $pdf_string = $export->execute();
    Log::out("email.php - After execute", Zend_Log::DEBUG);

    $email = new Email;
    $email->setBody($_POST['email_notes']);
    $email->setFormat('invoice');
    $email->setFromFriendly($biller['name']);
    $email->setPdfFileName($export->getFileName() . '.pdf');
    $email->setPdfString($pdf_string);
    $email->setSubject($_POST['email_subject']);

    $results = [];
    if (!$email->setFrom($_POST['email_from'])) {
        $message = "Invalid FROM field";
        $refresh_redirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";
        $display_block = "<div class=\"si_message_error\">{$message}</div>";
        $results = [
            "message" => $message,
            "refresh_redirect" => $refresh_redirect,
            "display_block" =>$display_block
        ];
    }

    if (empty($results) && !$email->setBcc($_POST['email_bcc'])) {
        $message = "Invalid BCC field";
        $refresh_redirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";
        $display_block = "<div class=\"si_message_error\">{$message}</div>";
        $results = [
            "message" => $message,
            "refresh_redirect" => $refresh_redirect,
            "display_block" =>$display_block
        ];
    }

Log::out("email.php after set BCC. results is " . (empty($results) ? "EMPTY" : "NOT EMPTY"), Zend_Log::DEBUG);
    if (empty($results) && !$email->setTo($_POST['email_to'])) {
        $message = "Invalid TO field";
        $refresh_redirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";
        $display_block = "<div class=\"si_message_error\">{$message}</div>";
        $results = [
            "message" => $message,
            "refresh_redirect" => $refresh_redirect,
            "display_block" =>$display_block
        ];
    }

    if (empty($results)) {
        Log::out("email.php - Before send", Zend_Log::DEBUG);
        $results = $email->send();
    }
    Log::out("email.php - results" . print_r($results, true), Zend_Log::DEBUG);
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
