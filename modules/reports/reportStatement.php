<?php

global $billerId, $customerId, $endDate, $filterByDateRange, $includeAllCustomers,
       $LANG, $menu, $startDate, $smarty;

use Inc\Claz\Util;

Util::directAccessAllowed();
$smarty->assign('showAllReports', $_GET['showAllReports']);

include 'library/dateRangePrompt.php';
include 'library/filterByDateRangePrompt.php';
include 'library/includePaidInvoicesPrompt.php';

// If session role is biller or customer, restrict to assigned biller or customer.
// Otherwise, allow user to select.
if (isset($_SESSION['role_name']) && $_SESSION['role_name'] == 'biller') {
    $billerId = $_SESSION['user_id'];
} else {
    $billerId = $_POST['billerId'] ?? 0;
}
$smarty->assign('billerId', $billerId);

if (isset($_SESSION['role_name']) && $_SESSION['role_name'] == 'customer') {
    $customerId = $_SESSION['user_id'];
} else {
    $customerId = $_POST['customerId'] ?? null;
}
$smarty->assign('customerId', $customerId);

$smarty->assign('title', $LANG["statementOfInvoices"]);

include 'reportStatementData.php';

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
