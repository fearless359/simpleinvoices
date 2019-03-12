<?php

use Inc\Claz\Export;

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
$format    = (isset($_GET['format']  ) ? $_GET['format']   : "");
$file_type = (isset($_GET['filetype']) ? $_GET['filetype'] : "");
$id        = (isset($_GET['id']      ) ? $_GET['id']       : "");

$export = new Export(Mpdf\Output\Destination::DOWNLOAD);
$export->setFormat($format);
$export->setFileType($file_type);
$export->setId($id);
$export->setModule('invoice');
$export->execute();
