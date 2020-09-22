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
$billerId       = isset($_GET ['billerId'])       ? $_GET ['billerId']       : null;
$customerId     = isset($_GET ['customerId'])     ? $_GET ['customerId']     : null;
$startDate      = isset($_GET ['startDate'])      ? $_GET ['startDate']      : "";
$endDate        = isset($_GET ['endDate'])        ? $_GET ['endDate']        : "";
$showOnlyUnpaid = isset($_GET ['showOnlyUnpaid']) ? $_GET ['showOnlyUnpaid'] : "no";
$filterByDate   = isset($_GET ['filterByDate'])   ? $_GET ['filterByDate']   : "yes";
$format         = isset($_GET ['format'])         ? $_GET ['format']         : "";
$fileType       = isset($_GET ['filetype'])       ? $_GET ['filetype']       : "";
// @formatter:on

// get the invoice id
$export = new Export(Destination::DOWNLOAD);
$export->setFormat($format);
$export->setFileType($fileType);
$export->setFileName('statement');
$export->setBillerId($billerId);
$export->setCustomerId($customerId);
$export->setStartDate($startDate);
$export->setEndDate($endDate);
$export->setShowOnlyUnpaid($showOnlyUnpaid);
$export->setFilterByDate($filterByDate);
try {
    $export->execute();
} catch (PdoDbException $pde) {
    exit("modules/statement/export.php Unexpected error: {$pde->getMessage()}");
}
