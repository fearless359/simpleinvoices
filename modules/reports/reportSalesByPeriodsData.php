<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\FunctionStmt;
use Inc\Claz\Join;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;
use Inc\Claz\WhereItem;

global $pdoDb, $smarty;

Util::directAccessAllowed();

/**
 * Calculate the current rate.
 * @param float $thisYearAmount
 * @param float $lastYearAmount
 * @param int $precision
 * @return float
 */
function calcMyRate(float $thisYearAmount, float $lastYearAmount, int $precision = 2): float
{
    if ($lastYearAmount == 0) {
        return 0;
    }
    return round(($thisYearAmount - $lastYearAmount) / $lastYearAmount * 100, $precision);
}

// showRates value can come from form submit, or from a link parameter.
if (isset($_POST['showRates'])) {
    $showRates = $_POST['showRates'];
} elseif (isset($_GET['showRates'])) {
    $showRates = $_GET['showRates'];
} else {
    $showRates = 'no';
}
$smarty->assign("showRates", $showRates);

$maxYears = 10;
$domainId = DomainId::get();

// Get earliest invoice date
$rows = [];
try {
    $pdoDb->addToFunctions(new FunctionStmt("MIN", new DbField("iv.date"), "date"));

    $jn = new Join("INNER", "preferences", "pr");
    $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
    $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
    $pdoDb->addToJoins($jn);

    $pdoDb->addSimpleWhere("pr.status", ENABLED, "AND");
    $pdoDb->addSimpleWhere("iv.domain_id", $domainId);

    $rows = $pdoDb->request("SELECT", "invoices", "iv");
} catch (PdoDbException $pde) {
    error_log("module/reports/report_sales_by_period.php - error(1): " . $pde->getMessage());
}

$firstInvoiceYear = intval(date('Y', strtotime($rows[0]['date'])));

// Get total for each month of each year from first invoice
$thisYear = intval(date('Y'));
$year = $firstInvoiceYear;

$totalYears = $thisYear - $firstInvoiceYear + 1;
if ($totalYears > $maxYears) {
    $year = $thisYear - $maxYears + 1;
}

// loop for each year
$years = [];
$data = [];
while ($year <= $thisYear) {
    // loop for each month
    $month = 1;
    while ($month <= 12) {
        // make month nice for mysql - accounts table doesn't like it if not 08 etc..
        if ($month < 10) {
            $month = "0" . $month;
        }

        // Monthly Sales ----------------------------
        $rows = "";
        try {
            $pdoDb->addToFunctions(new FunctionStmt("SUM", new DbField("ii.total"), "month_total"));

            $jn = new Join("INNER", "invoices", "iv");
            $jn->addSimpleItem("iv.id", new DbField("ii.invoice_id"), "AND");
            $jn->addSimpleItem("iv.domain_id", new DbField("ii.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join("INNER", "preferences", "pr");
            $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
            $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $pdoDb->addSimpleWhere("pr.status", ENABLED, "AND");
            $pdoDb->addSimpleWhere("pr.domain_id", new DbField("iv.domain_id"), "AND");
            $pdoDb->addToWhere(new WhereItem(false, "iv.date", "LIKE", "$year-$month%", false));

            $rows = $pdoDb->request("SELECT", "invoice_items", "ii");
        } catch (PdoDbException $pde) {
            error_log("modules/reports/reportSalesByPeriods.php - error(2): " . $pde->getMessage());
        }
        $data['sales']['months'][$month][$year] = $rows[0]['month_total'] ?? 0;

        $prevYear = $data['sales']['months'][$month][$year - 1] ?? 0;
        $data['sales']['monthsRate'][$month][$year] = calcMyRate($data['sales']['months'][$month][$year], $prevYear);

        // Monthly Payment ----------------------------
        $rows = [];
        try {
            $pdoDb->addToFunctions(new FunctionStmt("SUM", new DbField("ac_amount"), "month_total_payments"));
            $pdoDb->addSimpleWhere("domain_id", $domainId, "AND");
            $pdoDb->addToWhere(new WhereItem(false, "ac_date", "LIKE", "$year-$month%", false));

            $rows = $pdoDb->request("SELECT", "payment");
        } catch (PdoDbException $pde) {
            error_log("modules/reports/reportSalesByPeriods.php - error(3): " . $pde->getMessage());
        }
        $data['payments']['months'][$month][$year] = $rows[0]['month_total_payments'] ?? 0;

        $prevYear = $data['payment']['months'][$month][$year - 1] ?? 0;
        $data['payments']['monthsRate'][$month][$year] = calcMyRate($data['payments']['months'][$month][$year], $prevYear);

        $month++;
    }

    // Total Annual Sales ----------------------------
    $rows = [];
    try {
        $pdoDb->addToFunctions(new FunctionStmt("SUM", new DbField("ii.total"), "year_total"));

        $jn = new Join("INNER", "invoices", "iv");
        $jn->addSimpleItem("ii.invoice_id", new DbField("iv.id"), "AND");
        $jn->addSimpleItem("iv.domain_id", new DbField("ii.domain_id"));
        $pdoDb->addToJoins($jn);

        $jn = new Join("INNER", "preferences", "pr");
        $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
        $jn->addSimpleItem("ii.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins($jn);

        $pdoDb->addSimpleWhere("pr.status", ENABLED, "AND");
        $pdoDb->addSimpleWhere("ii.domain_id", $domainId, "AND");
        $pdoDb->addToWhere(new WhereItem(false, "iv.date", "LIKE", "$year%", false));
        $rows = $pdoDb->request("SELECT", "invoice_items", "ii");
    } catch (PdoDbException $pde) {
        error_log("modules/reports/reportSalesByPeriods.php - error(4): " . $pde->getMessage());
    }
    $data['sales']['total'][$year] = $rows[0]['year_total'];

    $prevYear = $data['sales']['total'][$year - 1] ?? 0;
    $data['sales']['totalRate'][$year] = calcMyRate($data['sales']['total'][$year], $prevYear);

    // Total Annual Payment ----------------------------
    $rows = [];
    try {
        $pdoDb->addToFunctions(new FunctionStmt("SUM", "ac_amount", "year_total_payments"));
        $pdoDb->addSimpleWhere("domain_id", $domainId, "AND");
        $pdoDb->addToWhere(new WhereItem(false, "ac_date", "LIKE", "$year%", false));
        $rows = $pdoDb->request("SELECT", "payment");
    } catch (PdoDbException $pde) {
        error_log("modules/reports/reportSalesByPeriods.php - error(3): " . $pde->getMessage());
    }
    $data['payments']['total'][$year] = $rows[0]['year_total_payments'];

    $prevYear = $data['payments']['total'][$year - 1] ?? 0;
    $data['payments']['totalRate'][$year] = calcMyRate($data['payments']['total'][$year], $prevYear);

    $years[] = $year;
    $year++;
}
$years = array_reverse($years);

$smarty->assign('data', $data);
$smarty->assign('allYears', $years);
