<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\Join;
use Inc\Claz\OnClause;
use Inc\Claz\PdoDb;
use Inc\Claz\Util;
use Inc\Claz\WhereItem;

/**
 * @var PdoDb $pdoDb
 */
global $endDate, $pdoDb, $smarty, $startDate;

Util::directAccessAllowed();

$onClause = new Onclause();
$onClause->addItem(new WhereItem(false, 'iv.date', 'BETWEEN', [$startDate, $endDate], false, 'AND'));
$onClause->addSimpleItem('iv.id', new DbField('ii.invoice_id'), 'AND');
$onClause->addSimpleItem('iv.domain_id', new DbField('ii.domain_id'));
$jn = new Join('INNER', 'invoices', 'iv');
$jn->setOnClause($onClause);
$pdoDb->addToJoins($jn);

$jn = new Join('INNER', 'preferences', 'pr');
$jn->addSimpleItem('pr.pref_id', new DbField('iv.preference_id'), 'AND');
$jn->addSimpleItem('pr.status', ENABLED, 'AND');
$jn->addSimpleItem('pr.domain_id', new DbField('iv.domain_id'));
$pdoDb->addToJoins($jn);

$pdoDb->addToWhere(new WhereItem(false, 'ii.tax_amount', "<>", 0, false, 'AND'));
$pdoDb->addSimpleWhere('ii.domain_id', DomainId::get());

$pdoDb->setSelectList(['iv.id', 'iv.index_id', 'ii.domain_id', 'ii.tax_amount', 'iv.date', 'pr.pref_description']);

$rows = $pdoDb->request("SELECT", "invoice_items", "ii");

$smarty->assign('taxDetail', $rows);

$invoices = [];
$totalTaxes = 0;
$lastInv = 0;
$lastPrefDesc = "";
$ivTotalTax = 0;
$idx = 0;
$cntRows = count($rows);
do {
    $row = $rows[$idx];
    $idx++;
    $done = $idx >= $cntRows;
    if ($lastInv != $row['index_id'] || $lastPrefDesc != $row['pref_description']) {
        if ($lastInv != 0) {
            $invRec['tax_amount'] = $ivTotalTax;
            $invoices[] = $invRec;
        }

        $invRec = $row;
        $lastInv = $row['index_id'];
        $lastPrefDesc = $row['pref_description'];
        $ivTotalTax = $row['tax_amount'];
    } else {
        $ivTotalTax += $row['tax_amount'];
    }
    $totalTaxes += $row['tax_amount'];

    if ($done) {
        $invRec['tax_amount'] = $ivTotalTax;
        $invoices[] = $invRec;
    }
} while (!$done);

$smarty->assign('invoices', $invoices);
$smarty->assign('totalTaxes', $totalTaxes);
