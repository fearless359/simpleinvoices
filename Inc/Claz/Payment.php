<?php

namespace Inc\Claz;

/**
 * Class Payment
 * @package Inc\Claz
 */
class Payment
{
    /**
     * Count of optionally filtered payments
     * @param string $filter
     * @param string $ol_pmt_id
     * @return string
     */
    public static function count($filter, $ol_pmt_id)
    {
        global $pdoDb;

        $count = 0;
        try {
            if ($filter == "online_payment_id") {
                $pdoDb->addSimpleWhere("ap.online_payment_id", $ol_pmt_id, "AND");
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $pdoDb->addToFunctions("COUNT(DISTINCT ap.id) AS count");
            $rows = $pdoDb->request("SELECT", "payment", "ap");
            if (!empty($rows)) {
                $count = $rows[0]['count'];
            }
        } catch (PdoDbException $pde) {
            error_log("Payment::count() - Error: " . $pde->getMessage());
        }
        return $count;
    }

    /**
     * Get first payment record for the specified id.
     * @param int $id Unique ID of record to retrieve.
     * @param boolean $is_pymt_id true (default) $id is payment ID, else is invoice ID.
     * @return array Row retrieved. An empty array is returned if no payment found.
     */
    public static function getOne($id, $is_pymt_id = true)
    {
        $rows = self::getPayments($id, $is_pymt_id);
        $count = count($rows);
        if ($count == 0) {
            $payment = array();
        } else {
            $payment = $rows[0];
            $payment['date'] = SiLocal::date($payment['ac_date']);
            $payment['num_payment_recs'] = $count;
        }
        return $payment;
    }

    /**
     * Get a all payment records.
     * @param bool $manageTable true if selection if for the manage table; false (default) if not.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function getAll($manageTable = false)
    {
        $rows = self::getPayments();
        if ($manageTable) {
            return self::manageTableInfo($rows);
        }

        return $rows;
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @param array $rows selected payment information.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo($rows)
    {
        global $LANG;

        $tableRows = array();
        foreach ($rows as $row) {
            $action =
                "<a class=\"index_table\" title=\"{$LANG['view']} {$LANG['payment']}# {$row['id']}\" " .
                   "href=\"index.php?module=payments&amp;view=details&amp;id={$row['id']}&amp;action=view\">" .
                    "<img src=\"images/common/view.png\" height=\"16\" border=\"-5px\" />" .
                "</a>" .
                "&nbsp;&nbsp;" .
                "<a class=\"index_table\" title=\"{$LANG['print_preview_tooltip']} {$LANG['payment']}# {$row['id']}\" " .
                   "href=\"index.php?module=payments&amp;view=print&amp;id={$row['id']}\">" .
                    "<img src=\"images/common/printer.png\" height=\"16\" border=\"-5px\" />" .
                "</a>";

            $invoice_id =
                "<a class=\"index_table\" title=\"{$LANG['invoice']} {$row['iv_index_id']}\" " .
                   "href=\"index.php?module=invoices&amp;view=quick_view&amp;id={$row['ac_inv_id']}\">{$row['iv_index_id']}</a>";

            $tableRows[] = array(
                'action' => $action,
                'payment_id' => $row['id'],
                'invoice_id' => $invoice_id,
                'customer' => $row['cname'],
                'biller' => $row['bname'],
                'amount_fmtd' => SiLocal::currency($row['ac_amount']),
                'type' => $row['type'],
                'date' => $row['date']
            );
        }
        return $tableRows;
    }

    /**
     * @param null $id
     * @param bool $is_pymt_id
     * @return array
     */
    private static function getPayments($id = null, $is_pymt_id = true)
    {
        global $pdoDb;

        $payments = array();
        try {
            $pdoDb->setSelectList(array(
                'ap.*',
                new DbField('iv.index_id', 'iv_index_id'),
                new DbField('c.id', 'customer_id'),
                new DbField('c.name', 'cname'),
                new DbField('b.id', 'biller_id'),
                new DbField('b.name', 'bname'),
                new DbField('pt.pt_description', 'description')
            ));

            $jn = new Join('LEFT', 'invoices', 'iv');
            $jn->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
            $jn->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'customers', 'c');
            $jn->addSimpleItem("iv.customer_id", new DbField("c.id"), "AND");
            $jn->addSimpleItem("iv.domain_id", new DbField("c.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'biller', 'b');
            $jn->addSimpleItem("iv.biller_id", new DbField("b.id"), "AND");
            $jn->addSimpleItem("iv.domain_id", new DbField("b.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join("INNER", "preferences", "pr");
            $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
            $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'payment_types', 'pt');
            $jn->addSimpleItem('pt.pt_id', new DbField('ap.ac_payment_type'), 'AND');
            $jn->addSimpleItem('pt.domain_id', new DbField('ap.domain_id'));
            $pdoDb->addToJoins($jn);

            $fn = new FunctionStmt("DATE_FORMAT", "ac_date,'%Y-%m-%d'");
            $se = new Select($fn, null, null, null, "date");
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt("CONCAT", "pr.pref_inv_wording,' ',iv.index_id");
            $se = new Select($fn, null, null, null, "index_name");
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt("CONCAT", "description,' ',ac_check_number");
            $se = new Select($fn, null, null, null, "type");
            $pdoDb->addToSelectStmts($se);

            if (isset($id)) {
                if ($is_pymt_id) {
                    $pdoDb->addSimpleWhere('ap.id', $id, 'AND');
                    $pdoDb->setOrderBy(array("ap.id", "D"));
                } else {
                    $pdoDb->addSimpleWhere('ap.ac_inv_id', $id, 'AND');
                    $pdoDb->setOrderBy(array("ap.ac_inv_id", "D"));
                }
            } else {
                $pdoDb->setOrderBy(array("ap.id", "D"));
            }
            $pdoDb->addSimpleWhere("ap.domain_id", DomainId::get());

            $rows = $pdoDb->request("SELECT", "payment", "ap");
            foreach ($rows as $row) {
                $row['notes_short'] = SiLocal::truncateStr($row['ac_notes'], '13', '...');
                $row['date'] = SiLocal::date($row['ac_date']);
                $payments[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Payment::getAll() - Error: " . $pde->getMessage());
        }

        return $payments;
    }

    /**
     * @param $start_date
     * @param $end_date
     * @param $filter
     * @param $ol_pmt_id
     * @return array|mixed
     */
    public static function selectByDate($start_date, $end_date, $filter, $ol_pmt_id)
    {
        global $pdoDb;

        $rows = array();
        try {
            if ($filter == "date") {
                $pdoDb->addToFunctions("COUNT(DISTINCT ap.id) AS count");
                $wi = new WhereItem(false, "ap.ac_date", "BETWEEN", array($start_date, $end_date), false, "AND");
                $pdoDb->addToWhere($wi);
            } else if ($filter == "online_payment_id") {
                $pdoDb->addSimpleWhere("ap.online_payment_id", "$ol_pmt_id", "AND");
            }
            $pdoDb->addSimpleWhere("ap.domain_id", DomainId::get());
            // @formatter:off
            $pdoDb->setSelectList( array("ap.*",
                                         "iv.index_id AS index_id",
                                         "iv.id AS invoice_id",
                                         "pref.pref_description AS preference",
                                         "pt.pt_description AS type",
                                         "c.name AS cname",
                                         "b.name AS bname"));
            // @formatter:on

            $oc = new OnClause();
            $oc->addSimpleItem("ap.ac_payment_type", new DbField("pt.pt_id"));
            $pdoDb->addToJoins(array("LEFT", "payment_types", "pt", $oc));

            $oc = new OnClause();
            $oc->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
            $oc->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins(array("LEFT", "invoices", "iv", $oc));

            $oc = new OnClause();
            $oc->addSimpleItem("iv.customer_id", new DbField("c.id"), "AND");
            $oc->addSimpleItem("iv.domain_id", new DbField("c.domain_id"));
            $pdoDb->addToJoins(array("LEFT", "customers", "c", $oc));

            $oc = new OnClause();
            $oc->addSimpleItem("iv.biller_id", new DbField("b.id"), "AND");
            $oc->addSimpleItem("iv.domain_id", new DbField("b.domain_id"));
            $pdoDb->addToJoins(array("LEFT", "biller", "b", $oc));

            $oc = new OnClause();
            $oc->addSimpleItem("iv.preference_id", new DbField("pref.pref_id"), "AND");
            $oc->addSimpleItem("iv.domain_id", new DbField("pref.domain_id"));
            $pdoDb->addToJoins(array("LEFT", "preferences", "pref", $oc));

            $pdoDb->setOrderBy(array("ap.id", "D"));

            $rows = $pdoDb->request("SELECT", "payment", "ap");
        } catch (PdoDbException $pde) {
            error_log("Payment::selectByDate() - Error: " . $pde->getMessage());
        }
        return $rows;
    }

    /**
     * Get a specific payment type record.
     * @param int $id Unique ID of invoice to retrieve payments for.
     * @param bool $manageTable true if selection if for the manage table; false (default) if not.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function getInvoicePayments($id, $manageTable = false)
    {
        global $pdoDb;

        $payments = array();
        try {
            $pdoDb->setSelectList(array(
                "ap.*",
                new DbField('c.name', 'cname'),
                new DbField('b.name', 'bname'),
                new DbField('pt.pt_description', 'description')
            ));

            $jn = new Join('LEFT', 'payment', 'ap');
            $jn->addSimpleItem('ap.ac_inv_id', new DbField('iv.id'), 'AND');
            $jn->addSimpleItem('ap.domain_id', new DbField('iv.domain_id'));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'customers', 'c');
            $jn->addSimpleItem('iv.customer_id', new DbField('c.id'), 'AND');
            $jn->addSimpleItem('iv.domain_id', new DbField('c.domain_id'));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'biller', 'b');
            $jn->addSimpleItem("iv.biller_id", new DbField("b.id"), "AND");
            $jn->addSimpleItem("iv.domain_id", new DbField("b.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'payment_types', 'pt');
            $jn->addSimpleItem('pt.pt_id', new DbField('ap.ac_payment_type'), 'AND');
            $jn->addSimpleItem('pt.domain_id', new DbField('ap.domain_id'));
            $pdoDb->addToJoins($jn);

            $pdoDb->addSimpleWhere("iv.id", $id, "AND");
            $pdoDb->addSimpleWhere("iv.domain_id", DomainId::get());

            $pdoDb->setOrderBy(array("ap.id", "D"));

            $rows = $pdoDb->request("SELECT", "invoices", "iv");
            foreach ($rows as $row) {
                $row['notes_short'] = SiLocal::truncateStr($row['ac_notes'], '13', '...');
                $row['date'] = SiLocal::date($row['ac_date']);
                $payments[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Payment::getInvoicePayments() - id[$id] error: " . $pde->getMessage());
        }

        if ($manageTable) {
            return self::manageTableInfo($payments);
        }

        return $payments;
    }

    /**
     * Get a specific payment type record.
     * @param int $id Unique ID of customer to retrieve payments for.
     * @param bool $manageTable true if selection if for the manage table; false (default) if not.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function getCustomerPayments($id, $manageTable = false)
    {
        global $pdoDb;

        $payments = array();
        try {
            $pdoDb->setSelectList(array(
                'ap.*',
                new DbField('c.name', 'cname'),
                new DbField('b.name', 'bname'),
                new DbField('pt.pt_description', 'description')
            ));

            $jn = new Join('LEFT', 'invoices', 'iv');
            $jn->addSimpleItem("iv.customer_id", new DbField("c.id"), "AND");
            $jn->addSimpleItem("iv.domain_id", new DbField("c.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'payment', 'ap');
            $jn->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
            $jn->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'biller', 'b');
            $jn->addSimpleItem("iv.biller_id", new DbField("b.id"), "AND");
            $jn->addSimpleItem("iv.domain_id", new DbField("b.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'payment_types', 'pt');
            $jn->addSimpleItem('pt.pt_id', new DbField('ap.ac_payment_type'), 'AND');
            $jn->addSimpleItem('pt.domain_id', new DbField('ap.domain_id'));
            $pdoDb->addToJoins($jn);

            $pdoDb->addSimpleWhere("c.id", $id, "AND");
            $pdoDb->addSimpleWhere("ap.domain_id", DomainId::get());

            $pdoDb->setOrderBy(array("ap.id", "D"));

            $rows = $pdoDb->request("SELECT", "customers", "c");
            foreach ($rows as $row) {
                $row['notes_short'] = SiLocal::truncateStr($row['ac_notes'], '13', '...');
                $row['date'] = SiLocal::date($row['ac_date']);
                $payments[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Payment::getCustomerPayments() - id[$id] error: " . $pde->getMessage());
        }

        if ($manageTable) {
            return self::manageTableInfo($payments);
        }

        return $payments;
    }

    /**
     * Add payment type description to retrieved payment records.
     * @param array $payments Array of <i>Payment</i> object to update.
     * @return array <i>Payment</i> records with payment type description added.
     */
    public static function progressPayments($payments)
    {
        global $pdoDb;

        $progressPayments = array();
        try {
            foreach ($payments as $payment) {
                $pdoDb->addSimpleWhere("pt_id", $payment['ac_payment_type'], "AND");
                $pdoDb->addSimpleWhere("domain_id", DomainId::get());
                $pdoDb->setSelectList(array("pt_description", 'description'));
                $result = $pdoDb->request("SELECT", "payment_types");
                if (empty($result)) {
                    $payment['description'] = "";
                } else {
                    $payment['description'] = $result[0]['description'];
                }
                $progressPayments[] = $payment;
            }
        } catch (PdoDbException $pde) {
            error_log("Payment::progressPayments() - Error: " . $pde->getMessage());
        }
        return $progressPayments;
    }

    /**
     * Insert a new payment record
     * @param array $list <i>Faux Post</i> list of record's values.
     * @return integer <b>ID</b> of record inserted. 0 if insert failed.
     */
    public static function insert($list)
    {
        global $pdoDb;

        $result = 0;
        try {
            $pdoDb->setExcludedFields(array("id" => 1));
            $pdoDb->setFauxPost($list);
            $result = $pdoDb->request("INSERT", "payment");
        } catch (PdoDbException $pde) {
            error_log("Payment::insert() - Error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Calculate amount paid on the specified invoice
     * @param integer $ac_inv_id Invoice ID to sum payments for.
     * @return float Total paid on the invoice.
     */
    public static function calcInvoicePaid($ac_inv_id)
    {
        global $pdoDb;

        $amount = 0;
        try {
            $pdoDb->addSimpleWhere("ac_inv_id", $ac_inv_id); // domain_id not needed
            $pdoDb->addToFunctions(new FunctionStmt("COALESCE", "SUM(ac_amount),0", "amount"));
            $rows = $pdoDb->request("SELECT", "payment");
            if (!empty($rows)) {
                $amount = $rows[0]['amount'];
            }
        } catch (PdoDbException $pde) {
            error_log("Payment::calcInvoicePaid() - Error: " . $pde->getMessage());
        }
        return $amount;
    }

    /**
     * @param $customer_id
     * @param bool $isReal
     * @return mixed
     */
    public static function calcCustomerPaid($customer_id, $isReal = false)
    {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->addToFunctions(new FunctionStmt("COALESCE", "SUM(ap.ac_amount),0", "amount"));

            $jn = new Join("INNER", "invoices", "iv");
            $jn->addSimpleItem("iv.id", new DbField("ap.ac_inv_id"), "AND");
            $jn->addSimpleItem("iv.domain_id", new DbField("ap.domain_id"));
            $pdoDb->addToJoins($jn);

            if ($isReal) {
                $jn = new Join("LEFT", "preferences", "pr");
                $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
                $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
                $pdoDb->addToJoins($jn);

                $pdoDb->addSimpleWhere("pr.status", ENABLED, "AND");
            }

            $pdoDb->addSimpleWhere("iv.customer_id", $customer_id, "AND");
            $pdoDb->addSimpleWhere("ap.domain_id", DomainId::get());

            $rows = $pdoDb->request("SELECT", "payment", "ap");
        } catch (PdoDbException $pde) {
            error_log("Payment::calcInvoicePaid() - customer_id[$customer_id] error: " . $pde->getMessage());
        }

        return $rows[0]['amount'];
    }
}
