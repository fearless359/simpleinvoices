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
global $pdoDb, $smarty;

Util::directAccessAllowed();

$domainId = DomainId::get();

// Get earliest invoice date
$rows = [];
try {
    $pdoDb->addToFunctions(new FunctionStmt("MIN", new DbField("date"), 'date'));
    $pdoDb->addSimpleWhere("domain_id", $domainId);

    $rows = $pdoDb->request("SELECT", "invoices");
} catch (PdoDbException $pde) {
    error_log('reportTaxVsSalesByPeriod.php - error(1): ' . $pde->getMessage());
}

if (empty($rows)) {
    $year = date('Y');
} else {
    $year = date('Y', strtotime($rows[0]['date']));
}

// Get total for each month of each year from first invoice
$thisYear = date('Y');

// loop for each year
$totalSales = [];
$totalPayments = [];
$taxSummary = [];
$years = [];
while($year <= $thisYear) {
    // loop for each month
    for ($mon = 1; $mon <= 12; $mon++) {
        // make month nice for mysql - accounts table doesn't like it if not 08 etc..
        $month = $mon < 10 ? "0{$mon}" : $mon;

        // Monthly taxes on sales
        try {
            $pdoDb->addToFunctions(new FunctionStmt('SUM', new DbField('iit.tax_amount'), 'totalMonthSales'));

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

            $pdoDb->addSimpleWhere('i.domain_id', $domainId, 'AND');
            $pdoDb->addSimpleWhere('p.status', ENABLED, 'AND');
            $pdoDb->addToWhere(new WhereItem(false, 'i.date', 'LIKE', "{$year}-{$month}%", false));

            $rows = $pdoDb->request('SELECT', 'invoice_item_tax', 'iit');
            $totalMonthSales = empty($rows) ? 0 : $rows[0]['totalMonthSales'];
        } catch (PdoDbException $pde) {
            error_log('reportTaxVsSalesByPeriod.php - error(2): ' . $pde->getMessage());
            $totalMonthSales = 0;
        }

        $totalSales[$year][$month] = $totalMonthSales;

        // Monthly payments applied
        try {
            $pdoDb->addToFunctions(new FunctionStmt('SUM', new DbField('et.tax_amount'), 'totalMonthPayments'));

            $jn = new Join('LEFT', 'expense', 'e');
            $jn->addSimpleItem('et.expense_id', new DbField('e.id'));
            $pdoDb->addToJoins($jn);

            $pdoDb->addSimpleWhere('e.domain_id', $domainId, 'AND');
            $pdoDb->addToWhere(new WhereItem(false, 'e.date', 'LIKE', "{$year}-{$month}%", false));

            $rows = $pdoDb->request('SELECT', 'expense_item_tax', 'et');
            $totalMonthPayments = empty($rows) ? 0 : $rows[0]['totalMonthPayments'];
        } catch (PdoDbException $pde) {
            error_log('reportTaxVsSalesByPeriod.php - error(3): ' . $pde->getMessage());
            $totalMonthPayments = 0;
        }

        $totalPayments[$year][$month] = $totalMonthPayments;

        $taxSummary[$year][$month] = $totalMonthSales - $totalMonthPayments;
    }

    $years[] = $year;
    $year++;
}

// Note that report uses the second index (month number and "Total") to build the
// heading line. So don't mess with these unless you know what you are doing. :-)
foreach ($years as $year) {
    for ($mon = 1; $mon <= 12; $mon++) {
        $month = $mon < 10 ? "0{$mon}" : $mon;
        if (!isset($totalSales[$year]['Total'])) {
            $totalSales[$year]['Total'] = 0;
        }
        $totalSales[$year]['Total'] += $totalSales[$year][$month];

        if (!isset($totalPayments[$year]['Total'])) {
            $totalPayments[$year]['Total'] = 0;
        }
        $totalPayments[$year]['Total'] += $totalPayments[$year][$month];

        if (!isset($taxSummary[$year]['Total'])) {
            $taxSummary[$year]['Total'] = 0;
        }
        $taxSummary[$year]['Total'] += $taxSummary[$year][$month];
    }
}

$smarty->assign('totalSales', $totalSales);
$smarty->assign('totalPayments', $totalPayments);
$smarty->assign('taxSummary', $taxSummary);
// $years = array(2006,2007,2008);
$years = array_reverse($years);
$smarty->assign('years', $years);
