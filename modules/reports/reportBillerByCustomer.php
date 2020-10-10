<?php

use Inc\Claz\Util;

global $LANG, $menu, $smarty;

Util::directAccessAllowed();

include 'library/dateRangePrompt.php';

$billerId = isset($_POST['billerId']) ? intval($_POST['billerId']) : 0;
$smarty->assign('billerId', $billerId);

$smarty->assign('title', $LANG["billerSalesByCustomerTotals"]);

include "modules/reports/reportBillerByCustomerData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
