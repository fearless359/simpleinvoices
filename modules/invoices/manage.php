<?php

use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

/*
 * Script: manage.php
 * Manage Invoices page
 *
 * License:
 * GPL v2 or above
 *
 * Website:
 * https://simpleinvoices.group
 */
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$having = $_GET['having'] ?? "";

// If user role is customer or biller, then restrict invoices to those they have access to.
// Make customer access read only. Billers change work only on those invoices generated for them.
$readOnly = isset($_SESSION['role_name']) && $_SESSION['role_name'] == 'customer';

try {
    if (isset($_GET['showAll'])) {
        $invoiceDisplayDays = 0;
    } else {
        $invoiceDisplayDays = SystemDefaults::getInvoiceDisplayDays();
    }
    $smarty->assign("invoiceDisplayDays", $invoiceDisplayDays);

    $invoices = Invoice::getAllWithHavings($having, '', '', true, false,
                                           $invoiceDisplayDays);
    $data = json_encode(['data' => mb_convert_encoding($invoices, 'UTF-8')]);
    if (file_put_contents("public/data.json", $data) === false) {
        error_log("modules/invoices/manage.php Unable to store json data.");
        exit("Unable to process request. See error log for details.");
    }

    $smarty->assign('number_of_invoices', count($invoices));
} catch (PdoDbException $pde) {
    error_log("modules/invoices/manage.php Unexpected error {$pde->getMessage()}");
    exit("Unable to process request. See error log for details.");
}
if (!empty($having)) {
    $having = "&amp;having=" . $having;
}
$smarty->assign('get_having', $having);

$smarty->assign('pageActive', "invoice");
$smarty->assign('activeTab', '#money');
