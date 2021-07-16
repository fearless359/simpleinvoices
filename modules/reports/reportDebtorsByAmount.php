<?php

use Inc\Claz\Util;

global $LANG, $menu, $smarty;

Util::directAccessAllowed();

$includePaidInvoices = $_POST['includePaidInvoices'] ?? 'no';
$smarty->assign('includePaidInvoices', $includePaidInvoices);

$smarty->assign('title', $LANG["debtorsByAmountOwed"]);

include "modules/reports/reportDebtorsByAmountData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
