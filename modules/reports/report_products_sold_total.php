<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Join;
use Inc\Claz\PdoDb;

/**
 * @var PdoDb $pdoDb
 */
global $pdoDb, $smarty;

$pdoDb->addSimpleWhere('p.visible', ENABLED, 'AND');
$pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
$pdoDb->addSimpleWhere('p.domain_id', DomainId::get());

$pdoDb->setGroupBy('p.description');

$pdoDb->setSelectList('p.description');
$pdoDb->addToFunctions(new FunctionStmt('SUM', 'ii.quantity', 'sum_quantity'));

$jn = new Join('INNER', 'invoices', 'iv');
$jn->addSimpleItem('ii.invoice_id', new DbField('iv.id'), 'AND');
$jn->addSimpleItem('ii.domain_id', new DbField('iv.domain_id'));
$pdoDb->addToJoins($jn);

$jn = new Join('INNER', 'products', 'p');
$jn->addSimpleItem('p.id', new DbField('ii.product_id'), 'AND');
$jn->addSimpleItem('p.domain_id', new DbField('ii.domain_id'));
$pdoDb->addToJoins($jn);

$jn = new Join('INNER', 'preferences', 'pr');
$jn->addSimpleItem('pr.pref_id', new DbField('iv.preference_id'), 'AND');
$jn->addSimpleItem('pr.domain_id', new DbField('iv.domain_id'));
$pdoDb->addToJoins($jn);

$rows = $pdoDb->request('SELECT', 'invoice_items', 'ii');

$total_quantity = 0;
foreach ($rows as $row) {
    $total_quantity += $row['sum_quantity'];
}

$smarty->assign('data', $rows);
$smarty->assign('total_quantity', $total_quantity);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
