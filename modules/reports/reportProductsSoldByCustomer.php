<?php

use Inc\Claz\Util;

global $LANG, $menu, $smarty;

Util::directAccessAllowed();

include 'library/dateRangePrompt.php';

$customerId = isset($_POST['customerId']) ? intval($_POST['customerId']) : 0;
$smarty->assign('customerId', $customerId);

$smarty->assign('title', $LANG["productsSoldCustomerTotal"]);

include "modules/reports/reportProductsSoldByCustomerData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
