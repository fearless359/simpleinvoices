<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FromStmt;
use Inc\Claz\FunctionStmt;
use Inc\Claz\GroupBy;
use Inc\Claz\Join;
use Inc\Claz\PdoDbException;
use Inc\Claz\Select;

global $includePaidInvoices, $pdoDb, $smarty;

$rows = [];
try {
    $pdoDb->setSelectList([
        'iv.id',
        'iv.domain_id',
        'iv.index_id',
        'pr.pref_inv_wording',
        'iv.date',
        new DbField('b.name', 'billerName'),
        new DbField('c.name', 'customerName')
    ]);

    $pdoDb->addToFunctions(new FunctionStmt('SUM', 'COALESCE(ii.total,0)', 'invTotal'));
    $pdoDb->addToFunctions(new FunctionStmt('COALESCE', 'ap.invPaid, 0', 'invPaid'));

    $fn = new FunctionStmt('SUM', 'COALESCE(ii.total, 0)', 'invOwing');
    $fn->addPart('-', 'COALESCE(ap.invPaid, 0)');
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

    $ls = ['ac_inv_id', 'domain_id'];
    $ls[] = new FunctionStmt("SUM", "COALESCE(ac_amount, 0)", "invPaid");
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

    $pdoDb->setOrderBy([
        ['invOwing', 'DESC'],
        ['iv.index_id', 'DESC']
    ]);

    $rows = $pdoDb->request('SELECT', 'invoices', 'iv');

    $totalOwed = 0;
    $invoices = [];
    foreach ($rows as $row) {
        if ($includePaidInvoices != 'yes' && $row['invOwing'] == 0) {
            continue;
        }
        $totalOwed += $row['invOwing'];
        $invoices[] = $row;
    }

    $smarty->assign('data', $invoices);
    $smarty->assign('totalOwed', $totalOwed);
} catch (PdoDbException $pde) {
    error_log("modules/reports/reportDebtorsByAmount.php Unexpected error:  {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');
