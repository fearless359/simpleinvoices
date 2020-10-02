<?php

global $endDate, $LANG, $menu, $startDate, $smarty;

include 'library/dateRangePrompt.php';

include 'modules/reports/reportSalesTotalData.php';

$smarty->assign('startDate', $startDate);
$smarty->assign('endDate', $endDate);
$smarty->assign('title', $LANG["totalSales"]);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
if (!isset($menu)) {
    $menu = true;
}
$smarty->assign('menu', $menu);
