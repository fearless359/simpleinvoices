<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FromStmt;
use Inc\Claz\FunctionStmt;
use Inc\Claz\GroupBy;
use Inc\Claz\Join;
use Inc\Claz\OnClause;
use Inc\Claz\PdoDbException;
use Inc\Claz\Select;
use Inc\Claz\WhereClause;

global $pdoDb, $smarty;

$rows = array();
try {
    $pdoDb->setSelectList(array(
        'iv.id',
        'iv.domain_id',
        'iv.index_id',
        'pr.pref_inv_wording',
        'iv.date',
        new DbField('b.name', 'biller'),
        new DbField('c.name', 'customer')
    ));
    $pdoDb->addToFunctions(new FunctionStmt('SUM', 'COALESCE(ii.total,0)', 'inv_total'));
    $pdoDb->addToFunctions(new FunctionStmt('COALESCE', 'ap.inv_paid, 0', 'inv_paid'));

    $fn = new FunctionStmt('SUM', 'COALESCE(ii.total, 0)', 'inv_owing');
    $fn->addPart('-', 'COALESCE(ap.inv_paid, 0)');
    $pdoDb->addToFunctions($fn);

    $jn = new Join('LEFT', 'invoice_items', 'ii');
    $jn->addSimpleItem('ii.invoice_id', new DbField('iv.id'), 'AND');
    $jn->addSimpleItem('ii.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $jn = new Join('LEFT', 'preferences', 'pr');
    $jn->addSimpleItem('pr.pref_id', new DbField('iv.preference_id'), 'AND');
    $jn->addSimpleItem('pr.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $jn = new Join('LEFT', 'biller', 'b');
    $jn->addSimpleItem('b.id', new DbField('iv.biller_id'), 'AND');
    $jn->addSimpleItem('b.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $jn = new Join('LEFT', 'customers', 'c');
    $jn->addSimpleItem('c.id', new DbField('iv.customer_id'), 'AND');
    $jn->addSimpleItem('c.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $ls = array('ac_inv_id', 'domain_id');
    $ls[] = new FunctionStmt("SUM", "COALESCE(ac_amount, 0)", "inv_paid");
    $fr = new FromStmt("payment");
    $gr = new GroupBy(array('ac_inv_id', 'domain_id'));
    $se = new Select($ls, $fr, null, $gr, "ap");
    $jn = new Join("LEFT", $se);
    $jn->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
    $jn->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
    $pdoDb->addToJoins($jn);

    $pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
    $pdoDb->addSimpleWhere('iv.domain_id', DomainId::get());

    $pdoDb->setGroupBy('iv.id');

    $pdoDb->setOrderBy(array(array('inv_owing', 'DESC'), array('iv.index_id', 'DESC')));

    $rows = $pdoDb->request('SELECT', 'invoices', 'iv');
} catch (PdoDbException $pde) {
    error_log('report_debtors_by_amount - error: ' . $pde->getMessage());
}

$total_owed = 0;
foreach ($rows as $row) {
    $total_owed += $row['inv_owing'];
}

$smarty -> assign('data', $rows);
$smarty -> assign('total_owed', $total_owed);   

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
