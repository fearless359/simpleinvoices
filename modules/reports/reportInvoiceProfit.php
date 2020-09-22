<?php

use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;

/*
 * Script: report_sales_by_period.php
 * Sales reports by period add page
 * License:
 * GPL v3
 * Website:
 * https://simpleinvoices.group
 */
global $pdoDb, $smarty;

Util::directAccessAllowed();

/**
 * @return false|string
 */
function firstOfMonth() {
    return date("Y-m-d", strtotime('01-' . date('m') . '-' . date('Y') . ' 00:00:00'));
}

/**
 * @return false|string
 */
function lastOfMonth() {
    return date("Y-m-d", strtotime('-1 second', strtotime('+1 month', strtotime('01-' . date('m') . '-' . date('Y') . ' 00:00:00'))));
}

isset($_POST['start_date']) ? $startDate = $_POST['start_date'] : $startDate = firstOfMonth();
isset($_POST['end_date']) ? $endDate = $_POST['end_date'] : $endDate = lastOfMonth();

try {
    // Select invoice date between range for real invoices.
    $havings = [
        "date_between" => [$startDate, $endDate],
        "real" => ''
    ];
    $invoices = Invoice::getAllWithHavings($havings);

    $invoiceTotals = [];
    foreach($invoices as $key=>$val) {
        //get list of all products
        $pdoDb->addToFunctions('DISTINCT(product_id)');
        $pdoDb->addSimpleWhere('invoice_id', $val['id'], 'AND');
        $pdoDb->addSimpleWhere('domain_id', DomainId::get());
        $products = $pdoDb->request('SELECT', 'invoice_items');

        $invoiceTotalCost = "0";
        foreach ($products as $pv) {
            $pdoDb->addToFunctions(new FunctionStmt('SUM', 'quantity'));
            $pdoDb->addSimpleWhere('product_id', $pv['product_id'], 'AND');
            $pdoDb->addSimpleWhere('invoice_id', $val['id'], 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $rows = $pdoDb->request('SELECT', 'invoice_items');
            $quantity = empty($rows) ? 0 : $rows[0]['quantity'];

            $pdoDb->addToFunctions('(SUM(cost * quantity) / SUM(quantity)) AS avg_cost');
            $pdoDb->addSimpleWhere('product_id', $pv['product_id'], 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $rows = $pdoDb->request('SELECT', 'inventory');
            $cost = empty($rows) ? 0 : $rows[0]['avg_cost'];

            $invoiceTotalCost += $quantity * $cost;
        }
        $invoices[$key]['cost'] = $invoiceTotalCost;
        $invoices[$key]['profit'] = $invoices[$key]['total'] - $invoices[$key]['cost'];

        $invoiceTotals['sum_total'] = $invoiceTotals['sum_total'] + $invoices[$key]['total'];
        $invoiceTotals['sum_cost'] = $invoiceTotals['sum_cost'] + $invoices[$key]['cost'];
        $invoiceTotals['sum_profit'] = $invoiceTotals['sum_profit'] + $invoices[$key]['profit'];
    }

    $smarty->assign('invoices'      , $invoices);
    $smarty->assign('invoice_totals', $invoiceTotals);
    $smarty->assign('start_date'    , $startDate);
    $smarty->assign('end_date'      , $endDate);
} catch (PdoDbException $pde) {
    exit("modules/reports/reportInvoiceProfit.php Unexpected error: [{$pde->getMessage()}]");
}

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
