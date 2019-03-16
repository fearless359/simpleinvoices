<?php

use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Invoice;
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
function firstOfMonth() {
    return date("Y-m-d", strtotime('01-' . date('m') . '-' . date('Y') . ' 00:00:00'));
}
function lastOfMonth() {
    return date("Y-m-d", strtotime('-1 second', strtotime('+1 month', strtotime('01-' . date('m') . '-' . date('Y') . ' 00:00:00'))));
}

isset($_POST['start_date']) ? $start_date = $_POST['start_date'] : $start_date = firstOfMonth();
isset($_POST['end_date']) ? $end_date = $_POST['end_date'] : $end_date = lastOfMonth();

// Select invoice date between range for real invoices.
$havings = array("date_between" => array($start_date, $end_date),
                 "real" => '');
$invoices = Invoice::getAllWithHavings($havings);

$invoice_totals = array();
foreach($invoices as $k=>$v) {
    //get list of all products
    $pdoDb->addToFunctions('DISTINCT(product_id)');
    $pdoDb->addSimpleWhere('invoice_id', $v['id'], 'AND');
    $pdoDb->addSimpleWhere('domain_id', DomainId::get());
    $products = $pdoDb->request('SELECT', 'invoice_items');

    $invoice_total_cost = "0";
    foreach($products as $pv) {
        $quantity="";
        $cost="";
        $product_total_cost="";

        $pdoDb->addToFunctions(new FunctionStmt('SUM', 'quantity'));
        $pdoDb->addSimpleWhere('product_id', $pv['product_id'], 'AND');
        $pdoDb->addSimpleWhere('invoice_id', $v['id'], 'AND');
        $pdoDb->addSimpleWhere('domain_id', DomainId::get());
        $rows = $pdoDb->request('SELECT', 'invoice_items');
        $quantity = (empty($rows) ? 0 : $rows[0]['quantity']);

        $pdoDb->addToFunctions('(SUM(cost * quantity) / SUM(quantity)) AS avg_cost');
        $pdoDb->addSimpleWhere('product_id', $pv['product_id'], 'AND');
        $pdoDb->addSimpleWhere('domain_id', DomainId::get());
        $rows = $pdoDb->request('SELECT', 'inventory');
        $cost = (empty($rows) ? 0 : $rows[0]['avg_cost']);

        $product_total_cost = $quantity * $cost;
        $invoice_total_cost += $product_total_cost;
    }
    $invoices[$k]['cost'  ] =  $invoice_total_cost;
    $invoices[$k]['profit'] =  $invoices[$k]['total'] - $invoices[$k]['cost'];

    $invoice_totals['sum_total' ] = $invoice_totals['sum_total' ] + $invoices[$k]['total'];
    $invoice_totals['sum_cost'  ] = $invoice_totals['sum_cost'  ] + $invoices[$k]['cost'         ];
    $invoice_totals['sum_profit'] = $invoice_totals['sum_profit'] + $invoices[$k]['profit'       ];
}

$smarty->assign('invoices'      , $invoices);
$smarty->assign('invoice_totals', $invoice_totals);
$smarty->assign('start_date'    , $start_date);
$smarty->assign('end_date'      , $end_date);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
