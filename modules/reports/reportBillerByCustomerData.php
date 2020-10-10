<?php

use Inc\Claz\Biller;
use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Join;
use Inc\Claz\OnClause;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;
use Inc\Claz\WhereItem;

global $billerId, $endDate, $pdoDb, $smarty, $startDate;

Util::directAccessAllowed();

$billers = Biller::getAll(true);
$smarty->assign('billers', $billers);
try {
    if (!empty($billerId)) {
        $pdoDb->addSimpleWhere("b.id", $billerId, "AND");
    }
    $pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
    $pdoDb->addSimpleWhere('b.domain_id', DomainId::get());

    $pdoDb->setSelectList([new DbField('b.name', 'billerName'), new DbField('c.name', 'custName')]);

    $pdoDb->setGroupBy(['b.name', 'c.name']);

    $fn = new FunctionStmt('SUM', new DbField('ii.total'), 'sumTotal');
    $pdoDb->addToFunctions($fn);

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

    $jn = new Join('INNER', 'customers', 'c');
    $jn->addSimpleItem('c.id', new DbField('iv.customer_id'), 'AND');
    $jn->addSimpleItem('c.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $rows = $pdoDb->request('SELECT', 'biller', 'b');

    $totalSales = 0;
    $billerInfo = [];
    foreach ($rows as $row) {
        $customer = [
            'name' => $row['custName'],
            'sumTotal' => $row['sumTotal']
        ];

        $billerName = $row['billerName'];
        $billerInfo[$billerName]['name'] = $billerName;

        if (!array_key_exists('customers', $billerInfo[$billerName])) {
            $billerInfo[$billerName]['customers'] = [];
        }

        array_push($billerInfo[$billerName]['customers'], $customer);

        $billerInfo[$billerName]['totalSales'] += $row['sumTotal'];

        $totalSales += $row['sumTotal'];
    }

    $smarty->assign('data', $billerInfo);
    $smarty->assign('totalSales', $totalSales);
} catch (PdoDbException $pde) {
    exit("modules/reports/reportBillerByCustomer.php Unexpected error: {$pde->getMessage()}");
}
