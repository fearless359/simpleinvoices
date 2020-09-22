<?php

use Inc\Claz\Customer;
use Inc\Claz\CustomFlags;
use Inc\Claz\NetIncomeReport;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;

/*
 * Script: net_income_wo_non_income.php Report income excluding non-income funds
 *
 * Authors: Richard Rowley
 *
 * Last edited: 2018-09-23
 *
 * License: GPL v3
 *
 * Website: https://simpleinvoices.group
 */
global $endDate, $menu, $pdoDb, $smarty, $startDate;

Util::directAccessAllowed();

include 'dateRangePrompt.php';

/**
 * Compare last activity dates for sorting
 * @param array $a
 * @param array $b
 * @return bool true if $a['last_activity_date'] > $b['last_activity_date']
 */
function custCmp(array $a, array $b): bool
{
    return $b['last_activity_date'] > $a['last_activity_date'];
}

$customFlag = isset($_POST['customFlag']) ? $_POST['customFlag'] : 0;

var_dump($customFlag);

$customerId = isset($_POST['customerId']) ? intval($_POST['customerId']) : 0;
$displayDetail = isset($_POST['displayDetail']);

$customFlagLabels = CustomFlags::getCustomFlagLabels();

$customers = [];
$rows = Customer::getAll(['no_totals' => true]);

foreach ($rows as $row) {
    $row['last_activity_date'] = '0000-01-01'; // Default to no activity date equivalent
    try {
        $pdoDb->addSimpleWhere('customer_id', $row['id'], 'AND');
        $pdoDb->addSimpleWhere('domain_id', $row['domain_id']);
        $pdoDb->setOrderBy(['last_activity_date', 'D']);
        $pdoDb->setSelectList(['last_activity_date']);

        $custIvs = $pdoDb->request('SELECT', 'invoices');
        if (!empty($custIvs)) {
            $row['last_activity_date'] = substr($custIvs[0]['last_activity_date'], 0, 10);
        }
    } catch (PdoDbException $pde) {
        error_log('reportNetIncome: Unable to get customer last activity date. Error: ' . $pde->getMessage());
    }
    $customers[] = $row;
}
usort($customers, 'custCmp');

$invoices = [];
$totIncome = 0;
if (isset($_POST['submit'])) {
    $netIncome = new NetIncomeReport();
    $invoices = $netIncome->selectRptItems($startDate, $endDate, $customerId, $customFlag);

    foreach ($invoices as $invoice) {
        $totIncome += $invoice->totalPeriodPayments;
    }
}

$smarty->assign('invoices', $invoices);
$smarty->assign('customers', $customers);
$smarty->assign('totIncome', $totIncome);
$smarty->assign('startDate', $startDate);
$smarty->assign('endDate', $endDate);
$smarty->assign('customerId', $customerId);
$smarty->assign('displayDetail', $displayDetail);

$smarty->assign('customFlag', $customFlag);
$smarty->assign('customFlagLabels', $customFlagLabels);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
