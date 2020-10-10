<?php

use Inc\Claz\Customer;
use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Join;
use Inc\Claz\OnClause;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;
use Inc\Claz\WhereItem;

global $customerId, $endDate, $pdoDb, $smarty, $startDate;

Util::directAccessAllowed();

try {
    $pdoDb->setSelectList(['c.id', 'c.name']);
    $pdoDb->addToFunctions(new FunctionStmt('SUM', 'ii.total', 'sumTotal'));

    $onClause = new OnClause();
    $onClause->addItem(new WhereItem(false, 'iv.date', 'BETWEEN', [$startDate, $endDate], false, "AND"));
    $onClause->addSimpleItem('c.id', new DbField('iv.customer_id'), 'AND');
    $onClause->addSimpleItem('c.domain_id', new DbField('iv.domain_id'));
    $jn = new Join('INNER', 'invoices', 'iv');
    $jn->setOnClause($onClause);
    $pdoDb->addToJoins($jn);

    $jn = new Join('INNER', 'invoice_items', 'ii');
    $jn->addSimpleItem('ii.invoice_id', new DbField('iv.id'), 'AND');
    $jn->addSimpleItem('ii.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $jn = new Join('INNER', 'preferences', 'pr');
    $jn->addSimpleItem('pr.pref_id', new DbField('iv.preference_id'), 'AND');
    $jn->addSimpleItem('pr.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    if (!empty($customerId)) {
        $pdoDb->addSimpleWhere('c.id', $customerId, "AND");
    }
    $pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
    $pdoDb->addSimpleWhere('c.domain_id', DomainId::get());

    $pdoDb->setGroupBy('c.name');

    $rows = $pdoDb->request('SELECT', 'customers', 'c');

    $totalSales = 0;
    foreach ($rows as $row) {
        $totalSales += $row['sumTotal'];
    }

    $smarty->assign('data', $rows);
    $smarty->assign('totalSales', $totalSales);
    $smarty->assign('customers', Customer::getALl());
} catch (PdoDbException $pde) {
    exit("modules/reports/reportsSalesCustomersTotalData.php Unexpected error: {$pde->getMessage()}");
}
