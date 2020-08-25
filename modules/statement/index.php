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
function firstOfMonth() {
    return date ( "Y-m-d", strtotime ( '01-01-' . date ( 'Y' ) . ' 00:00:00' ) );
}

/**
 * @return string end of month date.
 */
function lastOfMonth() {
    return date ( "Y-m-d", strtotime ( '31-12-' . date ( 'Y' ) . ' 00:00:00' ) );
}

$startDate  = isset($_POST['start_date'] ) ? $_POST['start_date']: firstOfMonth();
$endDate    = isset($_POST['end_date']   ) ? $_POST['end_date']  : lastOfMonth ();
$billerId   = isset($_POST['biller_id']  ) ? intval($_POST['biller_id'])  : null;
$customerId = isset($_POST['customer_id']) ? intval($_POST['customer_id']): null;

$showOnlyUnpaid    = "no";
$doNotFilterByDate = "no";
$invoices          = [];
$statement         = ["total" => 0, "owing" => 0, "paid" => 0];

if (isset($_POST['submit'])) {
    try {
        $havings = [];
        if (isset($_POST['do_not_filter_by_date'])) {
            $doNotFilterByDate = "yes";
        } else {
            $doNotFilterByDate = "no";
            $havings[] = ["date_between" => [$startDate, $endDate]];
        }

        if (isset($_POST['show_only_unpaid'])) {
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
        foreach ( $invoices as $row ) {
            if ($row ['status'] > 0) {
                $statement ['total'] += $row ['total'];
                $statement ['owing'] += $row ['owing'];
                $statement ['paid']  += $row ['paid'];
            }
        }
    } catch (PdoDbException $pde) {
        error_log("modules/statement/index.php - error: " . $pde->getMessage());
    }
}

// @formatter:off
$billers         = Biller::getAll(true);
$billerCount     = count($billers);
$customers       = Customer::getAll(['enabled_only' => true]);
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

$smarty->assign('biller_id'       , $billerId);
$smarty->assign('billers'         , $billers);
$smarty->assign('biller_count'    , $billerCount);
$smarty->assign('biller_details'  , $billerDetails);
$smarty->assign('customer_id'     , $customerId);
$smarty->assign('customers'       , $customers);
$smarty->assign('customer_count'  , $customerCount);
$smarty->assign('customer_details', $customerDetails);

$smarty->assign('show_only_unpaid'     , $showOnlyUnpaid);
$smarty->assign('do_not_filter_by_date', $doNotFilterByDate);

$smarty->assign('invoices'  , $invoices);
$smarty->assign('statement' , $statement);
$smarty->assign('start_date', $startDate);
$smarty->assign('end_date'  , $endDate);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
