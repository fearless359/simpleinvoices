<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Join;
use Inc\Claz\OnClause;
use Inc\Claz\PdoDbException;
use Inc\Claz\WhereItem;

global $billerId, $endDate, $pdoDb, $smarty, $startDate;

try {
    if (!empty($billerId)) {
        $pdoDb->addSimpleWhere('b.id', $billerId, 'AND');
    }
    $pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
    $pdoDb->addSimpleWhere('b.domain_id', DomainId::get());

    $pdoDb->setSelectList('b.name');

    $pdoDb->addToFunctions(new FunctionStmt('SUM', 'ii.total', 'sumTotal'));

    $onClause = new Onclause();
    $onClause->addItem(new WhereItem(false, 'iv.date', 'BETWEEN', [$startDate, $endDate], false, "AND"));
    $onClause->addSimpleItem('iv.biller_id', new DbField('b.id'), 'AND');
    $onClause->addSimpleItem('iv.domain_id', new DbField('b.domain_id'));
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

    $pdoDb->setGroupBy('b.name');

    $rows = $pdoDb->request('SELECT', 'biller', 'b');

    $totalSales = 0;
    foreach ($rows as $row) {
        $totalSales += $row['sumTotal'];
    }

    $smarty->assign('data', $rows);
    $smarty->assign('totalSales', $totalSales);
} catch (PdoDbException $pde) {
    exit("modules/reports/reportBillerTotal.php Unexpected error: {$pde->getMessage()}");
}
