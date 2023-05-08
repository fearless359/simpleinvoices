<?php

use Inc\Claz\CaseStmt;
use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Join;
use Inc\Claz\PdoDbException;
use Inc\Claz\Select;
use Inc\Claz\Util;

global $pdoDb, $smarty;

Util::directAccessAllowed();

/************************************************************************
/*  Get paid by aging period
 ************************************************************************/
try {
    $pdoDb->addToFunctions(new FunctionStmt('SUM', 'COALESCE(ap.ac_amount,0)', 'invPaid'));

    $pdoDb->addSimpleWhere('pr.pref_id', '1', 'AND');
    $pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
    $pdoDb->addSimpleWhere('ap.domain_id', DomainId::get());

    $pdoDb->setGroupBy('aging');
    $pdoDb->setOrderBy('aging');

    $fn = new FunctionStmt("IF", "(DateDiff(now(), iv.date) < 0), 0, DateDiff(now(), iv.date)");
    $se = new Select($fn, null, null, null, "Age");
    $pdoDb->addToSelectStmts($se);

    $ca = new CaseStmt("Age");
    $ca->addWhen("<=", "14", "0-14");
    $ca->addWhen("<=", "30", "15-30");
    $ca->addWhen("<=", "60", "31-60");
    $ca->addWhen("<=", "90", "61-90");
    $ca->addWhen(">", "90", "90+", true);
    $pdoDb->addToSelectStmts(new Select($ca, null, null, null, "aging"));

    $jn = new Join('LEFT', 'invoices', 'iv');
    $jn->addSimpleItem('ap.ac_inv_id', new DbField('iv.id'), 'AND');
    $jn->addSimpleItem('ap.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $jn = new Join('LEFT', 'preferences', 'pr');
    $jn->addSimpleItem('pr.pref_id', new DbField('iv.preference_id'), 'AND');
    $jn->addSimpleItem('pr.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $invPaid = $pdoDb->request('SELECT', 'payment', 'ap');

    /************************************************************************
     * /*  Get total billed by aging period
     ************************************************************************/
    $pdoDb->addSimpleWhere('pr.pref_id', '1', 'AND');
    $pdoDb->addSimpleWhere('pr.status', ENABLED, 'AND');
    $pdoDb->addSimpleWhere('ii.domain_id', DomainId::get());

    $pdoDb->setGroupBy('aging');
    $pdoDb->setOrderBy('aging');

    $pdoDb->addToFunctions(new FunctionStmt('SUM', 'COALESCE(ii.total, 0)', 'invTotal'));

    $fn = new FunctionStmt("IF", "(DateDiff(now(), iv.date) < 0), 0, DateDiff(now(), iv.date)");
    $se = new Select($fn, null, null, null, "Age");
    $pdoDb->addToSelectStmts($se);

    $ca = new CaseStmt("Age");
    $ca->addWhen("<=", "14", "0-14");
    $ca->addWhen("<=", "30", "15-30");
    $ca->addWhen("<=", "60", "31-60");
    $ca->addWhen("<=", "90", "61-90");
    $ca->addWhen(">", "90", "90+", true);
    $pdoDb->addToSelectStmts(new Select($ca, null, null, null, "aging"));

    $jn = new Join('LEFT', 'invoices', 'iv');
    $jn->addSimpleItem('ii.invoice_id', new DbField('iv.id'), 'AND');
    $jn->addSimpleItem('ii.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $jn = new Join('LEFT', 'preferences', 'pr');
    $jn->addSimpleItem('iv.preference_id', new DbField('pr.pref_id'), 'AND');
    $jn->addSimpleItem('pr.domain_id', new DbField('iv.domain_id'));
    $pdoDb->addToJoins($jn);

    $invTotal = $pdoDb->request('SELECT', 'invoice_items', 'ii');

    /************************************************************************
     * /*  Set up results with paid and then total billed
     ************************************************************************/
    $results = [];
    foreach ($invPaid as $paid) {
        $results[$paid['aging']] = [
            'total' => '0',
            'paid' => $paid['invPaid'],
            'owing' => 0
        ];
    }

    foreach ($invTotal as $total) {
        if (isset($results[$total['aging']])) {
            $results[$total['aging']]['total'] = $total['invTotal'];
        } else {
            $results[$total['aging']] = [
                'total' => $total['invTotal'],
                'paid' => 0,
                'owing' => 0
            ];
        }
    }

    /************************************************************************
     * /*  Calculate amount owing. If amount owing then include in summations
     *  and in the data array.
     ************************************************************************/
    $sumTotal = 0;
    $sumPaid = 0;
    $sumOwing = 0;
    $periods = [];
    foreach ($results as $key => $value) {
        $owing = $value['total'] - $value['paid'];
        if ($owing > 0) {
            $sumOwing += $owing;
            $sumTotal += $value['total'];
            $sumPaid += $value['paid'];
            $value['owing'] = $owing;
            $value['aging'] = $key;
            $periods[] = $value;
        }
    }

    $smarty->assign('data', $periods);
    $smarty->assign('sumTotal', $sumTotal);
    $smarty->assign('sumPaid', $sumPaid);
    $smarty->assign('sumOwing', $sumOwing);
} catch (PdoDbException $pde) {
    exit("modules/reports/reportDebtorsAgingTotal.php Unexpected error: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');
