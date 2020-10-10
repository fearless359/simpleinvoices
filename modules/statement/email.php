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

if (empty($_GET['billerId'])) {
    $biller = Biller::getDefaultBiller();
} else {
    $biller = Biller::getOne($_GET['billerId']);
}

if (empty($biller)) {
    $error = true;
    $message = "Must specify a biller to send an e-mail";
    $billerId = null;
} else {
    $billerId = $biller['id'];
}

if (empty($_GET['customerId'])) {
    $customer = ["id" => 0, "name" => "All"];
} else {
    $customer = Customer::getOne($_GET['customerId']);
}
$customerId = $customer['id'];

$filterByDateRange = empty($_GET['filterByDateRange']) ? 'yes' : $_GET['filterByDateRange'];
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : "";
$endDate = isset($_GET['endDate']  ) ? $_GET['endDate']   : "";

$showOnlyUnpaid = empty($_GET['showOnlyUnpaid']) ? "no" : $_GET['showOnlyUnpaid'];

if ($_GET['stage'] == 2) {
    $export = new Export(Destination::STRING_RETURN);
    $export->setFormat('pdf');
    $export->setModule('statement');
    if (empty($billerId)) {
        $refreshRedirect = "<meta http-equiv='refresh' content='5;URL=index.php?module=statement&amp;view=index' />";
        $displayBlock = "<div class='si_message_error'>{$message}</div>";
        $results = [
            "message" => $message,
            "refresh_redirect" => $refreshRedirect,
            "display_block" =>$displayBlock
        ];
    } else {
        try {
            $export->setBiller($biller);
            $export->setCustomerId($customerId);
            $export->setStartDate($startDate);
            $export->setEndDate($endDate);
            $export->setShowOnlyUnpaid($showOnlyUnpaid);
            $export->setFilterByDateRange($filterByDateRange);
            $pdfString = $export->execute();

            $email = new Email();
            $email->setBcc($_POST['emailBcc']);
            $email->setBody(trim($_POST['emailNotes']));
            $email->setFormat('statement');
            $email->setFrom($_POST['emailFrom']);
            $email->setFromFriendly($biller['name']);
            $email->setPdfFileName($export->getFileName() . '.pdf');
            $email->setPdfString($pdfString);
            $email->setSubject($_POST['emailSubject']);
            $email->setEmailTo($_POST['emailTo']);

            $results = $email->send();
        } catch (Exception $exp) {
            exit("modules/statement/email.php Unexpected error: {$exp->getMessage()}");
        }
    }
    $smarty->assign('display_block', $results['display_block']);
    $smarty->assign('refresh_redirect', $results['refresh_redirect']);

} elseif ($_GET['stage'] == 3) {
    // stage 3 = assemble email and send
    $message = "Invalid routing to stage 3 of email processing. Probably a process error.";
    $error = true;
}

$smarty->assign('error'   , $error ? "1":"0");
$smarty->assign('message' , $message);
$smarty->assign('biller'  , $biller);
$smarty->assign('customer', $customer);

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');
