<?php
global $module, $smarty, $view;

use Inc\Claz\CheckPermission;

$allReports = [
    'reportBillerByCustomer',
    'reportBillerTotal',
    'reportDatabaseLog',
    'reportDebtorsAgingTotal',
    'reportDebtorsByAging',
    'reportDebtorsByAmount',
    'reportDebtorsOwingByCustomer',
    'reportExpenseAccountByPeriod',
    'reportExpenseSummary',
    'reportInvoiceProfit',
    'reportNetIncome',
    'reportPastDue',
    'reportProductsSoldByCustomer',
    'reportProductsSoldTotal',
    'reportSalesByPeriods',
    'reportSalesByRepresentative',
    'reportSalesCustomersTotal',
    'reportSalesTotal',
    'reportStatement',
    'reportTaxTotal',
    'reportTaxVsSalesByPeriod'
];

$reports = [];
$showAllReports = "1";
foreach ($allReports as $report) {
    if (CheckPermission::isAllowed($module, $report)) {
        $reports[] = $report;
    } else {
        $showAllReports = "0";
    }
}

$smarty->assign('reports', $reports);
$smarty->assign('showAllReports', $showAllReports);

if (isset($_SESSION['role_name'])) {
    if ($_SESSION['role_name'] == 'biller') {
        $smarty->assign('billerId', $_SESSION['user_id']);
    } elseif ($_SESSION['role_name'] == 'customer') {
        $smarty->assign('customerId', $_SESSION['user_id']);
    }
}

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');
