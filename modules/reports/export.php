<?php
/** @noinspection DuplicatedCode */

use Inc\Claz\Export;
use Inc\Claz\PdoDbException;
use Mpdf\Output\Destination;

/*
 *  Script: template.php
 *      invoice export page
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global$module, $path;

// @formatter:off
$billerId          = $_GET['billerId'] ?? null;
$customerId        = $_GET['customerId'] ?? null;
$displayDetail     = $_GET['displayDetail'] ?? "no";
$endDate           = $_GET['endDate'] ?? "";
$fileName          = $_GET['fileName'] ?? "";
$fileType          = $_GET['fileType'] ?? "";
$filterByDateRange = $_GET['filterByDateRange'] ?? "yes";
$format            = $_GET['format'] ?? "file";
$includeAllCustomers = $_GET['includeAllCustomers'] ?? 'no';
$includePaidInvoices = $_GET['includePaidInvoices'] ?? 'yes';
$startDate         = $_GET['startDate'] ?? "";
// @formatter:on

$landscape = false;
switch ($fileName) {

    case 'reportNetIncome':
        $customFlag = $_GET['customFlag'] ?? '0';
        $customFlagLabel = $_GET['customFlagLabel'] ?? '';
        include "modules/reports/reportNetIncomeData.php";
        $params = [
            'customerId' => $customerId,
            'customFlag' => $customFlag,
            'customFlagLabel' => $customFlagLabel
        ];
        break;

    case 'reportSalesByRepresentative':
        $salesRep = $_GET['salesRep'] ?? "";
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
$export->setIncludeAllCustomers($includeAllCustomers);
$export->setIncludePaidInvoices($includePaidInvoices);
$export->setLandscape($landscape);
$export->setModule($module);
$export->setStartDate($startDate);
try {
    $export->execute();
} catch (PdoDbException $pde) {
    exit("modules/reports/export.php Unexpected error: {$pde->getMessage()}");
}
