<?php

use Inc\Claz\Util;

global $endDate, $LANG, $menu, $smarty, $startDate;

Util::directAccessAllowed();
$smarty->assign('showAllReports', $_GET['showAllReports']);

include 'library/dateRangePrompt.php';

$title = "{$LANG['profitUc']} {$LANG['per']} {$LANG['invoiceUc']} - {$LANG['basedUc']} {$LANG['onLc']} {$LANG['averageUc']} " .
         "{$LANG['productUc']} {$LANG['costUc']} {$LANG['summaryUc']}";

$smarty->assign('title', $title);

include "modules/reports/reportInvoiceProfitData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
