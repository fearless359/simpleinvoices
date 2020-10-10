<?php
/** @noinspection DuplicatedCode */

use Inc\Claz\Export;
use Inc\Claz\PdoDbException;
use Mpdf\Output\Destination;

/*
 * Script: template.php
 * invoice export page
 *
 * License:
 * GPL v3 or above
 *
 * Website:
 * https://simpleinvoices.group */
global$module, $path;

// @formatter:off
$billerId          = isset($_GET['billerId'])          ? intval($_GET['billerId'])   : null;
$customerId        = isset($_GET['customerId'])        ? intval($_GET['customerId']) : null;
$displayDetail     = isset($_GET['displayDetail'])     ? $_GET['displayDetail']      : "no";
$endDate           = isset($_GET['endDate'])           ? $_GET['endDate']            : "";
$fileName          = isset($_GET['fileName'])          ? $_GET['fileName']           : "";
$fileType          = isset($_GET['fileType'])          ? $_GET['fileType']           : "";
$filterByDateRange = isset($_GET['filterByDateRange']) ? $_GET['filterByDateRange']  : "yes";
$format            = isset($_GET['format'])            ? $_GET['format']             : "file";
$showOnlyUnpaid    = isset($_GET['showOnlyUnpaid'])    ? $_GET['showOnlyUnpaid']     : "no";
$startDate         = isset($_GET['startDate'])         ? $_GET['startDate']          : "";
// @formatter:on

$landscape = false;
switch ($fileName) {
    case 'reportDebtorsByAmount':
        $includePaidInvoices = isset($_GET['includePaidInvoices']) ? $_GET['includePaidInvoices'] : 'yes';
        include "modules/reports/reportDebtorsByAmountData.php";
        $params = [
            'includePaidInvoices' => $includePaidInvoices
        ];
        break;

    case 'reportDebtorsOwingByCustomer':
        $includeAllCustomers = isset($_GET['includeAllCustomers']) ? $_GET['includeAllCustomers'] : 'no';
        include "modules/reports/reportDebtorsOwingByCustomerData.php";
        $params = [
            'includeAllCustomers' => $includeAllCustomers
        ];
        break;

    case 'reportNetIncome':
        $customFlag = isset($_GET['customFlag']) ? $_GET['customFlag'] : '0';
        $customFlagLabel = isset($_GET['customFlagLabel']) ? $_GET['customFlagLabel'] : '';
        include "modules/reports/reportNetIncomeData.php";
        $params = [
            'customerId' => $customerId,
            'customFlag' => $customFlag,
            'customFlagLabel' => $customFlagLabel
        ];
        break;

    case 'reportProductsSoldByCustomer':
        include "modules/reports/reportProductsSoldByCustomerData.php";
        $params = [
            'customerId' => $customerId,
        ];
        break;

    case 'reportSalesByRepresentative':
        $salesRep = isset($_GET['salesRep']) ? $_GET['salesRep'] : "";
        include "modules/reports/reportSalesByRepresentativeData.php";
        break;

    default:
        include "modules/reports/{$fileName}Data.php";
        break;
}

// get the invoice id
$export = new Export(Destination::DOWNLOAD);
$export->setBillerId($billerId);
$export->setCustomerId($customerId);
$export->setDisplayDetail($displayDetail);
$export->setEndDate($endDate);
$export->setFileName($fileName);
$export->setFileType($fileType);
$export->setFilterByDateRange($filterByDateRange);
$export->setFormat($format);
$export->setLandscape($landscape);
$export->setModule($module);
$export->setShowOnlyUnpaid($showOnlyUnpaid);
$export->setStartDate($startDate);
try {
    $export->execute();
} catch (PdoDbException $pde) {
    exit("modules/reports/export.php Unexpected error: {$pde->getMessage()}");
}
