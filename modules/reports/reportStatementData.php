<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\CLaz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;

global $billerId, $customerId, $endDate, $filterByDateRange, $includePaidInvoices,
       $LANG, $menu, $pdoDb, $startDate, $smarty;

Util::directAccessAllowed();

$invoices = [];
$statement = ["total" => 0, "owing" => 0, "paid" => 0];

try {
    $havings = [];
    if ($filterByDateRange == "yes") {
        $havings[] = ["date_between" => [$startDate, $endDate]];
    }

    if ($includePaidInvoices == "no") {
        $havings[] = ["money_owed" => ''];
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

// @formatter:off
$billers       = Biller::getAll(true);
$billerCount   = count($billers);
$billerDetails = empty($billerId) ? [] : Biller::getOne($billerId);

$customers       = Customer::getAll(['enabledOnly' => true]);
$customerCount   = count($customers);
$customerDetails = empty($customerId) ? [] : Customer::getOne($customerId);

$smarty->assign('billers'          , $billers);
$smarty->assign('billerCount'      , $billerCount);
$smarty->assign('billerDetails'    , $billerDetails);
$smarty->assign('customers'        , $customers);
$smarty->assign('customerCount'    , $customerCount);
$smarty->assign('customerDetails'  , $customerDetails);

$smarty->assign('invoices'  , $invoices);
$smarty->assign('statement' , $statement);
// @formatter:on
