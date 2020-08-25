<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FromStmt;
use Inc\Claz\FunctionStmt;
use Inc\Claz\GroupBy;
use Inc\Claz\Join;
use Inc\Claz\PdoDbException;
use Inc\Claz\Select;
use Inc\Claz\WhereClause;

global $pdoDb, $smarty;

try {
    $rows = [];
    $pdoDb->setSelectList([
        new DbField('c.id', 'cid'),
        new DbField('c.name', 'customer')
    ]);
    $pdoDb->addToFunctions(new FunctionStmt('SUM', 'COALESCE(ii.total,0)', 'inv_total'));
    $pdoDb->addToFunctions(new FunctionStmt('COALESCE', 'ap.inv_paid, 0', 'inv_paid'));

    $fn = new FunctionStmt('SUM', 'COALESCE(ii.total, 0)', 'inv_owing');
    $fn->addPart('-', 'COALESCE(ap.inv_paid, 0)');
    $pdoDb->addToFunctions($fn);

    $jn = new Join('LEFT', 'invoices', 'iv');
    $jn->addSimpleItem('iv.customer_id', new DbField('c.id'), 'AND');
    $jn->addSimpleItem('iv.domain_id', new DbField('c.domain_id'));
    $pdoDb->addToJoins($jn);

    $jn = new Join('LEFT', 'invoice_items', 'ii');
    $jn->addSimpleItem('ii.invoice_id', new DbField('iv.id'), 'AND');
    $jn->addSimpleItem('ii.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $jn = new Join('LEFT', 'preferences', 'pr');
    $jn->addSimpleItem('pr.pref_id', new DbField('iv.preference_id'), 'AND');
    $jn->addSimpleItem('pr.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $ls = [new DbField('ivl.customer_id'), new DbField('apl.domain_id')];
    $ls[] = new FunctionStmt("SUM", "COALESCE(apl.ac_amount, 0)", "inv_paid");
    $fr = new FromStmt("payment", 'apl');
    $wh = new WhereClause();
    $wh->addSimpleItem('prl.status', ENABLED);
    $gr = new GroupBy(['ivl.customer_id', 'apl.domain_id']);
    $se = new Select($ls, $fr, $wh, $gr, "ap");

    $jn = new Join("LEFT", 'invoices', 'ivl');
    $jn->addSimpleItem("apl.ac_inv_id", new DbField("ivl.id"), "AND");
    $jn->addSimpleItem("apl.domain_id", new DbField("ivl.domain_id"));
    $se->addJoin($jn);

    $jn = new Join("LEFT", 'preferences', 'prl');
    $jn->addSimpleItem("prl.pref_id", new DbField("ivl.preference_id"), "AND");
    $jn->addSimpleItem("prl.domain_id", new DbField("ivl.domain_id"));
    $se->addJoin($jn);

    $jn = new Join('LEFT', $se);
    $jn->addSimpleItem('ap.customer_id', new DbField('c.id'), 'AND');
    $jn->addSimpleItem('ap.domain_id', new DbField('c.domain_id'));

    $pdoDb->addToJoins($jn);

    $pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
    $pdoDb->addSimpleWhere('c.domain_id', DomainId::get());

    $pdoDb->setGroupBy('c.id');

    $rows = $pdoDb->request('SELECT', 'customers', 'c');

    $totalOwed = 0;
    foreach ($rows as $row) {
        $totalOwed += $row['inv_owing'];
    }

    $smarty->assign('data', $rows);
    $smarty->assign('total_owed', $totalOwed);
} catch (PdoDbException $pde) {
    error_log("modules/reports/report_debtors_by_amount.php Unexpected error: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
