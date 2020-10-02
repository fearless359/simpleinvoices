<?php

use Inc\Claz\CustomFlags;
use Inc\Claz\Util;

/*
 * Script: net_income_wo_non_income.php Report income excluding non-income funds
 *
 * Authors: Richard Rowley
 *
 * Last edited: 2018-09-23
 *
 * License: GPL v3
 *
 * Website: https://simpleinvoices.group
 */
global $endDate, $LANG, $menu, $pdoDb, $smarty, $startDate;

Util::directAccessAllowed();

include 'library/dateRangePrompt.php';

$customFlag = !empty($_POST['customFlag']) ? intval($_POST['customFlag']) : 0;
$customerId = isset($_POST['customerId']) ? intval($_POST['customerId']) : 0;
$displayDetail = isset($_POST['displayDetail']) ? $_POST['displayDetail'] : 'no';

$customFlagLabels = CustomFlags::getCustomFlagLabels();

$smarty->assign('startDate', $startDate);
$smarty->assign('endDate', $endDate);
$smarty->assign('customerId', $customerId);
$smarty->assign('displayDetail', $displayDetail);

$smarty->assign('customFlag', $customFlag);
$cfLabel = $customFlag > 0 ? $customFlagLabels[$customFlag - 1] : '';
$smarty->assign('customFlagLabel', $cfLabel);
$smarty->assign('customFlagLabels', $customFlagLabels);
$smarty->assign('title', $LANG["netIncomeReport"]);

include "modules/reports/reportNetIncomeReportData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
