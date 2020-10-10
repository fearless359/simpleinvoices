<?php /** @noinspection DuplicatedCode */

/*
 * Script: report_sales_by_period.php
 *     Sales reports by period add page
 *
 * Authors:
 *     Justin Kelly
 *
 * Last edited:
 *      2008-05-13
 *
 * License:
 *     GPL v3
 *
 * Website:
 *     https://simpleinvoices.group
 */
global $LANG, $pdoDb, $smarty;

use Inc\Claz\Util;

Util::directAccessAllowed();

$smarty->assign('title', "{$LANG['monthlyUc']} {$LANG['tax']} {$LANG['summaryUc']} {$LANG['per']} {$LANG['yearUc']}");

include 'modules/reports/reportTaxVsSalesByPeriodData.php';

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');
