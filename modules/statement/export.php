<?php

use Inc\Claz\Export;

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
$biller_id             = (isset($_GET ['biller_id'])             ? $_GET ['biller_id']             : "");
$customer_id           = (isset($_GET ['customer_id'])           ? $_GET ['customer_id']           : "");
$start_date            = (isset($_GET ['start_date'])            ? $_GET ['start_date']            : "");
$end_date              = (isset($_GET ['end_date'])              ? $_GET ['end_date']              : "");
$show_only_unpaid      = (isset($_GET ['show_only_unpaid'])      ? $_GET ['show_only_unpaid']      : "no");
$do_not_filter_by_date = (isset($_GET ['do_not_filter_by_date']) ? $_GET ['do_not_filter_by_date'] : "no");
$format                = (isset($_GET ['format'])                ? $_GET ['format']                : "");
$file_type             = (isset($_GET ['filetype'])              ? $_GET ['filetype']              : "");

// get the invoice id
$export = new Export(Mpdf\Output\Destination::DOWNLOAD);
$export->setFormat($format);
$export->setFileType($file_type);
$export->setModule('statement');
$export->setBillerId($biller_id);
$export->setCustomerId($customer_id);
$export->setStartDate($start_date);
$export->setEndDate($end_date);
$export->setShowOnlyUnpaid($show_only_unpaid);
$export->setDoNotFilterByDate($do_not_filter_by_date);
$export->execute();
// @formatter:on
