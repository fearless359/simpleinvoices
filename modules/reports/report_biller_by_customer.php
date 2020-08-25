<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Join;
use Inc\Claz\PdoDbException;

global $pdoDb, $smarty;

try {
    $pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
    $pdoDb->addSimpleWhere('b.domain_id', DomainId::get());

    $pdoDb->setSelectList([new DbField('b.name', 'Biller'), new DbField('c.name', 'Customer')]);

    $pdoDb->setGroupBy(['b.name', 'c.name']);

    $fn = new FunctionStmt('SUM', new DbField('ii.total'), 'SUM_TOTAL');
    $pdoDb->addToFunctions($fn);

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

    $jn = new Join('INNER', 'customers', 'c');
    $jn->addSimpleItem('c.id', new DbField('iv.customer_id'), 'AND');
    $jn->addSimpleItem('c.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $rows = $pdoDb->request('SELECT', 'biller', 'b');

    $totalSales = 0;
    $billers = [];
    foreach ($rows as $row) {
        $customer = [];
        $customer['name'] = $row['Customer'];
        $customer['sum_total'] = $row['SUM_TOTAL'];

        $biller = $row['Biller'];
        $billers[$biller]['name'] = $biller;

        if (!array_key_exists('customers', $billers[$biller])) {
            $billers[$biller]['customers'] = [];
        }

        array_push($billers[$biller]['customers'], $customer);

        $billers[$biller]['total_sales'] += $row['SUM_TOTAL'];

        $totalSales += $row['SUM_TOTAL'];
    }

    $smarty->assign('data', $billers);
    $smarty->assign('total_sales', $totalSales);
} catch (PdoDbException $pde) {
    exit("modules/reports/report_biller_by_customer.php Unexpected error: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');

