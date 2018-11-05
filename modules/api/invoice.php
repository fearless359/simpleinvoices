<?php
use Inc\Claz\Invoice;
use Inc\Claz\Encode;

//get invoice details

// why hard code invoice number below?
$invoice = Invoice::select('1');

header('Content-type: application/xml');
echo Encode::xml($invoice);
print_r($invoice);
