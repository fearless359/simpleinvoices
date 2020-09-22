<?php

use Inc\Claz\CaseStmt;
use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FromStmt;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Invoice;
use Inc\Claz\Join;
use Inc\Claz\Payment;
use Inc\Claz\PdoDbException;
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

$domainId = DomainId::get();

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
	return date("Y-m-d",
        strtotime('-1 second',
            strtotime('+1 month',
                strtotime('01-'.date('m').'-'.date('Y').' 00:00:00')
            )
        )
    );
}

$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : firstOfMonth() ;
$endDate   = isset($_POST['end_date'])   ? $_POST['end_date']   : lastOfMonth()  ;

try {
    $pdoDb->setSelectList(["e.amount AS expense", "e.status AS status", "ea.name AS account"]);

    $fn = new FunctionStmt("SUM", "tax_amount");
    $fr = new FromStmt("expense_item_tax");
    $wh = new WhereItem(false, "expense_id", "=", new DbField("e.id"), false);
    $se = new Select($fn, $fr, $wh, null, "tax");
    $pdoDb->addToSelectStmts($se);

    $fn = new FunctionStmt("", "tax + e.amount");
    $se = new Select($fn, null, null, null, "total");
    $pdoDb->addToSelectStmts($se);

    $ca = new CaseStmt("status", "status_wording");
    $ca->addWhen("=", ENABLED, $LANG['paid']);
    $ca->addWhen("<>", ENABLED, $LANG['notPaid'], true);
    $pdoDb->addToCaseStmts($ca);

    $jn = new Join("LEFT", "expense_account", "ea");
    $jn->addSimpleItem("e.expense_account_id", new DbField("ea.id"), "AND");
    $jn->addSimpleItem("e.domain_id", new DbField("ea.domain_id"));
    $pdoDb->addToJoins($jn);

    $pdoDb->addSimpleWhere("e.domain_id", $domainId, "AND");
    $pdoDb->addToWhere(new WhereItem(false, "e.date", "BETWEEN", [$startDate, $endDate], false));

    $accounts = $pdoDb->request("SELECT", "expense", "e");

    $payments = Payment::selectByValue("date", [$startDate, $endDate]);

    $invoices = Invoice::getAllWithHavings(["date_between" => [$startDate, $endDate]], "preference");

    $smarty->assign('accounts'  , $accounts);
    $smarty->assign('payments'  , $payments);
    $smarty->assign('invoices'  , $invoices);
    $smarty->assign('start_date', $startDate);
    $smarty->assign('end_date'  , $endDate);
} catch (PdoDbException $pde) {
    die("reportSummary error: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
