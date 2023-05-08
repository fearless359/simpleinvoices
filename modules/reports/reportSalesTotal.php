<?php

global $endDate, $LANG, $menu, $startDate, $smarty;

use Inc\Claz\Util;

Util::directAccessAllowed();
$smarty->assign('showAllReports', $_GET['showAllReports']);

include 'library/dateRangePrompt.php';

include 'modules/reports/reportSalesTotalData.php';

$smarty->assign('title', $LANG["totalSales"]);

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');
if (!isset($menu)) {
    $menu = true;
}
$smarty->assign('menu', $menu);
