<?php

global $endDate, $LANG, $menu, $smarty, $startDate;

use Inc\Claz\Util;

Util::directAccessAllowed();

include 'library/dateRangePrompt.php';

$smarty->assign('title', $LANG["totalTaxes"]);

include "modules/reports/reportTaxTotalData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
