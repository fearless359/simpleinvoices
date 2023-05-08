<?php

use Inc\Claz\Util;

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

Util::directAccessAllowed();
$smarty->assign('showAllReports', $_GET['showAllReports']);

include 'library/dateRangePrompt.php';

$smarty->assign('title', "{$LANG['expenseUc']} {$LANG['accountsUc']} {$LANG['by']} {$LANG['periodUc']}");
$smarty->assign('showAllReports', $_GET['showAllReports']);

include "modules/reports/reportExpenseAccountByPeriodData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');
