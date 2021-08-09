<?php

use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;

try {
    Invoice::recur($_GET['id']);
} catch (PdoDbException $pde) {
    error_log("recur.php Invoice::recur() exception: {$pde->getMessage()}");
}

