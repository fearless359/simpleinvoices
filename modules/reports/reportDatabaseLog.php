<?php

use Inc\Claz\Util;

global $LANG, $pdoDb, $smarty;

Util::directAccessAllowed();

include "library/dateRangePrompt.php";
$smarty->assign('showAllReports', $_GET['showAllReports']);

$smarty->assign('title', "{$LANG['databaseLog']} {$LANG['reportUc']}");

include "modules/reports/reportDatabaseLogData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
