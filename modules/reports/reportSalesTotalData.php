<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Join;
use Inc\Claz\OnClause;
use Inc\Claz\PdoDbException;
use Inc\Claz\WhereItem;

global $endDate, $pdoDb, $startDate, $smarty;

try {
    $pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
    $pdoDb->addSimpleWhere('ii.domain_id', DomainId::get());

    $pdoDb->setGroupBy('pr.index_group');

    $pdoDb->setSelectList('pr.index_group');

    $pdoDb->addToFunctions(new FunctionStmt('GROUP_CONCAT', 'DISTINCT pr.pref_description SEPARATOR \',\'', 'template'));
    $pdoDb->addToFunctions(new FunctionStmt('COUNT', 'DISTINCT ii.invoice_id', 'count'));
    $pdoDb->addToFunctions(new FunctionStmt('SUM', new DbField('ii.total'), 'total'));

    $onClause = new OnClause();
    $onClause->addItem(new WhereItem(false, "date", ">=", $startDate, false, 'AND'));
    $onClause->addItem(new WhereItem( false, "date", "<=", $endDate, false, 'AND'));
    $onClause->addSimpleItem('iv.id', new DbField('ii.invoice_id'), 'AND');
    $onClause->addSimpleItem('iv.domain_id', new DbField('ii.domain_id'));
    $jn = new Join('INNER', 'invoices', 'iv');
    $jn->setOnClause($onClause);
    $pdoDb->addToJoins($jn);

    $jn = new Join('INNER', 'preferences', 'pr');
    $jn->addSimpleItem('pr.pref_id', new DbField('iv.preference_id'), 'AND');
    $jn->addSimpleItem('pr.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $sales = $pdoDb->request('SELECT', 'invoice_items', 'ii');

    $grandTotalSales = 0;
    foreach ($sales as $sale) {
        $grandTotalSales += $sale['total'];
    }

    $smarty->assign('data', $sales);
    $smarty->assign('grandTotalSales', $grandTotalSales);
} catch (PdoDbException $pde) {
    exit("modules/reports/reportSalesTotalData.php Unexpected error: {$pde->getMessage()}");
}
