<?php

use Inc\Claz\Customer;
use Inc\Claz\NetIncomeReport;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;

global $customerId, $customFlag, $endDate, $pdoDb, $smarty, $startDate;

Util::directAccessAllowed();

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

$customers = [];
$rows = Customer::getAll(['noTotals' => true]);
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
if (isset($_POST['submit']) || isset($_GET['format'])) {
    $invoices = NetIncomeReport::selectRptItems($startDate, $endDate, $customerId, $customFlag);
    foreach ($invoices as $invoice) {
        $totIncome += $invoice->totalPeriodPayments;
    }
}

$smarty->assign('invoices', $invoices);
$smarty->assign('customers', $customers);
$smarty->assign('totIncome', $totIncome);
