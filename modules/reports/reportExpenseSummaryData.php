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
global $endDate, $LANG, $smarty, $pdoDb, $startDate;

Util::directAccessAllowed();

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
    $ca->addWhen("=", ENABLED, $LANG['paidUc']);
    $ca->addWhen("<>", ENABLED, $LANG['notPaid'], true);
    $pdoDb->addToCaseStmts($ca);

    $jn = new Join("LEFT", "expense_account", "ea");
    $jn->addSimpleItem("e.expense_account_id", new DbField("ea.id"), "AND");
    $jn->addSimpleItem("e.domain_id", new DbField("ea.domain_id"));
    $pdoDb->addToJoins($jn);

    $pdoDb->addSimpleWhere("e.domain_id", DomainId::get(), "AND");
    $pdoDb->addToWhere(new WhereItem(false, "e.date", "BETWEEN", [$startDate, $endDate], false));

    $accounts = $pdoDb->request("SELECT", "expense", "e");

    $payments = Payment::selectByValue("date", [$startDate, $endDate]);

    $invoices = Invoice::getAllWithHavings(["date_between" => [$startDate, $endDate]], "preference");

    $smarty->assign('accounts'  , $accounts);
    $smarty->assign('payments'  , $payments);
    $smarty->assign('invoices'  , $invoices);
} catch (PdoDbException $pde) {
    error_log("modules/reports/reportExpenseSummaryData.php Exception: {$pde->getMessage()}");
    exit("Unable to process request. See error log for details.");
}
