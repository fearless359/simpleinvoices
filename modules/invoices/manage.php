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

$dir    = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC";
$sort   = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "id";
$rp     = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25";
$having = (isset($_GET['having'])    ) ? $_GET['having']     : "";
$page   = (isset($_POST['page'])     ) ? $_POST['page']      : "1";
$query  = (isset($_POST['query'])    ) ? $_POST['query']     : null;
$qtype  = (isset($_POST['qtype'])    ) ? $_POST['qtype']     : null;

// If user role is customer or biller, then restrict invoices to those they have access to.
// Make customer access read only. Billers change work only on those invoices generated for them.
$read_only = ($auth_session->role_name == 'customer');

if (!empty($having)) {
    try {
        $pdoDb->setHavings(Invoice::buildHavings($having));
    } catch (PdoDbException $pde) {
        error_log("modules/invoices/manage.php - error: " . $pde->getMessage());
    }
}

$invoices = Invoice::select_all(''     , $sort, $dir, $rp, $page, $qtype, $query);
$count    = Invoice::select_all('count', $sort, $dir, $rp, $page, $qtype, $query);

$smarty->assign('invoices', $invoices);
$smarty->assign('number_of_invoices', $count);
$smarty->assign('read_only', $read_only);


/******************************************************
// Combine access of values to minimize overhead.
$results = Invoice::select_all('count_owing');
$count = $results['count'];
$_POST['count'] = $count;

$total_owing = $results['total_owing'];

$smarty->assign('number_of_invoices', $count);
$smarty->assign('total_owing', $total_owing);

$url = 'index.php?module=invoices&view=xml' . $having;
$smarty->assign('url', $url);
 ***************************************************/

$having = "";
if (isset($_GET['having'])) {
    $having = "&having=" . $_GET['having'];
}
$smarty->assign('get_having', $having);

$smarty->assign('pageActive', "invoice");
$smarty->assign('active_tab', '#money');
