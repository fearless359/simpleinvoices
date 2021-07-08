<?php

global $billerId, $customerId, $endDate, $filterByDateRange, $includeAllCustomers,
       $LANG, $menu, $startDate, $smarty;

use Inc\Claz\Util;

Util::directAccessAllowed();

include 'library/dateRangePrompt.php';
include 'library/filterByDateRangePrompt.php';
include 'library/includePaidInvoicesPrompt.php';

$billerId = $_POST['billerId'] ?? null;
$smarty->assign('billerId', $billerId);

$customerId = $_POST['customerId'] ?? null;
$smarty->assign('customerId', $customerId);

$smarty->assign('title', $LANG["statementOfInvoices"]);

include 'reportStatementData.php';

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
