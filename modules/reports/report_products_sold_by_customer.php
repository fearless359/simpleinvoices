<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Join;
use Inc\Claz\PdoDb;

/**
 * @var PdoDb $pdoDb
 */
global $pdoDb;

$pdoDb->addSimpleWhere('p.visible', ENABLED, 'AND');
$pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
$pdoDb->addSimpleWhere('c.domain_id', DomainId::get());

$pdoDb->setSelectList(array(new DbField('c.name'), new DbField('p.description')));

$pdoDb->setGroupBy(array('p.description', 'c.name'));
$pdoDb->setOrderBy('c.name');

$pdoDb->addToFunctions(new FunctionStmt('SUM', new DbField('ii.quantity'), 'sum_quantity'));

$jn = new Join('INNER', 'invoices', 'iv');
$jn->addSimpleItem('iv.customer_id', new DbField('c.id'), 'AND');
$jn->addSimpleItem('iv.domain_id', new DbField('c.domain_id'));
$pdoDb->addToJoins($jn);

$jn = new Join('INNER', 'invoice_items', 'ii');
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

$rows = $pdoDb->request('SELECT', 'customers', 'c');

$customers = array();
foreach ($rows as $row) {
    $p = array();
    $p['description'] = $row['description'];
    $p['sum_quantity'] = $row['sum_quantity'];

    $name = $row['name'];
    $customers[$name]['name'] = $name;

    if (!array_key_exists('products', $customers[$name])) {
        $customers[$name]['products'] = array();
    }

    array_push($customers[$name]['products'], $p);

    $customers[$name]['total_quantity'] += $row['sum_quantity'];
}

$smarty->assign('data', $customers);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
