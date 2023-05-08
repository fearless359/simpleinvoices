<?php

use Inc\Claz\Util;

/*
 * Script: report_sales_by_period.php
 * 	Sales reports by period add page
 *
 * Authors:
 *	 Justin Kelly
 *
 * Last edited:
 * 	 2016-08-16
 *
 * License:
 *	 GPL v3
 *
 * Website:
 * 	https://simpleinvoices.group
 */
global $LANG, $smarty, $pdoDb;

Util::directAccessAllowed();
$smarty->assign('showAllReports', $_GET['showAllReports']);

include 'library/dateRangePrompt.php';

$smarty->assign('title', "{$LANG['expenseUc']} {$LANG['accountUc']} {$LANG['summaryUc']}");
$smarty->assign('title2', "{$LANG['invoiceUc']}/{$LANG['quoteUc']} {$LANG['summaryUc']}");
$smarty->assign('title3', "{$LANG['paymentUc']} {$LANG['summaryUc']}");

include "modules/reports/reportExpenseSummaryData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');
