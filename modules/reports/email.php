<?php /** @noinspection DuplicatedCode */

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
global $module, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$error = false;
$message = "Unable to process email request.";

$billerId       = isset($_GET['billerId'])       ? $_GET['billerId']       : null;
$customerId     = isset($_GET['customerId'])     ? $_GET['customerId']     : null;
$displayDetail  = isset($_GET['displayDetail'])  ? $_GET['displayDetail']  : "no";
$endDate        = isset($_GET['endDate'])        ? $_GET['endDate']        : "";
$fileName       = isset($_GET['fileName'])       ? $_GET['fileName']       : "";
$fileType       = isset($_GET['fileType'])       ? $_GET['fileType']       : "";
$filterByDate   = isset($_GET['filterByDate'])   ? $_GET['filterByDate']   : "no";
$format         = isset($_GET['format'])         ? $_GET['format']         : "file";
$showOnlyUnpaid = isset($_GET['showOnlyUnpaid']) ? $_GET['showOnlyUnpaid'] : "no";
$startDate      = isset($_GET['startDate'])      ? $_GET['startDate']      : "";
$stage          = isset($_GET['stage'])          ? $_GET['stage']          : 1;
$title          = isset($_GET['title'])          ? $_GET['title']          : "";

if (empty($billerId)) {
    $biller = Biller::getDefaultBiller();
} else {
    $biller = Biller::getOne($billerId);
}

if (empty($biller)) {
    $error = true;
    $message = "No biller specified. Make sure 'Default Biller' is set in SI Defaults.";
    $billerId = null;
}
$billerId = $biller['id'];

if (empty($customerId)) {
    $customer = ["id" => 0, "name" => ""];
    $customerId = 0;
} else {
    $customer = Customer::getOne($customerId);
}

switch ($fileName) {
    case 'reportNetIncome':
        $customFlag = isset($_GET['customFlag']) ? $_GET['customFlag'] : '0';
        $customFlagLabel = isset($_GET['customFlagLabel']) ? $_GET['customFlagLabel'] : '';
        include "modules/reports/reportNetIncomeReportData.php";
        $params = [
            'billerId' => $billerId,
            'customerId' => $customerId,
            'customFlag' => $customFlag,
            'customFlagLabel' => $customFlagLabel,
            'displayDetail' => $displayDetail,
            'endDate' => $endDate,
            'fileName' => $fileName,
            'filterByDate' => $filterByDate,
            'showOnlyUnpaid' => $showOnlyUnpaid,
            'startDate' => $startDate,
            'title' => $title
        ];
        break;

    case 'reportSalesTotal':
        include "modules/reports/reportSalesTotalData.php";
        $params = [
            'endDate' => $endDate,
            'fileName' => $fileName,
            'startDate' => $startDate,
            'title' => $title
        ];
        break;

    case 'reportSalesByPeriods':
        $showRates = isset($_GET['showRates']) ? $_GET['showRates'] : "no";
        include "modules/reports/reportSalesByPeriodsData.php";
        $params = [
            'fileName' => $fileName,
            'showRates' => $showRates,
            'title' => $title
        ];
        break;

    default:
        exit("Undefined fileName");
}
$smarty->assign('params', $params);

if ($stage == 2) {
    $export = new Export(Destination::STRING_RETURN);
    $export->setFormat('pdf');
    $export->setModule('reports');
    if (empty($billerId)) {
        $refreshRedirect = "<meta http-equiv='refresh' content='5;URL=index.php?module=reports&amp;view=index' />";
        $displayBlock = "<div class='si_message_error'>{$message}</div>";
        $results = [
            "message" => $message,
            "refresh_redirect" => $refreshRedirect,
            "display_block" =>$displayBlock
        ];
    } else {
        try {
            $export->setBillerId($billerId);
            $export->setCustomerId($customerId);
            $export->setDisplayDetail($displayDetail);
            $export->setEndDate($endDate);
            $export->setFileName($fileName);
            $export->setFileType($fileType);
            $export->setFilterByDate($filterByDate);
            $export->setFormat($format);
            $export->setModule($module);
            $export->setShowOnlyUnpaid($showOnlyUnpaid);
            $export->setStartDate($startDate);
            $pdfString = $export->execute();

            $email = new Email();
            $email->setBcc($_POST['emailBcc']);
            $email->setBody(trim($_POST['emailNotes']));
            $email->setFormat('reports');
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

} elseif ($stage == 3) {
    // stage 3 = assemble email and send
    $message = "Invalid routing to stage 3 of email processing. Probably a process error.";
    $error = true;
}

$smarty->assign('error'   , $error ? "1":"0");
$smarty->assign('message' , $message);
$smarty->assign('biller'  , $biller);
$smarty->assign('customer', $customer);
$smarty->assign('subject' , $title);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
