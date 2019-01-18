<?php

use Inc\Claz\Invoice;
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
Util::isAccessAllowed();

$having = (isset($_GET['having'])) ? $_GET['having'] : "";

// If user role is customer or biller, then restrict invoices to those they have access to.
// Make customer access read only. Billers change work only on those invoices generated for them.
$read_only = ($auth_session->role_name == 'customer');

$invoices = Invoice::getAllWithHavings($having, '', '', true);
$data = json_encode(array('data' => $invoices));
if (file_put_contents("public/data.json", $data) === false) {
    die("Unable to create public/data.json file");
}

$smarty->assign('number_of_invoices', count($invoices));

if (!empty($having)) {
    $having = "&amp;having=" . $having;
}
$smarty->assign('get_having', $having);

$smarty->assign('pageActive', "invoice");
$smarty->assign('active_tab', '#money');
