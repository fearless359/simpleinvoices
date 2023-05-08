<?php

use Inc\Claz\Util;

global $LANG, $menu, $smarty;

Util::directAccessAllowed();

$smarty->assign('title', $LANG["totalByAgingPeriods"]);
$smarty->assign('showAllReports', $_GET['showAllReports']);

include "modules/reports/reportDebtorsAgingTotalData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);

