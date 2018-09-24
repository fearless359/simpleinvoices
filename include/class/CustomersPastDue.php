<?php
/**
 * Class for customer's past due calculation
 * @author Rich
 */
class CustomersPastDue {
    /**
     * Collect past dues information for each customer
     * @param string $language Language code (ex "en_US") used to set currency type.
     * @return CustInfo[] Array of past due information for customers.
     * @throws PdoDbException
     * @throws Zend_Currency_Exception
     */
    public static function getCustInfo ($language) {
        global $pdoDb;

        $currency = new Zend_Currency($language);
        
        $domain_id = domain_id::get();
    
        // Get date for 30 days ago. Only invoices with a date prior to this date are included.
        $past_due_date = (date("Y-m-d", strtotime('-30 days')) . ' 00:00:00');
        
        $cust_info = array();

        $pdoDb->addSimpleWhere("domain_id", $domain_id);
        $pdoDb->setOrderBy("cid");
        $pdoDb->setSelectList(array(new DbField('id', 'cid'), 'name'));
        $cust_rows = $pdoDb->request("SELECT", "customers");

        foreach($cust_rows as $cust_row) {
            $cid = $cust_row['cid'];
            $name = $cust_row['name'];

            $pdoDb->addToFunctions("SUM(IF(pr.status = 1, COALESCE(p.ac_amount, 0), 0)) AS paid");

            $jn = new Join("LEFT", "preferences", "pr");
            $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
            $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join("LEFT", "payment", "p");
            $jn->addSimpleItem("p.ac_inv_id", new DbField("iv.id"), "AND");
            $jn->addSimpleItem("p.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $pdoDb->addSimpleWhere("iv.customer_id", $cid, "AND");
            $pdoDb->addSimpleWhere("iv.domain_id", $domain_id, "AND");
            $pdoDb->addToWhere(new WhereItem(false,"iv.date", "<", $past_due_date, false));

            $pdoDb->setGroupBy("id");

            $pdoDb->setSelectList(new DbField("iv.id", "id"));

            $rows = $pdoDb->request("SELECT", "invoices", "iv");

            $payments = array();
            foreach ($rows as $row) {
                $payments[$row['id']] = doubleval($row['paid']);
            }

            $pdoDb->addToFunctions("SUM(IF(pr.status = 1, COALESCE(ii.total, 0), 0)) AS billed");

            $jn = new Join("LEFT", "preferences", "pr");
            $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
            $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join("LEFT", "invoice_items", "ii");
            $jn->addSimpleItem("ii.invoice_id", new DbField("iv.id"), "AND");
            $jn->addSimpleItem("ii.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $pdoDb->addSimpleWhere("iv.customer_id", $cid, "AND");
            $pdoDb->addSimpleWhere("iv.domain_id", $domain_id, "AND");
            $pdoDb->addToWhere(new WhereItem(false,"iv.date", "<", $past_due_date, false));

            $pdoDb->setGroupBy("invoice_id");

            $pdoDb->setSelectList(new DbField("iv.id", "id"));

            $rows = $pdoDb->request("SELECT", "invoices", "iv");

            $tot_billed = 0;
            $tot_paid   = 0;
            $inv_info   = array();
            foreach ($rows as $row) {
                $id     = $row['id'];
                $billed =  doubleval($row['billed']);
                $paid   = (empty($payments[$id]) ? 0.00 : $payments[$id]);
                $owed   = $billed - $paid;
                if ($owed != 0) {
                    $fmtd_billed = $currency->toCurrency(doubleval($billed));
                    $fmtd_paid   = $currency->toCurrency(doubleval($paid));
                    $fmtd_owed   = $currency->toCurrency(doubleval($owed));

                    $inv_info[] = new InvInfo($id, $fmtd_billed, $fmtd_paid, $fmtd_owed);

                    $tot_billed += $billed;
                    $tot_paid   += $paid;
                }
            }

            if (!empty($inv_info)) {
                $tot_owed    = $tot_billed - $tot_paid;
                $fmtd_billed = $currency->toCurrency(doubleval($tot_billed));
                $fmtd_paid   = $currency->toCurrency(doubleval($tot_paid));
                $fmtd_owed   = $currency->toCurrency(doubleval($tot_owed));

                $cust_info[$cid] = new CustInfo($name, $fmtd_billed, $fmtd_paid, $fmtd_owed, $inv_info);
            }
        }
        return $cust_info;
    }

    /**
     * Get the past dues amount for an invoice
     * @param integer $cid Customer ID value.
     * @param integer $invoice_id Invoice ID value.
     * @param string $invoice_dt Date before which invoices must have been issues.
     *               Format is "yyyy-mm-dd hh:mm:ss".
     * @return number Past due amount.
     * @throws PdoDbException
     */
    public static function getCustomerPastDue($cid, $invoice_id, $invoice_dt) {
        global $pdoDb;

        $domain_id = domain_id::get();

        $pdoDb->addToFunctions("SUM(IF(pr.status = 1, COALESCE(ii.total, 0), 0)) AS billed");

        $jn = new Join("LEFT", "preferences", "pr");
        $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
        $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins($jn);

        $jn = new Join("LEFT", "invoice_items", "ii");
        $jn->addSimpleItem("ii.invoice_id", new DbField("iv.id"), "AND");
        $jn->addSimpleItem("ii.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins($jn);

        $pdoDb->addSimpleWhere("iv.customer_id", $cid, "AND");
        $pdoDb->addSimpleWhere("iv.id", $invoice_id, "AND");
        $pdoDb->addToWhere(new WhereItem(false, "iv.date", "<", $invoice_dt, false, "AND"));
        $pdoDb->addSimpleWhere("iv.domain_id", $domain_id);

        $pdoDb->setGroupBy("iv.id");

        $pdoDb->setSelectList(new DbField("iv.id", "id"));

        $rows = $pdoDb->request("SELECT", "invoices", "iv");

        $billed = 0;
        foreach ($rows as $row) {
            $billed += doubleval($row['billed']);
        }

        $pdoDb->addToFunctions("SUM(COALESCE(IF(pr.status = 1, p.ac_amount, 0), 0)) AS paid");

        $jn = new Join("LEFT", "preferences", "pr");
        $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
        $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins($jn);

        $jn = new Join("LEFT", "payment", "p");
        $jn->addSimpleItem("p.ac_inv_id", new DbField("iv.id"), "AND");
        $jn->addSimpleItem("p.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins($jn);

        $pdoDb->addSimpleWhere("iv.customer_id", $cid, "AND");
        $pdoDb->addSimpleWhere("iv.id", $invoice_id, "AND");
        $pdoDb->addToWhere(new WhereItem(false, "iv.date", "<", $invoice_dt, false, "AND"));
        $pdoDb->addSimpleWhere("iv.domain_id", $domain_id);

        $pdoDb->setGroupBy("iv.id");

        $pdoDb->setSelectList(new DbField("iv.id", "id"));

        $rows = $pdoDb->request("SELECT", "invoices", "iv");

        $paid = 0;
        foreach ($rows as $row) {
            $paid += doubleval($row['paid']);
        }

        $owed = round($billed - $paid, 2);
    
        return $owed;
    }

}
