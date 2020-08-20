<?php /** @noinspection DuplicatedCode */

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Join;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;
use Inc\Claz\WhereItem;

/*
 * Script: report_sales_by_period.php
 *     Sales reports by period add page
 *
 * Authors:
 *     Justin Kelly
 *
 * Last edited:
 *      2008-05-13
 *
 * License:
 *     GPL v3
 *
 * Website:
 *     https://simpleinvoices.group
 */
global$pdoDb, $smarty;

Util::directAccessAllowed();

$domainId = DomainId::get();

// Get earliest invoice date
$rows = [];
try {
    $pdoDb->addToFunctions(new FunctionStmt("MIN", new DbField("date"), 'date'));
    $pdoDb->addSimpleWhere("domain_id", $domainId);

    $rows = $pdoDb->request("SELECT", "invoices");
} catch (PdoDbException $pde) {
    error_log('report_tax_vs_sales_by_period.php - error(1): ' . $pde->getMessage());
}

if (empty($rows)) {
    $year = date('Y');
} else {
    $year = date('Y', strtotime($rows[0]['date']));
}

// Get total for each month of each year from first invoice
$thisYear = date('Y');

// loop for each year
$totalSales    = [];
$taxSummary    = [];
$totalPayments = [];
$years         = [];
while($year <= $thisYear) {
    // loop for each month
    for ($idx = 1; $idx <= 12; $idx++) {
        // make month nice for mysql - accounts table doesn't like it if not 08 etc..
        if ($idx < 10) {
            $month = "0" . $idx;
        } else {
            $month = $idx;
        }

        try {
            $pdoDb->addToFunctions(new FunctionStmt('SUM', new DbField('iit.tax_amount'), 'month_total'));

            $jn = new Join('LEFT', 'invoice_items', 'ii');
            $jn->addSimpleItem('iit.invoice_item_id', new DbField('ii.id'));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'invoices', 'i');
            $jn->addSimpleItem('i.id', new DbField('ii.invoice_id'), 'AND');
            $jn->addSimpleItem('i.domain_id', new DbField('ii.domain_id'));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'preferences', 'p');
            $jn->addSimpleItem('p.pref_id', new DbField('i.preference_id'), 'AND');
            $jn->addSimpleItem('p.domain_id', new DbField('i.domain_id'));
            $pdoDb->addToJoins($jn);

            $pdoDb->addSimpleWhere('ii.domain_id', DomainId::get(), 'AND');
            $pdoDb->addSimpleWhere('p.status', ENABLED, 'AND');
            $pdoDb->addToWhere(new WhereItem(false, 'i.date', 'LIKE', "{$year}-{$month}", false));

            $rows = $pdoDb->request('SELECT', 'invoice_item_tax', 'iit');
        } catch (PdoDbException $pde) {
            error_log('report_tax_vs_sales_by_period.php - error(2): ' . $pde->getMessage());
        }

        if (empty($rows)) {
            $monthTotals = 0;
        } else {
            $monthTotals = $rows[0]['month_total'];
        }

        $totalSales[$year][$month] = $monthTotals;
        if (empty($totalSales[$year][$month])) {
            $totalSales[$year][$month] = "-";
        }

        // Payment
        try {
            $pdoDb->addToFunctions(new FunctionStmt('SUM', new DbField('iit.tax_amount'), 'month_total'));

            $jn = new Join('LEFT', 'invoice_items', 'ii');
            $jn->addSimpleItem('iit.invoice_item_id', new DbField('ii.id'));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'invoices', 'i');
            $jn->addSimpleItem('i.id', new DbField('ii.invoice_id'), 'AND');
            $jn->addSimpleItem('i.domain_id', new DbField('ii.domain_id'));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'preferences', 'p');
            $jn->addSimpleItem('p.pref_id', new DbField('i.preference_id'), 'AND');
            $jn->addSimpleItem('p.domain_id', new DbField('i.domain_id'));
            $pdoDb->addToJoins($jn);

            $pdoDb->addSimpleWhere('ii.domain_id', DomainId::get(), 'AND');
            $pdoDb->addSimpleWhere('p.status', ENABLED, 'AND');
            $pdoDb->addToWhere(new WhereItem(false, 'i.date', 'LIKE', "{$year}-{$month}", false));

            $rows = $pdoDb->request('SELECT', 'invoice_item_tax', 'iit');
        } catch (PdoDbException $pde) {
            error_log('report_tax_vs_sales_by_period.php - error(3): ' . $pde->getMessage());
        }

        if (empty($rows)) {
            $monthTotalPayments = 0;
        } else {
            $monthTotalPayments = $rows[0]['month_total_payments'];
        }

        $taxSummary[$year][$month] = $monthTotals - $monthTotalPayments;
        if (empty($total_summary[$year][$month])) {
            $taxSummary[$year][$month] = "-";
        }
    }

    // Sales
    try {
        $pdoDb->addToFunctions(new FunctionStmt('SUM', new DbField('iit.tax_amount'), 'year_total'));

        $jn = new Join('LEFT', 'invoice_items', 'ii');
        $jn->addSimpleItem('iit.invoice_item_id', new DbField('ii.id'));
        $pdoDb->addToJoins($jn);

        $jn = new Join('LEFT', 'invoices', 'i');
        $jn->addSimpleItem('i.id', new DbField('ii.invoice_id'), 'AND');
        $jn->addSimpleItem('i.domain_id', new DbField('ii.domain_id'));
        $pdoDb->addToJoins($jn);

        $jn = new Join('LEFT', 'preferences', 'p');
        $jn->addSimpleItem('i.preference_id', new DbField('p.pref_id'), 'AND');
        $jn->addSimpleItem('i.domain_id', new DbField('p.domain_id'));
        $pdoDb->addToJoins($jn);

        $pdoDb->addSimpleWhere('ii.domain_id', $domainId, 'AND');
        $pdoDb->addSimpleWhere('p.status', ENABLED, 'AND');
        $pdoDb->addToWhere(new WhereItem(false, 'i.date', 'LIKE', "{$year}%", false));

        $rows = $pdoDb->request('SELECT', 'invoice_item_tax', 'iit');
        $yearTotal = empty($rows) ? 0 : $rows[0]['year_total'];
    } catch (PdoDbException $pde) {
        error_log('report_tax_vs_sales_by_period.php - error(4): ' . $pde->getMessage());
        $yearTotal = 0;
    }

    $totalSales[$year]['Total'] = $yearTotal;
    if (empty($totalSales[$year]['Total'])) {
        $totalSales[$year]['Total'] = "-";
    }

    // Payment
    try {
        $pdoDb->addToFunctions(new FunctionStmt('SUM', new DbField('et.tax_amount'), 'year_total_payments'));

        $jn = new Join('LEFT', 'expense', 'e');
        $jn->addSimpleItem('e.id', new DbField('et.expense_id'));
        $pdoDb->addToJoins($jn);

        $pdoDb->addSimpleWhere('e.domain_id', $domainId, 'AND');
        $pdoDb->addToWhere(new WhereItem(false, 'e.date', 'LIKE', "{$year}%", false));

        $rows = $pdoDb->request('SELECT', 'expense_item_tax', 'et');
        $yearTotalPayments = empty($rows) ? 0 : $rows['year_total_payments'];
    } catch (PdoDbException $pde) {
        error_log('report_tax_vs_sales_by_period.php - error(5): ' . $pde->getMessage());
        $yearTotalPayments = 0;
    }

    $totalPayments[$year]['Total'] = $yearTotalPayments;
    if (empty($totalPayments[$year]['Total'])) {
        $totalPayments[$year]['Total'] = "-";
    }
    
    $taxSummary[$year]['Total'] = $yearTotal - $yearTotalPayments;
    if (empty($taxSummary[$year]['Total'])) {
        $taxSummary[$year]['Total'] = "-";
    }

    $years[] = $year;
    $year++;
}

$smarty->assign('total_sales', $totalSales);
$smarty->assign('total_payments', $totalPayments);
$smarty->assign('tax_summary', $taxSummary);
// $years = array(2006,2007,2008);
$years = array_reverse($years);
$smarty->assign('years', $years);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
