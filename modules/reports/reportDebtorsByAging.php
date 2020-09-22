<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FromStmt;
use Inc\Claz\FunctionStmt;
use Inc\Claz\GroupBy;
use Inc\Claz\Join;
use Inc\Claz\PdoDbException;
use Inc\Claz\Select;

global $pdoDb, $smarty;

$rows =[];
try {
    $pdoDb->setSelectList([
        'iv.id',
        'iv.index_id',
        'pr.pref_inv_wording',
        new DbField('iv.aging', 'Aging'),
        new DbField('iv.owing', 'inv_owing'),
        new DbField('iv.age_days', 'age'),
        new DbField('b.name', 'biller'),
        new DbField('c.name', 'customer')
    ]);

    $pdoDb->addToFunctions(new FunctionStmt("--COUNT", 'ii.invoice_id', 'items'));
    $pdoDb->addToFunctions(new FunctionStmt('SUM', 'COALESCE(ap.inv_paid, 0)', 'inv_total'));
    $pdoDb->addToFunctions(new FunctionStmt('COALESCE', 'ap.inv_paid,0', 'inv_paid'));

    $pdoDb->addToFunctions(new FunctionStmt('DATE_FORMAT', 'date, "%Y-%m-%e"', 'date'));

    $jn = new Join('LEFT', 'invoice_items', 'ii');
    $jn->addSimpleItem('ii.invoice_id', new DbField('iv.id'), 'AND');
    $jn->addSimpleItem('ii.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $jn = new Join('LEFT', 'biller', 'b');
    $jn->addSimpleItem('b.id', new DbField('iv.biller_id'), 'AND');
    $jn->addSimpleItem('b.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $jn = new Join('LEFT', 'customers', 'c');
    $jn->addSimpleItem('c.id', new DbField('iv.customer_id'), 'AND');
    $jn->addSimpleItem('c.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $jn = new Join('LEFT', 'preferences', 'pr');
    $jn->addSimpleItem('pr.pref_id', new DbField('iv.preference_id'), 'AND');
    $jn->addSimpleItem('pr.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $ls = ['ac_inv_id', 'domain_id'];
    $ls[] = new FunctionStmt("SUM", "COALESCE(ac_amount, 0)", "inv_paid");
    $fr = new FromStmt("payment");
    $gr = new GroupBy(['ac_inv_id', 'domain_id']);
    $se = new Select($ls, $fr, null, $gr, "ap");
    $jn = new Join("LEFT", $se);
    $jn->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
    $jn->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
    $pdoDb->addToJoins($jn);

    $pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
    $pdoDb->addSimpleWhere('iv.domain_id', DomainId::get());

    $pdoDb->setGroupBy('iv.id');

    $pdoDb->setSimpleHavings('inv_owing', '>', '0');

    $pdoDb->setOrderBy(['age', 'DESC']);

    $rows = $pdoDb->request('SELECT', 'invoices', 'iv');

    $totalOwed = 0;
    $periods = [];
    foreach ($rows as $row) {
        $periods[$row['Aging']]['name'] = $row['Aging'];
        if (!array_key_exists('invoices', $periods[$row['Aging']])) {
            $periods[$row['Aging']]['invoices'] = [];
        }

        array_push($periods[$row['Aging']]['invoices'], $row);
        $periods[$row['Aging']]['sum_total'] += $row['inv_owing'];
        $totalOwed += $row['inv_owing'];
    }

    $smarty->assign('data', $periods);
    $smarty->assign('total_owed', $totalOwed);
} catch (PdoDbException $pde) {
    error_log("modules/reports/reportDebtorsByAging.php Unexpected error: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
