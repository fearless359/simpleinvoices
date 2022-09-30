<?php

use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;

try {
    $invoiceId = $_GET['id'] ?? 0;
    $cronId = $_GET['cronId'] ?? 0;
    Invoice::recur($invoiceId, $cronId);
} catch (PdoDbException $pde) {
    error_log("recur.php Invoice::recur() exception: {$pde->getMessage()}");
}
