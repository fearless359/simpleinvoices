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
// @formatter:off
$billerId       = isset($_GET['billerId'])       ? $_GET['billerId']       : "";
$customerId     = isset($_GET['customerId'])     ? $_GET['customerId']     : "";
$displayDetail  = isset($_GET['displayDetail'])  ? $_GET['displayDetail']  : "no";
$filterByDate   = isset($_GET['filterByDate'])   ? $_GET['filterByDate']   : "no";
$endDate        = isset($_GET['endDate'])        ? $_GET['endDate']        : "";
$reportFileType = isset($_GET['reportFileType']) ? $_GET['reportFileType'] : "";
$reportFormat   = isset($_GET['reportFormat'])   ? $_GET['reportFormat']   : "file";
$reportFileName = isset($_GET['reportFileName']) ? $_GET['reportFileName'] : "";
$showOnlyUnpaid = isset($_GET['showOnlyUnpaid']) ? $_GET['showOnlyUnpaid'] : "no";
$startDate      = isset($_GET['startDate'])      ? $_GET['startDate']      : "";
// @formatter:on

// get the invoice id
$export = new Export(Destination::DOWNLOAD);
$export->setBillerId($billerId);
$export->setCustomerId($customerId);
$export->setDisplayDetail($displayDetail);
$export->setFilterByDate($filterByDate);
$export->setEndDate($endDate);
$export->setReportFileType($reportFileType);
$export->setReportFormat($reportFormat);
$export->setReportFileName($reportFileName);
$export->setShowOnlyUnpaid($showOnlyUnpaid);
$export->setStartDate($startDate);
try {
    $export->execute();
} catch (PdoDbException $pde) {
    exit("modules/reports/export.php Unexpected error: {$pde->getMessage()}");
}
