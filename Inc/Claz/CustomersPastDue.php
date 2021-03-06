<?php
namespace Inc\Claz;

use \Exception;

/**
 * Class for customer's past due calculation
 * @author Rich
 */
class CustomersPastDue {
    /**
     * Collect past dues information for each customer
     * @param string $language Language code (ex "en_US") used to set currency type.
     * @return array of past due information for customers.
     */
    public static function getCustInfo ($language) {
        global $pdoDb;

        $cust_info = array();
        try {
            $currency = new \Zend_Currency($language);

            $domain_id = DomainId::get();

            // Get date for 30 days ago. Only invoices with a date prior to this date are included.
            $past_due_date = (date("Y-m-d", strtotime('-30 days')) . ' 00:00:00');

            $pdoDb->addSimpleWhere("domain_id", $domain_id);
            $pdoDb->setOrderBy("cid");
            $pdoDb->setSelectList(array(new DbField('id', 'cid'), 'name'));
            $cust_rows = $pdoDb->request("SELECT", "customers");

            foreach ($cust_rows as $cust_row) {
                $cid = $cust_row['cid'];
                $name = $cust_row['name'];

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
                $pdoDb->addSimpleWhere("iv.domain_id", $domain_id, "AND");
                $pdoDb->addToWhere(new WhereItem(false, "iv.date", "<", $past_due_date, false));

                $pdoDb->setGroupBy("id");

                $pdoDb->setSelectList(new DbField("iv.id", "id"));

                $rows = $pdoDb->request("SELECT", "invoices", "iv");

                $payments = array();
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
                $pdoDb->addSimpleWhere("iv.domain_id", $domain_id, "AND");
                $pdoDb->addToWhere(new WhereItem(false, "iv.date", "<", $past_due_date, false));

                $pdoDb->setGroupBy("invoice_id");

                $pdoDb->setSelectList(array(new DbField("iv.id", "id"), 'index_id'));

                $rows = $pdoDb->request("SELECT", "invoices", "iv");

                $tot_billed = 0;
                $tot_paid = 0;
                $inv_info = array();
                foreach ($rows as $row) {
                    $id = $row['id'];
                    $index_id = $row['index_id'];
                    $billed = doubleval($row['billed']);
                    $paid = (empty($payments[$id]) ? 0.00 : $payments[$id]);
                    $owed = $billed - $paid;
                    if ($owed != 0) {
                        $fmtd_billed = $currency->toCurrency(doubleval($billed));
                        $fmtd_paid = $currency->toCurrency(doubleval($paid));
                        $fmtd_owed = $currency->toCurrency(doubleval($owed));

                        $inv_info[] = new InvInfo($id, $index_id, $fmtd_billed, $fmtd_paid, $fmtd_owed);

                        $tot_billed += $billed;
                        $tot_paid += $paid;
                    }
                }

                if (!empty($inv_info)) {
                    $tot_owed = $tot_billed - $tot_paid;
                    $fmtd_billed = $currency->toCurrency(doubleval($tot_billed));
                    $fmtd_paid = $currency->toCurrency(doubleval($tot_paid));
                    $fmtd_owed = $currency->toCurrency(doubleval($tot_owed));

                    $cust_info[$cid] = new CustInfo($name, $fmtd_billed, $fmtd_paid, $fmtd_owed, $inv_info);
                }
            }
        } catch (Exception $e) {
            error_log("CustomersPastDue::getCustInfo() - Error: " . $e->getMessage());
        }
        return $cust_info;
    }

    /**
     * Get the past dues amount for an invoice
     * @param integer $cid Customer ID value.
     * @param string $past_due_date Date before which invoices must have been issues.
     *                              Format is "yyyy-mm-dd hh:mm:ss".
     * @param integer $invoice_id ID of invoice NOT to include.
     *                              Defaults to NULL (all invoices included).
     * @return number Past due amount.
     */
    public static function getCustomerPastDue($cid, $past_due_date, $invoice_id = NULL) {
        global $pdoDb;

        $owed = 0;
        try {
            $domain_id = DomainId::get();

            $pdoDb->addSimpleWhere('iv.customer_id', $cid, 'AND');
            $pdoDb->addToWhere(new WhereItem(false, "iv.date", "<", $past_due_date, false, "AND"));
            if (isset($invoice_id)) {
                $pdoDb->addToWhere(new WhereItem(false, "iv.id", "<>", $invoice_id, false, "AND"));
            }
            $pdoDb->addSimpleWhere('iv.domain_id', $domain_id);

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
            $pdoDb->addToWhere(new WhereItem(false, "iv.date", "<", $past_due_date, false, "AND"));
            if (isset($invoice_id)) {
                $pdoDb->addToWhere(new WhereItem(false, "iv.id", "<>", $invoice_id, false, "AND"));
            }
            $pdoDb->addSimpleWhere('iv.domain_id', $domain_id);

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
            error_log("CustomersPastDue::getCustomerPastDue() - cid[$cid]  invoice_id[$invoice_id] past_due_date[$past_due_date] - error: " . $pde->getMessage());
        }
        return $owed;
    }

}
