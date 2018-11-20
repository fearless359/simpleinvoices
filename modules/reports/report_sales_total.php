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

$pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
$pdoDb->addSimpleWhere('ii.domain_id', DomainId::get());

$pdoDb->setGroupBy('pr.index_group');

$pdoDb->setSelectList('pr.index_group');

$pdoDb->addToFunctions(new FunctionStmt('GROUP_CONCAT', 'DISTINCT pr.pref_description SEPARATOR \',\'', 'template'));
$pdoDb->addToFunctions(new FunctionStmt('COUNT', 'DISTINCT ii.invoice_id', 'count'));
$pdoDb->addToFunctions(new FunctionStmt('SUM', new DbField('ii.total'), 'total'));

$jn = new Join('INNER', 'invoices', 'iv');
$jn->addSimpleItem('iv.id', new DbField('ii.invoice_id'), 'AND');
$jn->addSimpleItem('iv.domain_id', new DbField('ii.domain_id'));
$pdoDb->addToJoins($jn);

$jn = new Join('INNER', 'preferences', 'pr');
$jn->addSimpleItem('pr.pref_id', new DbField('iv.preference_id'), 'AND');
$jn->addSimpleItem('pr.domain_id', new DbField('iv.domain_id'));
$pdoDb->addToJoins($jn);

$sales = $pdoDb->request('SELECT', 'invoice_items', 'ii');

$grand_total_sales = 0;
foreach ($sales as $sale) {
    $grand_total_sales += $sale['sum_total'];
}

$smarty->assign('data', $sales);
$smarty->assign('grand_total_sales', $grand_total_sales);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
