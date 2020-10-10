<?php

use Inc\Claz\Util;

/*
 * Script: reportPastDue.php collecting past due information.
 * Author: Richard Rowley
 */
global $LANG, $menu, $smarty;

Util::directAccessAllowed();

include 'library/displayDetailPrompt.php';

$smarty->assign('title', "30 {$LANG['daysUc']} {$LANG['orLc']} {$LANG['more']} {$LANG['pastPueReport']}");

include "modules/reports/reportPastDueData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
