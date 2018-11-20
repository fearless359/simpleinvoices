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

$pdoDb->addToFunctions(new FunctionStmt('SUM', 'ii.tax_amount', 'sum_tax_total'));

$jn = new Join('INNER', 'invoices', 'iv');
$jn->addSimpleItem('iv.id', new DbField('ii.invoice_id'), 'AND');
$jn->addSimpleItem('iv.domain_id', new DbField('ii.domain_id'));
$pdoDb->addToJoins($jn);

$jn = new Join('INNER', 'preferences', 'pr');
$jn->addSimpleItem('pr.pref_id', new DbField('iv.preference_id'), 'AND');
$jn->addSimpleItem('pr.domain_id', new DbField('iv.domain_id'));
$pdoDb->addToJoins($jn);

$rows = $pdoDb->request('SELECT', 'invoice_items', 'ii');

$smarty->assign('total_taxes', $rows);

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
