<?php

namespace Inc\Claz;

use Exception;
use Zend_Currency;

/**
 * Class for customer's past due calculation
 * @author Rich
 */
class CustomersPastDue
{
    /**
     * Collect past dues information for each customer
     * @param string $language Language code (ex "en_US") used to set currency type.
     * @return array of past due information for customers.
     */
    public static function getCustInfo($language)
    {
        global $pdoDb;

        $custInfo = [];
        try {
            $currency = new Zend_Currency($language);

            $domainId = DomainId::get();

            // Get date for 30 days ago. Only invoices with a date prior to this date are included.
            $pastDueDate = date("Y-m-d", strtotime('-30 days')) . ' 00:00:00';

            $pdoDb->addSimpleWhere("domain_id", $domainId);
            $pdoDb->setOrderBy("cid");
            $pdoDb->setSelectList([new DbField('id', 'cid'), 'name']);
            $custRows = $pdoDb->request("SELECT", "customers");

            foreach ($custRows as $custRow) {
                $cid = $custRow['cid'];
                $name = $custRow['name'];

                $pdoDb->addToFunctions(new FunctionStmt('SUM', 'COALESCE(p.ac_amount, 0)', 'paid'));

                $jn = new Join("LEFT", "preferences", "pr");
                $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
                $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
                $pdoDb->addToJoins($jn);

                $jn = new Join("LEFT", "payment", "p");
                $jn->addSimpleItem("p.ac_inv_id", new DbField("iv.id"), "AND");
                $jn->addSimpleItem("p.domain_id", new DbField("iv.domain_id"));
                $pdoDb->addToJoins($jn);

                $pdoDb->addSimpleWhere('pr.status', '1', 'AND');
                $pdoDb->addSimpleWhere("iv.customer_id", $cid, "AND");
                $pdoDb->addSimpleWhere("iv.domain_id", $domainId, "AND");
                $pdoDb->addToWhere(new WhereItem(false, "iv.date", "<", $pastDueDate, false));

                $pdoDb->setGroupBy("id");

                $pdoDb->setSelectList(new DbField("iv.id", "id"));

                $rows = $pdoDb->request("SELECT", "invoices", "iv");

                $payments = [];
                foreach ($rows as $row) {
                    $payments[$row['id']] = doubleval($row['paid']);
                }

                $pdoDb->addToFunctions(new FunctionStmt('SUM', 'COALESCE(ii.total, 0)', 'billed'));

                $jn = new Join("LEFT", "preferences", "pr");
                $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
                $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
                $pdoDb->addToJoins($jn);

                $jn = new Join("LEFT", "invoice_items", "ii");
                $jn->addSimpleItem("ii.invoice_id", new DbField("iv.id"), "AND");
                $jn->addSimpleItem("ii.domain_id", new DbField("iv.domain_id"));
                $pdoDb->addToJoins($jn);

                $pdoDb->addSimpleWhere('pr.status', '1', 'AND');
                $pdoDb->addSimpleWhere("iv.customer_id", $cid, "AND");
                $pdoDb->addSimpleWhere("iv.domain_id", $domainId, "AND");
                $pdoDb->addToWhere(new WhereItem(false, "iv.date", "<", $pastDueDate, false));

                $pdoDb->setGroupBy("invoice_id");

                $pdoDb->setSelectList([new DbField("iv.id", "id"), 'index_id']);

                $rows = $pdoDb->request("SELECT", "invoices", "iv");

                $totBilled = 0;
                $totPaid = 0;
                $invInfo = [];
                foreach ($rows as $row) {
                    $id = $row['id'];
                    $indexId = $row['index_id'];
                    $billed = doubleval($row['billed']);
                    $paid = empty($payments[$id]) ? 0.00 : $payments[$id];
                    $owed = $billed - $paid;
                    if ($owed != 0) {
                        $fmtdBilled = $currency->toCurrency(doubleval($billed));
                        $fmtdPaid = $currency->toCurrency(doubleval($paid));
                        $fmtdOwed = $currency->toCurrency(doubleval($owed));

                        $invInfo[] = new InvInfo($id, $indexId, $fmtdBilled, $fmtdPaid, $fmtdOwed);

                        $totBilled += $billed;
                        $totPaid += $paid;
                    }
                }

                if (!empty($invInfo)) {
                    $totOwed = $totBilled - $totPaid;
                    $fmtdBilled = $currency->toCurrency(doubleval($totBilled));
                    $fmtdPaid = $currency->toCurrency(doubleval($totPaid));
                    $fmtdOwed = $currency->toCurrency(doubleval($totOwed));

                    $custInfo[$cid] = new CustInfo($name, $fmtdBilled, $fmtdPaid, $fmtdOwed, $invInfo);
                }
            }
        } catch (Exception $exp) {
            error_log("CustomersPastDue::getCustInfo() - Error: " . $exp->getMessage());
        }
        return $custInfo;
    }

    /**
     * Get the past dues amount for an invoice
     * @param int $cid Customer ID value.
     * @param string $pastDueDate Date before which invoices must have been issues.
     *                              Format is "yyyy-mm-dd hh:mm:ss".
     * @param int|null $invoice_id ID of invoice NOT to include.
     *                              Defaults to null (all invoices included).
     * @return float Past due amount.
     */
    public static function getCustomerPastDue(int $cid, string $pastDueDate, ?int $invoice_id = null): float
    {
        global $pdoDb;

        $owed = 0;
        try {
            $domainId = DomainId::get();

            $pdoDb->addSimpleWhere('iv.customer_id', $cid, 'AND');
            $pdoDb->addToWhere(new WhereItem(false, "iv.date", "<", $pastDueDate, false, "AND"));
            if (isset($invoice_id)) {
                $pdoDb->addToWhere(new WhereItem(false, "iv.id", "<>", $invoice_id, false, "AND"));
            }
            $pdoDb->addSimpleWhere('iv.domain_id', $domainId);

            // Get previously billed on all invoices
            $pdoDb->addToFunctions("SUM(IF(pr.set_aging = " . ENABLED . ", COALESCE(ii.total, 0), 0)) AS billed");

            $jn = new Join("LEFT", "preferences", "pr");
            $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
            $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join("LEFT", "invoice_items", "ii");
            $jn->addSimpleItem("ii.invoice_id", new DbField("iv.id"), "AND");
            $jn->addSimpleItem("ii.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $pdoDb->setGroupBy("iv.id");

            $pdoDb->setSelectList(new DbField("iv.id", "id"));

            $rows = $pdoDb->request("SELECT", "invoices", "iv");

            $billed = 0;
            foreach ($rows as $row) {
                $billed += doubleval($row['billed']);
            }

            $pdoDb->addSimpleWhere('iv.customer_id', $cid, 'AND');
            $pdoDb->addToWhere(new WhereItem(false, "iv.date", "<", $pastDueDate, false, "AND"));
            if (!empty($invoice_id)) {
                $pdoDb->addToWhere(new WhereItem(false, "iv.id", "<>", $invoice_id, false, "AND"));
            }
            $pdoDb->addSimpleWhere('iv.domain_id', $domainId);

            // Get paid on all invoices
            $pdoDb->addToFunctions("SUM(IF(pr.set_aging = " . ENABLED . ", COALESCE(p.ac_amount, 0), 0)) AS paid");

            $jn = new Join("LEFT", "preferences", "pr");
            $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
            $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join("LEFT", "payment", "p");
            $jn->addSimpleItem("p.ac_inv_id", new DbField("iv.id"), "AND");
            $jn->addSimpleItem("p.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $pdoDb->setGroupBy("iv.id");

            $pdoDb->setSelectList(new DbField("iv.id", "id"));

            $rows = $pdoDb->request("SELECT", "invoices", "iv");

            $paid = 0;
            foreach ($rows as $row) {
                $paid += doubleval($row['paid']);
            }

            $owed = round($billed - $paid, 2);
        } catch (PdoDbException $pde) {
            error_log("CustomersPastDue::getCustomerPastDue() - cid[$cid]  invoice_id[$invoice_id] past_due_date[$pastDueDate] - error: " . $pde->getMessage());
        }
        return $owed;
    }

}
