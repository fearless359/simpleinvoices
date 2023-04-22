<?php

use Inc\Claz\Customer;
use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Join;
use Inc\Claz\OnClause;
use Inc\Claz\PdoDbException;
use Inc\Claz\WhereItem;

global $customerId, $endDate, $pdoDb, $smarty, $startDate;

// Get data for the customer selection list.
$rows = Customer::getAll(['noTotals' => true]);
$custInfo = [];
foreach ($rows as $row) {
    $custInfo[] = [
        'id' => $row['id'],
        'name' => $row['name']
        // NOTE that last_activity_date is excluded on purpose
    ];
}
$smarty->assign('customers', $custInfo);

$customers = [];
try {
    if (!empty($customerId)) {
        $pdoDb->addSimpleWhere("c.id", $customerId, "AND");
    }
    $pdoDb->addSimpleWhere('p.visible', ENABLED, 'AND');
    $pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
    $pdoDb->addSimpleWhere('c.domain_id', DomainId::get());

    $pdoDb->setSelectList([new DbField('c.name'), new DbField('p.description')]);

    $pdoDb->setGroupBy(['p.description', 'c.name']);
    $pdoDb->setOrderBy('c.name');

    $pdoDb->addToFunctions(new FunctionStmt('SUM', new DbField('ii.quantity'), 'sum_quantity'));

    $onClause = new Onclause();
    $onClause->addItem(new WhereItem(false, 'iv.date', 'BETWEEN', [$startDate, $endDate], false, "AND"));
    $onClause->addSimpleItem('iv.customer_id', new DbField('c.id'), 'AND');
    $onClause->addSimpleItem('iv.domain_id', new DbField('c.domain_id'));
    $jn = new Join('INNER', 'invoices', 'iv');
    $jn->setOnClause($onClause);
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

    foreach ($rows as $row) {
        $pInfo = [];
        $pInfo['description'] = $row['description'];
        $pInfo['sum_quantity'] = $row['sum_quantity'];

        $name = $row['name'];
        $customers[$name]['name'] = $name;

        if (!array_key_exists('products', $customers[$name])) {
            $customers[$name]['products'] = [];
        }

        array_push($customers[$name]['products'], $pInfo);

        $customers[$name]['total_quantity'] += $row['sum_quantity'];
    }
} catch (PdoDbException $pde) {
    exit("modules/reports/reportProductsSoldByCustomer.php Unexpected error: {$pde->getMessage()}");
}

$smarty->assign('data', $customers);
