<?php

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
$format   = $_GET['format'] ?? "";
$fileType = $_GET['filetype'] ?? "";
$id       = $_GET['id'] ?? "";

$export = new Export(Destination::DOWNLOAD);
$export->setFormat($format);
$export->setFileType($fileType);
$export->setInvoiceId($id);
$export->setModule('invoice');
try {
    $export->execute();
} catch (PdoDbException $pde) {
    exit ("modules/export/invoice.php Unexpected exception: {$pde->getMessage()}");
}
