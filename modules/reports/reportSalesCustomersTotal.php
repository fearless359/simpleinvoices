<?php

use Inc\Claz\Util;

global $endDate, $LANG, $menu, $pdoDb, $smarty, $startDate;

Util::directAccessAllowed();

include 'library/dateRangePrompt.php';

$customerId = isset($_POST['customerId']) ? intval($_POST['customerId']) : 0;

$smarty->assign('startDate', $startDate);
$smarty->assign('endDate', $endDate);
$smarty->assign('customerId', $customerId);

$smarty->assign('title', $LANG["totalSalesByCustomer"]);

include 'reportSalesCustomersTotalData.php';

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');
if (!isset($menu)) {
    $menu = true;
}
$smarty->assign('menu', $menu);
