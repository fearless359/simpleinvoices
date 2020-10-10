<?php

use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;
use Inc\Claz\WhereItem;

/*
 *  Script:
 *      Sales by Representative report
 *
 *  Authors:
 *      Justin Kelly
 *
 *  Last edited:
 *      2018-10-19 by Richard Rowley
 *
 *  License:
 *      GPL v3 or later
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $endDate, $filterByDateRange, $menu, $pdoDb, $salesRep, $smarty, $startDate, $view;

Util::directAccessAllowed();

$invoices  = [];
$salesReps = [];
$statement = ["total" => 0, "owing" => 0, "paid" => 0];

try {
    if (!empty($salesRep)) {
        $pdoDb->addSimpleWhere("iv.sales_representative", $salesRep, 'AND');
    }

    if ($filterByDateRange == 'yes') {
        $invoices = Invoice::getAllWithHavings(["date_between" => [$startDate, $endDate]], "date");
    } else {
        $invoices = Invoice::getAll("date", "asc");
    }

    foreach ($invoices as $row) {
        $statement['total'] += $row['total'];
        $statement['owing'] += $row['owing'];
        $statement['paid'] += $row['paid'];
    }

    $pdoDb->addToWhere(new WhereItem(false, "sales_representative", "<>", "", false, "AND"));
    $pdoDb->addSimpleWhere("domain_id", DomainId::get());

    $pdoDb->addToFunctions(new FunctionStmt("DISTINCT", "sales_representative"));

    $rows = $pdoDb->request("SELECT", "invoices");

    foreach ($rows as $row) {
        $salesReps[] = $row['sales_representative'];
    }
} catch (PdoDbException $pde) {
    $str = "reportByRepresentativeData.php - Unexpected error: {$pde->getMessage()}";
    error_log($str);
    exit($str);
}

$smarty->assign('salesReps', $salesReps);
$smarty->assign('invoices', $invoices);
$smarty->assign('statement', $statement);
