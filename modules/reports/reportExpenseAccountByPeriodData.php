<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\Join;
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
global $endDate, $pdoDb, $smarty, $startDate;

Util::directAccessAllowed();

try {
    $pdoDb->setSelectList([new DbField("e.amount", "expense"), new DbField("ea.name", "account")]);

    $pdoDb->addToWhere(new WhereItem(false, "e.date", "BETWEEN", [$startDate, $endDate], false, "AND"));
    $pdoDb->addSimpleWhere("e.domain_id", DomainId::get());

    $jn = new Join("LEFT", "expense_account", "ea");
    $jn->addSimpleItem("e.expense_account_id", new DbField("ea.id"), "AND");
    $jn->addSimpleItem("e.domain_id", new DbField("ea.domain_id"));
    $pdoDb->addToJoins($jn);

    $pdoDb->setOrderBy('account');
    $pdoDb->setGroupBy('account');

    $rows = $pdoDb->request("SELECT", "expense", "e");

    $smarty->assign('accounts', $rows);
} catch (PdoDbException $pde) {
    exit("modules/reports/reportExpenseAccountByPeriod.php Unexpected error: {$pde->getMessage()}");
}
