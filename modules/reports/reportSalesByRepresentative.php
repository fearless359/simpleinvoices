<?php

use Inc\Claz\Util;

/*
 *  Script:
 *      Sales by Representative report
 *
 *  Authors:
 *      Justin Kelly
 *
 *  Last edited:
 *      2018-10-19 by Richard Rowley
 *
 *  License:
 *      GPL v3 or later
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $endDate, $LANG, $menu, $smarty, $startDate;

Util::directAccessAllowed();

include 'library/dateRangePrompt.php';
include 'library/filterByDateRangePrompt.php';

$salesRep = isset($_POST['salesRep']) ? $_POST['salesRep'] : "";
$smarty->assign('salesRep', $salesRep);

$smarty->assign('title', "{$LANG['salesRepresentative']} {$LANG['reportUc']}");

include "modules/reports/reportSalesByRepresentativeData.php";

$smarty->assign('pageActive', 'report' );
$smarty->assign('activeTab', '#home' );
if (!isset($menu)) {
    $menu = true;
}
$smarty->assign('menu', $menu );
