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
     * @param int|null $onlinePmtId Set to null if all payments to be counted. Otherwise
     *          set to Online Payment ID of the record to accessed. Online payments
     *          are made through portals such as PayPal, eWAY, etc.
     * @return int Count of records for specified payment ID if specified. Otherwise
     *          count of all payment records.
     */
    public static function count(?int $onlinePmtId = null): int
    {
        global $pdoDb;

        $count = 0;
        try {
            if (isset($onlinePmtId)) {
                $pdoDb->addSimpleWhere("ap.online_payment_id", $onlinePmtId, "AND");
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
     * @param bool $isPymtId true (default) $id is payment ID, else is invoice ID.
     * @return array Row retrieved. An empty array is returned if no payment found.
     */
    public static function getOne(int $id, bool $isPymtId = true): array
    {
        $rows = self::getPayments($id, $isPymtId);
        $count = count($rows);
        if ($count == 0) {
            $payment = [];
        } else {
            $payment = $rows[0];
            $payment['date'] = Util::date($payment['ac_date']);
            $payment['num_payment_recs'] = $count;
        }

        return $payment;
    }

    /**
     * Get a all payment records.
     * @param bool $manageTable true if selection if for the manage table; false (default) if not.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function getAll(bool $manageTable = false): array
    {
        $rows = self::getPayments();
        if ($manageTable) {
            return self::manageTableInfo($rows);
        }

        return $rows;
    }

    /**
     * Get a specific payment type record.
     * @param int $id Unique ID of invoice to retrieve payments for.
     * @param bool $manageTable true if selection if for the manage table; false (default) if not.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function getInvoicePayments(int $id, bool $manageTable = false): array
    {
        $row = self::getOne($id, false);
        if (!empty($row) && $manageTable) {
            $tableRows = [$row];
            return self::manageTableInfo($tableRows);
        }

        return $row;
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @param array $rows selected payment information.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo(array $rows): array
    {
        global $LANG;
        $tableRows = [];
        foreach ($rows as $row) {
            $action =
                "<a class='index_table' title='{$LANG['view']} {$LANG['paymentUc']}' " .
                    "href='index.php?module=payments&amp;view=view&amp;ac_inv_id={$row['ac_inv_id']}'>" .
                    "<img src='images/view.png' alt='view' height='16' />" .
                "</a>" .
                "&nbsp;&nbsp;" .
                "<a class='index_table' title='{$LANG['printPreviewTooltip']} {$LANG['paymentUc']}# {$row['id']}' " .
                    "href='index.php?module=payments&amp;view=print&amp;id={$row['id']}' target='_blank'>" .
                    "<img src='images/printer.png' alt='print' height='16' />" .
                "</a>";

            $invoiceId =
                "<a class='index_table' title='{$LANG['invoice']} {$row['iv_index_id']}' " .
                "href='index.php?module=invoices&amp;view=quickView&amp;id={$row['ac_inv_id']}'>{$row['iv_index_id']}</a>";

            $pattern = '/^(.*)_(.*)$/';
            $tableRows[] = [
                'action' => $action,
                'paymentId' => $row['id'],
                'invoiceId' => $invoiceId,
                'customer' => $row['cname'],
                'biller' => $row['bname'],
                'amount' => $row['ac_amount'],
                'type' => $row['type'],
                'date' => $row['ac_date'],
                'currency_code' => $row['currency_code'],
                'locale' => preg_replace($pattern, '$1-$2', $row['locale'])
            ];
        }

        return $tableRows;
    }

    /**
     * @param int|null $id ID of payment(s) to access. Set to null if all payments to be accessed.
     * @param bool $isPymtId If true, $id contains the ID of a payment record; false if ID of
     *          payments for an invoice.
     * @return array Rows selected from database.
     */
    private static function getPayments(?int $id = null, bool $isPymtId = true): array
    {
        global $pdoDb;

        $payments = [];
        try {
            $pdoDb->setSelectList([
                'ap.*',
                new DbField('iv.index_id', 'iv_index_id'),
                new DbField('c.id', 'customer_id'),
                new DbField('c.name', 'cname'),
                new DbField('b.id', 'biller_id'),
                new DbField('b.name', 'bname'),
                new DbField('pt.pt_description', 'description'),
                new DbField("pr.locale", "locale"),
                new DbField("pr.pref_currency_sign", "currency_sign"),
                new DbField("pr.currency_code", "currency_code")
            ]);

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
                if ($isPymtId) {
                    $pdoDb->addSimpleWhere('ap.id', $id, 'AND');
                    $pdoDb->setOrderBy(["ap.id", "D"]);
                } else {
                    $pdoDb->addSimpleWhere('ap.ac_inv_id', $id, 'AND');
                    $pdoDb->setOrderBy(["ap.ac_inv_id", "D"]);
                }
            } else {
                $pdoDb->setOrderBy(["ap.id", "D"]);
            }
            $pdoDb->addSimpleWhere("ap.domain_id", DomainId::get());

            $rows = $pdoDb->request("SELECT", "payment", "ap");
            foreach ($rows as $row) {
                $row['notes_short'] = Util::TruncateStr($row['ac_notes'], '13', '...');
                $payments[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Payment::getAll() - Error: " . $pde->getMessage());
        }

        return $payments;
    }

    /**
     * @param string $filter Set to "date" if date range to be selected, or "online_payment_id"
     *          to select by the online_payment_id. Online payments are made through portals
     *          such as PayPal, eWAY, etc.
     * @param array|int $value For $filter of date, an array containing the start and end date
     *          range to select by. Otherwise set to "online_payment_id" and value contains the
     *          online_payment_id value to select payments for.
     * @return array
     */
    public static function selectByValue(string $filter, $value): array
    {
        global $pdoDb;

        $rows = [];
        try {
            if ($filter == "date") {
                $pdoDb->addToFunctions("COUNT(DISTINCT ap.id) AS count");
                $wi = new WhereItem(false, "ap.ac_date", "BETWEEN", $value, false, "AND");
                $pdoDb->addToWhere($wi);
            } elseif ($filter == "online_payment_id") {
                $pdoDb->addSimpleWhere("ap.online_payment_id", $value, "AND");
            } else {
                error_log("Payment::selectByValue() - Invalid filter[$filter] specified.");
            }

            $pdoDb->addSimpleWhere("ap.domain_id", DomainId::get());

            // @formatter:off
            $pdoDb->setSelectList([
                "ap.*",
                "iv.index_id AS index_id",
                "iv.id AS invoice_id",
                "pref.pref_description AS preference",
                "pt.pt_description AS type",
                "c.name AS cname",
                "b.name AS bname"
            ]);
            // @formatter:on

            $oc = new OnClause();
            $oc->addSimpleItem("ap.ac_payment_type", new DbField("pt.pt_id"));
            $pdoDb->addToJoins(["LEFT", "payment_types", "pt", $oc]);

            $oc = new OnClause();
            $oc->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
            $oc->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins(["LEFT", "invoices", "iv", $oc]);

            $oc = new OnClause();
            $oc->addSimpleItem("iv.customer_id", new DbField("c.id"), "AND");
            $oc->addSimpleItem("iv.domain_id", new DbField("c.domain_id"));
            $pdoDb->addToJoins(["LEFT", "customers", "c", $oc]);

            $oc = new OnClause();
            $oc->addSimpleItem("iv.biller_id", new DbField("b.id"), "AND");
            $oc->addSimpleItem("iv.domain_id", new DbField("b.domain_id"));
            $pdoDb->addToJoins(["LEFT", "biller", "b", $oc]);

            $oc = new OnClause();
            $oc->addSimpleItem("iv.preference_id", new DbField("pref.pref_id"), "AND");
            $oc->addSimpleItem("iv.domain_id", new DbField("pref.domain_id"));
            $pdoDb->addToJoins(["LEFT", "preferences", "pref", $oc]);

            $pdoDb->setOrderBy(["ap.id", "D"]);

            $rows = $pdoDb->request("SELECT", "payment", "ap");
        } catch (PdoDbException $pde) {
            error_log("Payment::selectByDate() - Error: " . $pde->getMessage());
        }
        return $rows;
    }

    /**
     * Get a specific payment type record.
     * @param int $id Unique ID of customer to retrieve payments for.
     * @param bool $manageTable true if selection if for the manage table; false (default) if not.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function getCustomerPayments(int $id, bool $manageTable = false): array
    {
        global $pdoDb;

        $payments = [];
        try {
            $pdoDb->setSelectList([
                'ap.*',
                new DbField('c.name', 'cname'),
                new DbField('b.name', 'bname'),
                new DbField('pt.pt_description', 'description'),
                new DbField("pr.locale", "locale"),
                new DbField("pr.pref_currency_sign", "currency_sign"),
                new DbField("pr.currency_code", "currency_code")
            ]);

            $jn = new Join('LEFT', 'invoices', 'iv');
            $jn->addSimpleItem("iv.customer_id", new DbField("c.id"), "AND");
            $jn->addSimpleItem("iv.domain_id", new DbField("c.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join("INNER", "preferences", "pr");
            $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
            $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
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

            $pdoDb->setOrderBy(["ap.id", "D"]);

            $rows = $pdoDb->request("SELECT", "customers", "c");
            foreach ($rows as $row) {
                $row['notes_short'] = Util::TruncateStr($row['ac_notes'], '13', '...');
                $row['date'] = Util::date($row['ac_date']);
                $payments[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Payment::getCustomerPayments() - id[$id] error: " . $pde->getMessage());
        }

        if (empty($payments)) {
            return [];
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
     * @noinspection PhpUnused
     */
    public static function progressPayments(array $payments): array
    {
        global $pdoDb;

        $progressPayments = [];
        try {
            foreach ($payments as $payment) {
                $pdoDb->addSimpleWhere("pt_id", $payment['ac_payment_type'], "AND");
                $pdoDb->addSimpleWhere("domain_id", DomainId::get());
                $pdoDb->setSelectList(["pt_description", 'description']);
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
     * @return int <b>ID</b> of record inserted. 0 if insert failed.
     */
    public static function insert(array $list): int
    {
        global $pdoDb;

        $result = 0;
        try {
            $pdoDb->setExcludedFields(["id" => 1]);
            $pdoDb->setFauxPost($list);
            $result = $pdoDb->request("INSERT", "payment");

            Invoice::updateAging($list['ac_inv_id']);
        } catch (PdoDbException $pde) {
            error_log("Payment::insert() - Error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Calculate amount paid on the specified invoice
     * @param int $acInvId Invoice ID to sum payments for.
     * @return float Total paid on the invoice.
     */
    public static function calcInvoicePaid(int $acInvId): float
    {
        global $pdoDb;

        $amount = 0;
        try {
            $pdoDb->addSimpleWhere("ac_inv_id", $acInvId); // domain_id not needed
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
     * Get the amount paid by the specified customer.
     * @param int $customerId
     * @param bool $isReal
     * @return float
     */
    public static function calcCustomerPaid(int $customerId, bool $isReal = false): float
    {
        global $pdoDb;

        $rows = [];
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

            $pdoDb->addSimpleWhere("iv.customer_id", $customerId, "AND");
            $pdoDb->addSimpleWhere("ap.domain_id", DomainId::get());

            $rows = $pdoDb->request("SELECT", "payment", "ap");
        } catch (PdoDbException $pde) {
            error_log("Payment::calcInvoicePaid() - customer_id[$customerId] error: " . $pde->getMessage());
        }

        return empty($rows) ? 0 : $rows[0]['amount'];
    }
}
