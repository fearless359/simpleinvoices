<?php

global $LANG, $menu, $smarty;

use Inc\Claz\Util;

Util::directAccessAllowed();

include 'library/dateRangePrompt.php';

$smarty->assign('title', $LANG["productsSoldTotal"]);

include "modules/reports/reportProductsSoldTotalData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
