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

// Combine access of values to minimize overhead.
$results = Invoice::select_all('count_owing');
$count = $results['count'];
$_POST['count'] = $count;

$total_owing = $results['total_owing'];

$smarty->assign('number_of_invoices', $count);
$smarty->assign('total_owing', $total_owing);

$having = "";
if (isset($_GET['having'])) {
    $having = "&having=" . $_GET['having'];
}
$url = 'index.php?module=invoices&view=xml' . $having;
$smarty->assign('url', $url);

$smarty->assign('pageActive', "invoice");
$smarty->assign('active_tab', '#money');
