<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Join;
use Inc\Claz\OnClause;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;
use Inc\Claz\WhereItem;

global $endDate, $pdoDb, $smarty, $startDate;

Util::directAccessAllowed();

$rows = [];
try {
    $pdoDb->addSimpleWhere('p.visible', ENABLED, 'AND');
    $pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
    $pdoDb->addSimpleWhere('p.domain_id', DomainId::get());

    $pdoDb->setGroupBy('p.description');

    $pdoDb->setSelectList('p.description');
    $pdoDb->addToFunctions(new FunctionStmt('SUM', 'ii.quantity', 'sumQuantity'));

    $onClause = new Onclause();
    $onClause->addItem(new WhereItem(false, 'iv.date', 'BETWEEN', [$startDate, $endDate], false, "AND"));
    $onClause->addSimpleItem('iv.id', new DbField('ii.invoice_id'), 'AND');
    $onClause->addSimpleItem('iv.domain_id', new DbField('ii.domain_id'));
    $jn = new Join('INNER', 'invoices', 'iv');
    $jn->setOnClause($onClause);
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
} catch (PdoDbException $pde) {
    error_log("modules/reports/reportProductsSoldTotalData.php Exception: {$pde->getMessage()}");
    exit("Unable to process request. See error log for details.");
}
$totalQuantity = 0;
foreach ($rows as $row) {
    $totalQuantity += $row['sumQuantity'];
}

$smarty->assign('data', $rows);
$smarty->assign('totalQuantity', $totalQuantity);
