<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\Email;
use Inc\Claz\Export;
use Inc\Claz\Util;

use Mpdf\Output\Destination;
/*
 *  Script: email.php
 *      Email invoice page
 *
 *  License:
 *      GPL v3 or above
 *      
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$error = false;
$message = "Unable to process email request.";

if (empty($_GET['biller_id'])) {
    $biller = Biller::getDefaultBiller();
} else {
    $biller = Biller::getOne($_GET['biller_id']);
}

if (empty($biller)) {
    $error = true;
    $message = "Must specify a biller to send an e-mail";
    $biller_id = null;
} else {
    $biller_id = $biller['id'];
}

if (empty($_GET['customer_id'])) {
    $customer = array("id" => 0, "name" => "All");
} else {
    $customer = Customer::getOne($_GET['customer_id']);
}
$customer_id = $customer['id'];

$do_not_filter_by_date = (empty($_GET['do_not_filter_by_date']) ? 'no' : $_GET['do_not_filter_by_date']);
$start_date = (isset($_GET['start_date']) ? $_GET['start_date'] : "");
$end_date   = (isset($_GET['end_date']  ) ? $_GET['end_date']   : "");

$show_only_unpaid = (empty($_GET['show_only_unpaid']) ? "no" : $_GET['show_only_unpaid']);

if ($_GET['stage'] == 2) {
    $export = new Export(Destination::STRING_RETURN);
    $export->setFormat('pdf');
    $export->setModule('statement');
    if (empty($biller_id)) {
        $refresh_redirect = "<meta http-equiv=\"refresh\" content=\"5;URL=index.php?module=statement&amp;view=index\" />";
        $display_block = "<div class=\"si_message_error\">{$message}</div>";
        $results = [
            "message" => $message,
            "refresh_redirect" => $refresh_redirect,
            "display_block" =>$display_block
        ];
    } else {
        $export->setBiller($biller);
        $export->setCustomerId($customer_id);
        $export->setStartDate($start_date);
        $export->setEndDate($end_date);
        $export->setShowOnlyUnpaid($show_only_unpaid);
        $export->setDoNotFilterByDate($do_not_filter_by_date);
        $pdf_string = $export->execute();

        $email = new Email();
        $email->setBcc($_POST['email_bcc']);
        $email->setBody(trim($_POST['email_notes']));
        $email->setFormat('statement');
        $email->setFrom($_POST['email_from']);
        $email->setFromFriendly($biller['name']);
        $email->setPdfFileName($export->getFileName() . '.pdf');
        $email->setPdfString($pdf_string);
        $email->setSubject($_POST['email_subject']);
        $email->setTo($_POST['email_to']);

        $results = $email->send();
    }
    $smarty->assign('display_block', $results['display_block']);
    $smarty->assign('refresh_redirect', $results['refresh_redirect']);

} else if ($_GET['stage'] == 3) {
    // stage 3 = assemble email and send
    $message = "Invalid routing to stage 3 of email processing. Probably a process error.";
    $error = true;
}

$smarty->assign('error'   , ($error ? "1":"0"));
$smarty->assign('message' , $message);
$smarty->assign('biller'  , $biller);
$smarty->assign('customer', $customer);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
