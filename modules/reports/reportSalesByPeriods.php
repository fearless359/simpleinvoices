<?php

use Inc\Claz\Util;

/*
 * Script: report_sales_by_period.php
 * 	Sales reports by period add page
 *
 * Authors:
 *	 Justin Kelly
 *	Francois Dechery, aka Soif
 *
 * Last edited:
 * 	 2008-05-13
 *
 * License:
 *	 GPL v3
 *
 * Website:
 * 	https://simpleinvoices.group
 */
global $LANG, $smarty;

Util::directAccessAllowed();

include 'modules/reports/reportSalesByPeriodsData.php';

$smarty->assign('title', $LANG["monthlySalesPerYear"]);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
if (!isset($menu)) {
    $menu = true;
}
$smarty->assign('menu', $menu);
$smarty->assign('showReportExportButtons', true);
