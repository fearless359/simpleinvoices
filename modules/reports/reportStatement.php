<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;

/*
 * Script: report_sales_by_period.php
 * Sales reports by period add page
 *
 * Authors:
 * Justin Kelly
 *
 * Last edited:
 *  2017-12-20 Richard Rowley
 *
 * License:
 * GPL v3
 *
 * Website:
 * https://simpleinvoices.group
 */
global $menu, $pdoDb, $smarty;

Util::directAccessAllowed();

/**
 * @return string first of month date.
 */
function firstOfMonth(): string
{
    return date("Y-m-d", strtotime('01-01-' . date('Y') . ' 00:00:00'));
}

/**
 * @return string end of month date.
 */
function lastOfMonth(): string
{
    return date("Y-m-d", strtotime('31-12-' . date('Y') . ' 00:00:00'));
}

$startDate = $_POST['startDate'] ?? firstOfMonth();
$endDate = $_POST['endDate'] ?? lastOfMonth();
$billerId = $_POST['billerId'] ?? null;
$customerId = $_POST['customerId'] ?? null;

$showOnlyUnpaid = "no";
$filterByDateRange = "yes";
$invoices = [];
$statement = ["total" => 0, "owing" => 0, "paid" => 0];

if (isset($_POST['submit'])) {
    try {
        $havings = [];
        if (isset($_POST['filterByDateRange'])) {
            $filterByDateRange = "yes";
            $havings[] = ["date_between" => [$startDate, $endDate]];
        } else {
            $filterByDateRange = "no";
        }

        if (isset($_POST['showOnlyUnpaid'])) {
            $showOnlyUnpaid = "yes";
            $havings[] = ["money_owed" => ''];
        } else {
            $showOnlyUnpaid = "no";
        }

        if (!empty($billerId)) {
            $pdoDb->addSimpleWhere("biller_id", $billerId, "AND");
        }
        if (!empty($customerId)) {
            $pdoDb->addSimpleWhere("customer_id", $customerId, "AND");
        }

        $invoices = Invoice::getAllWithHavings($havings, "date", "desc");
        foreach ($invoices as $row) {
            if ($row ['status'] > 0) {
                $statement ['total'] += $row ['total'];
                $statement ['owing'] += $row ['owing'];
                $statement ['paid'] += $row ['paid'];
            }
        }
    } catch (PdoDbException $pde) {
        error_log("modules/statement/index.php - error: " . $pde->getMessage());
    }
}

// @formatter:off
$billers         = Biller::getAll(true);
$billerCount     = count($billers);
$customers       = Customer::getAll(['enabledOnly' => true]);
$customerCount   = count($customers);

if (empty($billerId)) {
    $billerDetails = [];
} else {
    $billerDetails   = Biller::getOne($billerId);
}

if (empty($customerId)) {
    $customerDetails = [];
} else {
    $customerDetails = Customer::getOne($customerId);
}

$smarty->assign('billerId'         , $billerId);
$smarty->assign('billers'          , $billers);
$smarty->assign('biller_count'     , $billerCount);
$smarty->assign('billerDetails'    , $billerDetails);
$smarty->assign('customerId'       , $customerId);
$smarty->assign('customers'        , $customers);
$smarty->assign('customerCount'    , $customerCount);
$smarty->assign('customerDetails'  , $customerDetails);
$smarty->assign('showOnlyUnpaid'   , $showOnlyUnpaid);
$smarty->assign('filterByDateRange', $filterByDateRange);

$smarty->assign('invoices'  , $invoices);
$smarty->assign('statement' , $statement);
$smarty->assign('startDate' , $startDate);
$smarty->assign('endDate'   , $endDate);

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
