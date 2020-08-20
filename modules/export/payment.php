<?php

use Inc\Claz\Export;
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
$format   = isset($_GET['format']  ) ? $_GET['format']   : "";
$fileType = isset($_GET['filetype']) ? $_GET['filetype'] : "";
$recId    = isset($_GET['id']      ) ? $_GET['id']       : "";
// @formatter:on

$export = new Export(Destination::DOWNLOAD);
$export->setFormat($format);
$export->setFileType($fileType);
$export->setRecId($recId);
$export->setModule('payment');
$export->execute();
