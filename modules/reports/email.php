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

$billerId            = $_GET['billerId'] ?? null;
$customerId          = $_GET['customerId'] ?? null;
$displayDetail       = $_GET['displayDetail'] ?? "no";
$endDate             = $_GET['endDate'] ?? "";
$fileName            = $_GET['fileName'] ?? "";
$fileType            = $_GET['fileType'] ?? "";
$filterByDateRange   = $_GET['filterByDateRange'] ?? "yes";
$format              = $_GET['format'] ?? "file";
$includeAllCustomers = $_GET['includeAllCustomers'] ?? 'no';
$includePaidInvoices = $_GET['includePaidInvoices'] ?? 'no';
$startDate           = $_GET['startDate'] ?? "";
$stage               = $_GET['stage'] ?? 1;
$title               = $_GET['title'] ?? "";

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

// Note that the $params array is used to supply parameters
// to the "action" parameter of the email form.
switch ($fileName) {
    case 'reportBillerByCustomer':
        include "modules/reports/reportBillerByCustomerData.php";
        $params = [
            'billerId' => $billerId,
            'endDate' => $endDate,
            'fileName' => $fileName,
            'startDate' => $startDate,
            'title' => $title
        ];
        break;

    case 'reportBillerTotal':
        include "modules/reports/reportBillerTotalData.php";
        $params = [
            'endDate' => $endDate,
            'fileName' => $fileName,
            'startDate' => $startDate,
            'title' => $title
        ];
        break;

    case 'reportDebtorsByAmount':
        include "modules/reports/reportDebtorsByAmountData.php";
        $params = [
            'includePaidInvoices' => $includePaidInvoices,
            'fileName' => $fileName,
            'title' => $title
        ];
        break;

    case 'reportDebtorsOwingByCustomer':
        include "modules/reports/reportDebtorsOwingByCustomerData.php";
        $params = [
            'includeAllCustomers' => $includeAllCustomers,
            'endDate' => $endDate,
            'fileName' => $fileName,
            'filterByDateRange' => $filterByDateRange,
            'startDate' => $startDate,
            'title' => $title
        ];
        break;

    case 'reportExpenseAccountByPeriod':
        include "modules/reports/reportExpenseAccountByPeriodData.php";
        $params = [
            'endDate' => $endDate,
            'fileName' => $fileName,
            'startDate' => $startDate,
            'title' => $title
        ];
        break;

    case 'reportExpenseSummary':
        include "modules/reports/reportExpenseSummaryData.php";
        $params = [
            'endDate' => $endDate,
            'fileName' => $fileName,
            'startDate' => $startDate,
            'title' => $title
        ];
        break;

    case 'reportNetIncome':
        $customFlag = $_GET['customFlag'] ?? '0';
        $customFlagLabel = $_GET['customFlagLabel'] ?? '';
        include "modules/reports/reportNetIncomeData.php";
        $params = [
            'billerId' => $billerId,
            'customerId' => $customerId,
            'customFlag' => $customFlag,
            'customFlagLabel' => $customFlagLabel,
            'displayDetail' => $displayDetail,
            'endDate' => $endDate,
            'fileName' => $fileName,
            'filterByDateRange' => $filterByDateRange,
            'startDate' => $startDate,
            'title' => $title
        ];
        break;

    case 'reportPastDue':
        include "modules/reports/reportPastDueData.php";
        $params = [
            'displayDetail' => $displayDetail,
            'fileName' => $fileName,
            'title' => $title
        ];
        break;

    case 'reportProductsSoldByCustomer':
        include "modules/reports/reportProductsSoldByCustomerData.php";
        $params = [
            'customerId' => $customerId,
            'endDate' => $endDate,
            'fileName' => $fileName,
            'startDate' => $startDate,
            'title' => $title
        ];
        break;

    case 'reportProductsSoldTotal':
        include "modules/reports/reportProductsSoldTotalData.php";
        $params = [
            'endDate' => $endDate,
            'fileName' => $fileName,
            'startDate' => $startDate,
            'title' => $title
        ];
        break;

    case 'reportSalesByPeriods':
        $showRates = $_GET['showRates'] ?? "no";
        include "modules/reports/reportSalesByPeriodsData.php";
        $params = [
            'fileName' => $fileName,
            'showRates' => $showRates,
            'title' => $title
        ];
        break;

    case 'reportSalesByRepresentative':
        $salesRep = $_GET['salesRep'] ?? "";
        include "modules/reports/reportSalesByRepresentativeData.php";
        $params = [
            'endDate' => $endDate,
            'fileName' => $fileName,
            'filterByDateRange' => $filterByDateRange,
            'salesRep' => $salesRep,
            'startDate' => $startDate,
            'title' => $title
        ];
        break;

    case 'reportSalesCustomersTotal':
        include "modules/reports/reportSalesCustomersTotalData.php";
        $params = [
            'customerId' => $customerId,
            'endDate' => $endDate,
            'fileName' => $fileName,
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

    case 'reportStatement':
        include "modules/reports/reportStatementData.php";
        $params = [
            'billerId' => $billerId,
            'customerId' => $customerId,
            'endDate' => $endDate,
            'fileName' => $fileName,
            'filterByDateRange' => $filterByDateRange,
            'includePaidInvoices' => $includePaidInvoices,
            'startDate' => $startDate,
            'title' => $title
        ];
        break;

    case 'reportTaxTotal':
        include "modules/reports/reportTaxTotalData.php";
        $params = [
            'endDate' => $endDate,
            'fileName' => $fileName,
            'startDate' => $startDate,
            'title' => $title
        ];
        break;

    default:
        include "modules/reports/{$fileName}Data.php";
        $params = [
            'fileName' => $fileName,
            'title' => $title
        ];
        break;
}
$smarty->assign('params', $params);

if ($stage == 2) {
    $export = new Export(Destination::STRING_RETURN);
    $export->setFormat('pdf');
    $export->setModule('reports');
    if (empty($billerId)) {
        $refreshRedirect = "<meta http-equiv='refresh' content='5;URL=index.php?module=reports&amp;view=index' />";
        $displayBlock = "<div class='si_message_error'>$message</div>";
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
            $export->setFilterByDateRange($filterByDateRange);
            $export->setFormat($format);
            $export->setIncludeAllCustomers($includeAllCustomers);
            $export->setIncludePaidInvoices($includePaidInvoices);
            $export->setModule($module);
            $export->setStartDate($startDate);
            $pdfString = $export->execute();

            $email = new Email();
            $email->setBcc(explode(';', $_POST['emailBcc']));
            $email->setBody(trim($_POST['emailNotes']));
            $email->setFormat('reports');
            if ($_POST['emailFrom'] == $biller['email']) {
                $email->setFrom([$_POST['emailFrom'] => $biller['name']]);
            } else {
                $email->setFrom([$_POST['emailFrom']]);
            }
            $email->setPdfFileName($export->getFileName() . '.pdf');
            $email->setPdfString($pdfString);
            $email->setSubject($_POST['emailSubject']);
            $email->setEmailTo(explode(';', $_POST['emailTo']));
            $results = $email->send();
        } catch (Exception $exp) {
            exit("modules/reports/email.php Unexpected error: {$exp->getMessage()}");
        }
    }
    $smarty->assign('display_block', $results['display_block']);
    $smarty->assign('refresh_redirect', $results['refresh_redirect']);

} elseif ($stage == 3) {
    // stage 3 = assemble email and send
    $message = "Invalid routing to stage 3 of email processing. Probably a process error.";
    $error = true;
}

$smarty->assign('biller', $biller);
$smarty->assign('customer', $customer);

$smarty->assign('error', $error ? "1":"0");
$smarty->assign('message', $message);
$smarty->assign('subject', $title);

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');
