<?php

use Inc\Claz\CaseStmt;
use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FromStmt;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Invoice;
use Inc\Claz\Join;
use Inc\Claz\Payment;
use Inc\Claz\Select;
use Inc\Claz\Util;
use Inc\Claz\WhereItem;

/*
 * Script: report_sales_by_period.php
 * 	Sales reports by period add page
 *
 * Authors:
 *	 Justin Kelly
 *
 * Last edited:
 * 	 2016-08-16
 *
 * License:
 *	 GPL v3
 *
 * Website:
 * 	https://simpleinvoices.group
 */
global $LANG, $smarty, $pdoDb;

Util::directAccessAllowed();

$domain_id = DomainId::get();

function firstOfMonth() {
	return date("Y-m-d", strtotime('01-'.date('m').'-'.date('Y').' 00:00:00'));
}

function lastOfMonth() {
	return date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime('01-'.date('m').'-'.date('Y').' 00:00:00'))));
}

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : firstOfMonth() ;
$end_date   = isset($_POST['end_date'])   ? $_POST['end_date']   : lastOfMonth()  ;

$pdoDb->setSelectList(array("e.amount AS expense", "e.status AS status", "ea.name AS account"));

$fn = new FunctionStmt("SUM", "tax_amount");
$fr = new FromStmt("expense_item_tax");
$wh = new WhereItem(false, "expense_id", "=", new DbField("e.id"), false);
$se = new Select($fn, $fr, $wh, null, "tax");
$pdoDb->addToSelectStmts($se);

$fn = new FunctionStmt("", "tax + e.amount");
$se = new Select($fn, null, null, null, "total");
$pdoDb->addToSelectStmts($se);

$ca = new CaseStmt(status, "status_wording");
$ca->addWhen("=", ENABLED, $LANG['paid']);
$ca->addWhen("<>", ENABLED, $LANG['not_paid'],true);
$pdoDb->addToCaseStmts($ca);

$jn = new Join("LEFT", "expense_account", "ea");
$jn->addSimpleItem("e.expense_account_id", new DbField("ea.id"), "AND");
$jn->addSimpleItem("e.domain_id", new DbField("ea.domain_id"));
$pdoDb->addToJoins($jn);

$pdoDb->addSimpleWhere("e.domain_id", $domain_id, "AND");
$pdoDb->addToWhere(new WhereItem(false, "e.date", "BETWEEN", array($start_date, $end_date), false));

$accounts = $pdoDb->request("SELECT", "expense", "e");

$payments = Payment::selectByDate($start_date, $end_date, "date", "");

$invoices = Invoice::getAllWithHavings(array("date_between" => array($start_date, $end_date)), "preference");

$smarty->assign('accounts'  , $accounts);
$smarty->assign('payments'  , $payments);
$smarty->assign('invoices'  , $invoices);
$smarty->assign('start_date', $start_date);
$smarty->assign('end_date'  , $end_date);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
