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
global $menu, $pdoDb, $smarty;

Util::directAccessAllowed();

/**
 * @return false|string
 */
function firstOfMonth()
{
    return date('Y-m-d', strToTime('first day of this month'));
}

/**
 * @return false|string
 */
function lastOfMonth()
{
    return date('Y-m-d', strToTime('last day of this month'));
}

function custCmp(array $a, array $b): bool
{
    return $b['last_activity_date'] > $a['last_activity_date'];
}

$startDate     = isset($_POST['start_date'])  ? $_POST['start_date']  : firstOfMonth();
$endDate       = isset($_POST['end_date'])    ? $_POST['end_date']    : lastOfMonth();
$customFlag    = isset($_POST['custom_flag']) ? $_POST['custom_flag'] : "";
$customerId    = isset($_POST['customer_id']) ? $_POST['customer_id'] : 0;
$displayDetail = isset($_POST['display_detail']);

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
        error_log('report_net_income: Unable to get customer last activity date. Error: ' . $pde->getMessage());
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
        $totIncome += $invoice->total_period_payments;
    }
}

$smarty->assign('invoices', $invoices);
$smarty->assign('customers', $customers);
$smarty->assign('tot_income', $totIncome);
$smarty->assign('start_date', $startDate);
$smarty->assign('end_date', $endDate);
$smarty->assign('customer_id', $customerId);
$smarty->assign('display_detail', $displayDetail);

$smarty->assign('custom_flag', $customFlag);
$smarty->assign('custom_flag_labels', $customFlagLabels);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
$smarty->assign('menu', $menu);
