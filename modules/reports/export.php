<?php

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
global $module;

// @formatter:off
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
// @formatter:on

switch ($fileName) {
    case 'reportNetIncome':
        $customFlag = isset($_GET['customFlag']) ? $_GET['customFlag'] : '0';
        $customFlagLabel = isset($_GET['customFlagLabel']) ? $_GET['customFlagLabel'] : '';
        include "modules/reports/reportNetIncomeReportData.php";
        $params = [
            'customerId' => $customerId,
            'customFlag' => $customFlag,
            'customFlagLabel' => $customFlagLabel
        ];
        break;

    case 'reportSalesByPeriods':
        include "modules/reports/reportSalesByPeriodsData.php";
        break;

    case 'reportSalesTotal':
        include "modules/reports/reportSalesTotalData.php";
        break;

    default:
        exit("Undefined fileName");
}

// get the invoice id
$export = new Export(Destination::DOWNLOAD);
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
try {
    $export->execute();
} catch (PdoDbException $pde) {
    exit("modules/reports/export.php Unexpected error: {$pde->getMessage()}");
}
