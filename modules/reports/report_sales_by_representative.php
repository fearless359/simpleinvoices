<?php

use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Invoice;
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
global $menu, $pdoDb, $smarty;

Util::isAccessAllowed();

function firstOfMonth() {
    return date("Y-m-d", strtotime('01-01-' . date('Y') . ' 00:00:00'));
}
function lastOfMonth() {
    return date("Y-m-d", strtotime('31-12-' . date('Y') . ' 00:00:00'));
}

// @formatter:off
$start_date     = (isset($_POST['start_date']    ) ? $_POST['start_date']     : firstOfMonth());
$end_date       = (isset($_POST['end_date']      ) ? $_POST['end_date']       : lastOfMonth());
$sales_rep      = (isset($_POST['sales_rep']     ) ? $_POST['sales_rep']      : "");
$filter_by_date = (isset($_POST['filter_by_date']) ? $_POST['filter_by_date'] : "no");

$invoices = array();
$statement = array("total" => 0, "owing" => 0, "paid" => 0);
if (isset($_POST['submit'])) {
    $pdoDb->addSimpleWhere("iv.sales_representative", $sales_rep, 'AND');
    if (isset($_POST['filter_by_date'])) {
        $pdoDb->setHavings(Invoice::buildHavings("date_between", array($start_date, $end_date)));
        $filter_by_date = "yes";
    }

    $invoices = Invoice::select_all("", "date", "", null, "", "", "", "", "");
    foreach ( $invoices as $row ) {
        $statement['total'] += $row['invoice_total'];
        $statement['owing'] += $row['owing'];
        $statement['paid' ] += $row['INV_PAID'];
    }
}

$pdoDb->addToWhere(new WhereItem(false, "sales_representative", "<>", "", false, "AND"));
$pdoDb->addSimpleWhere("domain_id", DomainId::get());
$pdoDb->addToFunctions(new FunctionStmt("DISTINCT", "sales_representative"));
$rows = $pdoDb->request("SELECT", "invoices");
$sales_reps = array();
foreach ($rows as $row) {
    $sales_reps[] = $row['sales_representative'];
}

$smarty->assign('sales_reps'    , $sales_reps);
$smarty->assign('sales_rep'     , $sales_rep);
$smarty->assign('filter_by_date', $filter_by_date);
$smarty->assign('invoices'      , $invoices);
$smarty->assign('statement'     , $statement);
$smarty->assign('start_date'    , $start_date);
$smarty->assign('end_date'      , $end_date);

$smarty->assign('pageActive', 'report' );
$smarty->assign('active_tab', '#home' );
$smarty->assign('menu'      , $menu );
// @formatter:on
