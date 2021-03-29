<?php

use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
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

$having = isset($_GET['having']) ? $_GET['having'] : "";

// If user role is customer or biller, then restrict invoices to those they have access to.
// Make customer access read only. Billers change work only on those invoices generated for them.
$readOnly = $_SESSION['role_name'] == 'customer';

try {
    $invoices = Invoice::getAllWithHavings($having, '', '', true);
    $data = json_encode(['data' => $invoices]);
    if (file_put_contents("public/data.json", $data) === false) {
        die("Unable to create public/data.json file");
    }

    $smarty->assign('number_of_invoices', count($invoices));
} catch (PdoDbException $pde) {
    exit("modules/invoices/manager.php Unexpected error {$pde->getMessage()}");
}
if (!empty($having)) {
    $having = "&amp;having=" . $having;
}
$smarty->assign('get_having', $having);

$smarty->assign('pageActive', "invoice");
$smarty->assign('activeTab', '#money');
