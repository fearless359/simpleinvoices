<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\Join;
use Inc\Claz\OnClause;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;
use Inc\Claz\WhereItem;

/*
 * Script: report_sales_by_period.php
 *     Sales reports by period add page
 *
 * Authors:
 *     Justin Kelly
 *
 * Last edited:
 *      2008-05-13
 *
 * License:
 *     GPL v3
 *
 * Website:
 *     https://simpleinvoices.group
 */
global $pdoDb, $smarty;

Util::directAccessAllowed();

/**
 * @return false|string
 */
function firstOfMonth() {
    return date("Y-m-d", strtotime('01-'.date('m').'-'.date('Y').' 00:00:00'));
}

/**
 * @return false|string
 */
function lastOfMonth() {
    return date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime('01-'.date('m').'-'.date('Y').' 00:00:00'))));
}

$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : firstOfMonth() ;
$endDate   = isset($_POST['end_date'])   ? $_POST['end_date']   : lastOfMonth()  ;

try {
    $pdoDb->setSelectList([new DbField("e.amount", "expense"), new DbField("ea.name", "account")]);

    $pdoDb->addToWhere(new WhereItem(false, "e.date", "BETWEEN", [$startDate, $endDate], false, "AND"));
    $pdoDb->addSimpleWhere("e.domain_id", DomainId::get());

    $on = new OnClause();
    $on->addSimpleItem("e.expense_account_id", new DbField("ea.id"), "AND");
    $on->addSimpleItem("e.domain_id", new DbField("ea.domain_id"));
    $jn = new Join("LEFT", "expense_account", "ea");
    $jn->setOnClause($on);
    $pdoDb->addToJoins($jn);

    $pdoDb->setOrderBy('account');
    $pdoDb->setGroupBy('account');

    $rows = $pdoDb->request("SELECT", "expense", "e");

    $smarty->assign('accounts', $rows);
    $smarty->assign('start_date', $startDate);
    $smarty->assign('end_date', $endDate);
} catch (PdoDbException $pde) {
    exit("modules/reports/report_expense_account_by_period.php Unexpected error: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
