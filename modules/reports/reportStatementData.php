<?php

global $endDate, $LANG, $menu, $startDate, $smarty;

use Inc\Claz\Util;

Util::directAccessAllowed();

include 'library/dateRangePrompt.php';

include 'modules/reports/reportSalesTotalData.php';

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
