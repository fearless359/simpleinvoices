<?php

use Inc\Claz\Util;

global $LANG, $menu, $smarty;

Util::directAccessAllowed();

include 'library/dateRangePrompt.php';

$billerId = $_SESSION['role_name'] == 'biller' ? intval($_SESSION['user_id']) : 0;
$smarty->assign('billerId', $billerId);
$smarty->assign('showAllReports', $_GET['showAllReports']);

$smarty->assign('title', $LANG["billerSalesByCustomerTotals"]);

include "modules/reports/reportBillerByCustomerData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
