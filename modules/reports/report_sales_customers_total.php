<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Join;

global $pdoDb, $smarty;

$pdoDb->setSelectList('c.name');
$pdoDb->addToFunctions(new FunctionStmt('SUM', 'ii.total', 'sum_total'));

$jn = new Join('INNER', 'invoices', 'iv');
$jn->addSimpleItem('c.id', new DbField('iv.customer_id'), 'AND');
$jn->addSimpleItem('c.domain_id', new DbField('iv.domain_id'));
$pdoDb->addToJoins($jn);

$jn = new Join('INNER', 'invoice_items', 'ii');
$jn->addSimpleItem('ii.invoice_id', new DbField('iv.id'), 'AND');
$jn->addSimpleItem('ii.domain_id', new DbField('iv.domain_id'));
$pdoDb->addToJoins($jn);

$jn = new Join('INNER', 'preferences', 'pr');
$jn->addSimpleItem('pr.pref_id', new DbField('iv.preference_id'), 'AND');
$jn->addSimpleItem('pr.domain_id', new DbField('iv.domain_id'));
$pdoDb->addToJoins($jn);

$pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
$pdoDb->addSimpleWhere('c.domain_id', DomainId::get());

$pdoDb->setGroupBy('c.name');

$rows = $pdoDb->request('SELECT', 'customers', 'c');

$total_sales = 0;
foreach ($rows as $row) {
    $total_sales += $row['sum_total'];
}

$smarty->assign('data', $rows);
$smarty->assign('total_sales', $total_sales);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');

