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

include 'library/dateRangePrompt.php';

$smarty->assign('title', "{$LANG['expenseUc']} {$LANG['account']} {$LANG['summary']}");
$smarty->assign('title2', "{$LANG['invoiceUc']}/{$LANG['quoteUc']} {$LANG['summary']}");
$smarty->assign('title3', "{$LANG['paymentUc']} {$LANG['summary']}");

include "modules/reports/reportExpenseSummaryData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');
