<?php

use Inc\Claz\Export;
use Inc\Claz\PdoDbException;
use Mpdf\Output\Destination;

/*
 *  Script: template.php
 * 	    invoice export page
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 * 	    https://simpleinvoices.group */
// @formatter:off
$format   = $_GET['format'] ?? "";
$fileType = $_GET['filetype'] ?? "";
$id       = $_GET['id'] ?? "";
// @formatter:on

$export = new Export(Destination::DOWNLOAD);
$export->setFormat($format);
$export->setFileType($fileType);
$export->setPaymentId($id);
$export->setModule('payment');
try {
    $export->execute();
} catch (PdoDbException $pde) {
    exit("modules/export/payment.php Unexpected error: {$pde->getMessage()}");
}
