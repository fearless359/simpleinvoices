<?php
use Inc\Claz\Invoice;
use Inc\Claz\Encode;
use Inc\Claz\PdoDbException;

//get invoice details

// why hard code invoice number below?
try {
    $invoice = Invoice::getOne('1');

    header('Content-type: application/xml');
    echo Encode::xml($invoice);
    print_r($invoice);
} catch (PdoDbException $pde) {
    exit("modules/api/invoice.php - Unexpected database error: {$pde->getMessage()}");
}

