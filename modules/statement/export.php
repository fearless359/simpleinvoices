<?php

use Inc\Claz\Export;
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
$billerId          = isset($_GET ['biller_id'])             ? $_GET ['biller_id']             : "";
$customerId        = isset($_GET ['customer_id'])           ? $_GET ['customer_id']           : "";
$startDate         = isset($_GET ['start_date'])            ? $_GET ['start_date']            : "";
$endDate           = isset($_GET ['end_date'])              ? $_GET ['end_date']              : "";
$showOnlyUnpaid    = isset($_GET ['show_only_unpaid'])      ? $_GET ['show_only_unpaid']      : "no";
$doNotFilterByDate = isset($_GET ['do_not_filter_by_date']) ? $_GET ['do_not_filter_by_date'] : "no";
$format            = isset($_GET ['format'])                ? $_GET ['format']                : "";
$fileType          = isset($_GET ['filetype'])              ? $_GET ['filetype']              : "";
// @formatter:on

// get the invoice id
$export = new Export(Destination::DOWNLOAD);
$export->setFormat($format);
$export->setFileType($fileType);
$export->setModule('statement');
$export->setBillerId($billerId);
$export->setCustomerId($customerId);
$export->setStartDate($startDate);
$export->setEndDate($endDate);
$export->setShowOnlyUnpaid($showOnlyUnpaid);
$export->setDoNotFilterByDate($doNotFilterByDate);
$export->execute();
