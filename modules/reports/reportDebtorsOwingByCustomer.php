<?php

use Inc\Claz\Util;

global $LANG, $menu, $smarty;

Util::directAccessAllowed();

include "library/dateRangePrompt.php";
$reverseFilterByDateRangeDefault = true;
include 'library/filterByDateRangePrompt.php';
include 'library/includeAllCustomersPrompt.php';

$smarty->assign('title', $LANG["debtorsByAmountOwingCustomer"]);

include "modules/reports/reportDebtorsOwingByCustomerData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
