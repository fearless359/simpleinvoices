<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Join;
use Inc\Claz\PdoDbException;

global $pdoDb, $smarty;

try {
    $pdoDb->setSelectList('b.name');

    $pdoDb->addToFunctions(new FunctionStmt('SUM', 'ii.total', 'sum_total'));

    $jn = new Join('INNER', 'invoices', 'iv');
    $jn->addSimpleItem('b.id', new DbField('iv.biller_id'), 'AND');
    $jn->addSimpleItem('b.domain_id', new DbField('iv.domain_id'));
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
    $pdoDb->addSimpleWhere('b.domain_id', DomainId::get());

    $pdoDb->setGroupBy('b.name');

    $rows = $pdoDb->request('SELECT', 'biller', 'b');

    $totalSales = 0;
    foreach ($rows as $row) {
        $totalSales += $row['sum_total'];
    }

    $smarty->assign('data', $rows);
    $smarty->assign('total_sales', $totalSales);
} catch (PdoDbException $pde) {
    exit("modules/reports/report_biller_total.php Unexpected error: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
