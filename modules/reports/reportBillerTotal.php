<?php

global $LANG, $menu, $smarty;

include 'library/dateRangePrompt.php';

$smarty->assign('title', "{$LANG['billerUc']} {$LANG['salesUc']} {$LANG['totalUc']}");

include "modules/reports/reportBillerTotalData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
