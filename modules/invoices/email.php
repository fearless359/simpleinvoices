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

$invoiceId  = $_GET['id'];

try {
    $invoice = Invoice::getOne($invoiceId);
    $preference = Preferences::getOne($invoice['preference_id']);
    $biller = Biller::getOne($invoice['biller_id']);
    $customer = Customer::getOne($invoice['customer_id']);

    $smarty->assign('biller', $biller);
    $smarty->assign('customer', $customer);
    $smarty->assign('invoice', $invoice);
    $smarty->assign('preferences', $preference);

    $error = false;
    $message = "Unable to process email request.";
    Log::out("email.php - _GET stage[{$_GET['stage']}]");
    if ($_GET['stage'] == 2) {
        $export = new Export(Destination::STRING_RETURN);
        $export->setBiller($biller);
        $export->setCustomer($customer);
        $export->setFormat("pdf");
        $export->setRecId($invoiceId);
        $export->setInvoice($invoice);
        $export->setModule('invoice');
        $export->setPreference($preference);
        $pdfString = $export->execute();
        Log::out("email.php - After execute");

        $email = new Email();
        $email->setBody($_POST['email_notes']);
        $email->setFormat('invoice');
        $email->setFromFriendly($biller['name']);
        $email->setPdfFileName($export->getFileName() . '.pdf');
        $email->setPdfString($pdfString);
        $email->setSubject($_POST['email_subject']);

        $results = [];
        if (!$email->setFrom($_POST['email_from'])) {
            $message = "Invalid FROM field";
            $refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";
            $displayBlock = "<div class=\"si_message_error\">{$message}</div>";
            $results = [
                "message" => $message,
                "refresh_redirect" => $refreshRedirect,
                "display_block" => $displayBlock
            ];
        }

        if (empty($results) && !$email->setBcc($_POST['email_bcc'])) {
            $message = "Invalid BCC field";
            $refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";
            $displayBlock = "<div class=\"si_message_error\">{$message}</div>";
            $results = [
                "message" => $message,
                "refresh_redirect" => $refreshRedirect,
                "display_block" => $displayBlock
            ];
        }

        Log::out("email.php after set BCC. results is " . (empty($results) ? "EMPTY" : "NOT EMPTY"));
        if (empty($results) && !$email->setEmailTo($_POST['email_to'])) {
            $message = "Invalid TO field";
            $refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";
            $displayBlock = "<div class=\"si_message_error\">{$message}</div>";
            $results = [
                "message" => $message,
                "refresh_redirect" => $refreshRedirect,
                "display_block" => $displayBlock
            ];
        }

        if (empty($results)) {
            Log::out("email.php - Before send");
            $results = $email->send();
        }
        Log::out("email.php - results" . print_r($results, true));
        $smarty->assign('display_block', $results['display_block']);
        $smarty->assign('refresh_redirect', $results['refresh_redirect']);

        $message = '';
    } elseif ($_GET['stage'] == 3) {
        //stage 3 = assemble email and send
        $message = "Invalid routing to stage 3 of email processing. Probably a process error.";
        $error = true;
    }

    $smarty->assign('error', $error ? "1" : "0");
    $smarty->assign('message', $message);
} catch (Exception $exp) {
    exit("modules/invoices/email.php Unexpected error: {$exp->getMessage()}");
}

$smarty->assign('pageActive', 'invoice');
$smarty->assign('active_tab', '#money');
