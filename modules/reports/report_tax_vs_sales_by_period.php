<?php
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
 *     https://simpleinvoices.group */
global$pdoDb, $smarty;

checkLogin();

$domain_id = domain_id::get();

// Get earliest invoice date

//$sql = "SELECT MIN(date) AS date FROM " . TB_PREFIX . "invoices WHERE domain_id = :domain_id";
//$sth = dbQuery($sql, ':domain_id', $domain_id);
//$invoice_start_array = $sth->fetch();
$rows = array();
try {
    $pdoDb->addToFunctions(new FunctionStmt("MIN", new DbField("date"), 'date'));
    $pdoDb->addSimpleWhere("domain_id", $domain_id);

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
$this_year = date('Y');

// loop for each year
$total_sales    = array();
$tax_summary    = array();
$total_payments = array();
$years          = array();
while($year <= $this_year) {
    // loop for each month
    for ($i = 1; $i <= 12; $i++) {
        // make month nice for mysql - accounts table doesn't like it if not 08 etc..
        if ($i < 10) {
            $month = "0" . $i;
        } else {
            $month = $i;
        }

//          $total_month_sales_sql = "SELECT SUM(iit.tax_amount) AS month_total
//          FROM " . TB_PREFIX . "invoice_item_tax iit
//          LEFT JOIN " . TB_PREFIX . "invoice_items ii ON (iit.invoice_item_id = ii.id)
//          LEFT JOIN " . TB_PREFIX . "invoices i ON (i.id = ii.invoice_id AND i.domain_id = ii.domain_id)
//          LEFT JOIN " . TB_PREFIX . "preferences p ON (i.preference_id = p.pref_id AND i.domain_id = p.domain_id)
//          WHERE ii.domain_id = :domain_id
//            AND p.status = '1'
//            AND i.date LIKE '$year-$month%'";
//         $sth = dbQuery($total_month_sales_sql, ':domain_id', $domain_id);
//         $total_month_sales_array = $sth->fetch();

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

            $pdoDb->addSimpleWhere('ii.domain_id', domain_id::get(), 'AND');
            $pdoDb->addSimpleWhere('p.status', ENABLED, 'AND');
            $pdoDb->addToWhere(new WhereItem(false, 'i.date', 'LIKE', "{$year}-{$month}", false));

            $rows = $pdoDb->request('SELECT', 'invoice_item_tax', 'iit');
        } catch (PdoDbException $pde) {
            error_log('report_tax_vs_sales_by_period.php - error(2): ' . $pde->getMessage());
        }

        if (empty($rows)) {
            $month_totals = 0;
        } else {
            $month_totals = $rows[0]['month_total'];
        }

        $total_sales[$year][$month] = $month_totals;
        if (empty($total_sales[$year][$month])) {
            $total_sales[$year][$month] = "-";
        }

//        $total_month_payments_sql = "SELECT SUM(et.tax_amount) AS month_total_payments
//            FROM " . TB_PREFIX . "expense_item_tax et
//            LEFT JOIN " . TB_PREFIX . "expense e ON (e.id = et.expense_id)
//            WHERE e.domain_id = :domain_id
//              AND e.date LIKE '$year-$month%'";
//
//        $total_month_payments = dbQuery($total_month_payments_sql, ':domain_id', $domain_id);
//        $total_month_payments_array = $total_month_payments->fetch();

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

            $pdoDb->addSimpleWhere('ii.domain_id', domain_id::get(), 'AND');
            $pdoDb->addSimpleWhere('p.status', ENABLED, 'AND');
            $pdoDb->addToWhere(new WhereItem(false, 'i.date', 'LIKE', "{$year}-{$month}", false));

            $rows = $pdoDb->request('SELECT', 'invoice_item_tax', 'iit');
        } catch (PdoDbException $pde) {
            error_log('report_tax_vs_sales_by_period.php - error(3): ' . $pde->getMessage());
        }

        if (empty($rows)) {
            $month_total_payments = 0;
        } else {
            $month_total_payments = $rows[0]['month_total_payments'];
        }

        $tax_summary[$year][$month] = $month_totals - $month_total_payments;
        if (empty($total_summary[$year][$month])) {
            $tax_summary[$year][$month] = "-";
        }
    }

//    $sql = "SELECT SUM(iit.tax_amount) AS year_total
//            FROM " . TB_PREFIX . "invoice_item_tax iit
//            LEFT JOIN " . TB_PREFIX . "invoice_items ii ON (iit.invoice_item_id = ii.id)
//            LEFT JOIN " . TB_PREFIX . "invoices i ON (i.id = ii.invoice_id AND i.domain_id = ii.domain_id)
//            LEFT JOIN " . TB_PREFIX . "preferences p ON (i.preference_id = p.pref_id AND i.domain_id = p.domain_id)
//            WHERE ii.domain_id = :domain_id
//              AND p.status = '1'
//              AND i.date LIKE '$year%'";
//    $sth = dbQuery($sql, ':domain_id', $domain_id);
//    $row = $sth->fetch();

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

        $pdoDb->addSimpleWhere('ii.domain_id', $domain_id, 'AND');
        $pdoDb->addSimpleWhere('p.status', ENABLED, 'AND');
        $pdoDb->addToWhere(new WhereItem(false, 'i.date', 'LIKE', "{$year}%", false));

        $rows = $pdoDb->request('SELECT', 'invoice_item_tax', 'iit');
        $year_total = (empty($rows) ? 0 : $rows[0]['year_total']);
    } catch (PdoDbException $pde) {
        error_log('report_tax_vs_sales_by_period.php - error(4): ' . $pde->getMessage());
        $year_total = 0;
    }

    $total_sales[$year]['Total'] = $year_total;
    if (empty($total_sales[$year]['Total'])) {
        $total_sales[$year]['Total'] = "-";
    }
    
//    $sql = "SELECT SUM(et.tax_amount) AS year_total_payments
//            FROM " . TB_PREFIX . "expense_item_tax et
//            LEFT JOIN " . TB_PREFIX . "expense e ON (e.id = et.expense_id)
//            WHERE e.domain_id = :domain_id
//              AND e.date LIKE '$year%'";
//    $sth = dbQuery($sql, ':domain_id', $domain_id);
//    $row = $sth->fetch();

    // Payment
    try {
        $pdoDb->addToFunctions(new FunctionStmt('SUM', new DbField('et.tax_amount'), 'year_total_payments'));

        $jn = new Join('LEFT', 'expense', 'e');
        $jn->addSimpleItem('e.id', new DbField('et.expense_id'));
        $pdoDb->addToJoins($jn);

        $pdoDb->addSimpleWhere('e.domain_id', $domain_id, 'AND');
        $pdoDb->addToWhere(new WhereItem(false, 'e.date', 'LIKE', "{$year}%", false));

        $rows = $pdoDb->request('SELECT', 'expense_item_tax', 'et');
        $year_total_payments = (empty($rows) ? 0 : $rows['year_total_payments']);
    } catch (PdoDbException $pde) {
        error_log('report_tax_vs_sales_by_period.php - error(5): ' . $pde->getMessage());
        $year_total_payments = 0;
    }

    $total_payments[$year]['Total'] = $year_total_payments;
    if (empty($total_payments[$year]['Total'])) {
        $total_payments[$year]['Total'] = "-";
    }
    
    $tax_summary[$year]['Total'] = $year_tota - $year_total_payments;
    if (empty($tax_summary[$year]['Total'])) {
        $tax_summary[$year]['Total'] = "-";
    }

    $years[] = $year;
    $year++;
}

$smarty->assign('total_sales', $total_sales);
$smarty->assign('total_payments', $total_payments);
$smarty->assign('tax_summary', $tax_summary);
// $years = array(2006,2007,2008);
$years = array_reverse($years);
$smarty->assign('years', $years);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
