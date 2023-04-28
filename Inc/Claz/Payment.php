<?php

namespace Inc\Claz;

use DateTime;
use Exception;
use NumberFormatter;

/**
 * Class Payment
 * @package Inc\Claz
 */
class Payment
{
    /**
     * Count of optionally filtered payments
     * @param int|null $onlinePmtId Set to null if all payments to be counted. Otherwise
     *          set to Online Payment ID of the record to access. Online payments
     *          are made through portals such as PayPal, eWAY, etc.
     * @return int count of records retrieved.
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
     * @param bool $isPymtId true (default) if $id is payment ID, false if it is invoice ID.
     * @return array Row retrieved. An empty array is returned if no payment found.
     * @throws PdoDbException
     */
    public static function getOne(int $id, bool $isPymtId = true): array
    {
        $idType = $isPymtId ? 0 : 1;
        $rows = self::getPayments($id, $idType);
        if (empty($rows)) {
            return [];
        }

        // Force return of single row but with count of actual number selected.
        // Multiple rows can only exist if access by invoice id and multiple payments
        // were made for that id.
        $payment = $rows[0];
        $payment['num_payment_recs'] = count($rows);

        return $payment;
    }

    /**
     * Get all payment records.
     * @param bool $manageTable true if selection is for the manage table; false (default) if not.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     * @throws PdoDbException
     */
    public static function getAll(bool $manageTable = false): array
    {
        $rows = self::getPayments(null, 0, $manageTable);
        if (empty($rows)) {
            return [];
        }

        return $rows;
    }

    /**
     * Get a specific payment type record.
     * @param int $id Unique ID of invoice to retrieve payments for.
     * @param bool $manageTable true if selection is for the manage table; false (default) if not.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     * @throws PdoDbException
     */
    public static function getInvoicePayments(int $id, bool $manageTable = false): array
    {
        $rows = self::getPayments($id, 1, $manageTable);
        if (empty($rows)) {
            return [];
        }

        return $rows;
    }

    /**
     * Get a specific payment type record.
     * @param int $customerId
     * @param bool $manageTable true if selection is for the manage table; false (default) if not.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     * @throws PdoDbException
     */
    public static function getCustomerPayments(int $customerId, bool $manageTable = false): array
    {
        $rows = self::getPayments($customerId, 2, $manageTable);
        if (empty($rows)) {
            return [];
        }

        return $rows;
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @param array $rows selected payment information.
     * @return array Data for the manage table rows.
     * @throws Exception
     */
    public static function manageTableInfo(array $rows): array
    {
        global $LANG;

        $paymentDeleteDays = SystemDefaults::getPaymentDeleteDays();
        $now = new DateTime();

        $invoiceIds = [];
        $tableRows = [];
        foreach($rows as $row) {
            $action =
                "<a class='index_table' title='{$LANG['view']} {$LANG['paymentUc']}' " .
                    "href='index.php?module=payments&amp;view=view&amp;ac_inv_id={$row['ac_inv_id']}&amp;id={$row['id']}'>" .
                    "<img src='images/view.png' alt='{$LANG['view']}' height='16' />" .
                "</a>" .
                "&nbsp;" .
                "<a class='index_table' title='{$LANG['printPreviewTooltip']} {$LANG['paymentUc']}' " .
                    "href='index.php?module=payments&amp;view=print&amp;id={$row['id']}' target='_blank'>" .
                    "<img src='images/printer.png' alt='{$LANG['printUc']}' height='16' />" .
                "</a>";

            // See if to add delete option to payment actions. Ensure the delete option has been
            // enabled and that the payment date is within the number of days that deletion is
            // enabled. Additionally verify that a warehouse record has a last payment day that
            // is the same as the payment, or that the payment is the first one for an invoice.
            $pymtDt = new DateTime($row['ac_date']);
            $diff = $now->diff($pymtDt)->format('%a');
            if ($paymentDeleteDays != 0 && $diff <= $paymentDeleteDays &&
                (isset($row['pwLastPaymentId']) && $row['id'] == $row['pwLastPaymentId'] ||
                    !in_array($row['ac_inv_id'], $invoiceIds))) {
                $action .=
                    "&nbsp;" .
                    "<a class='index_table' title='{$LANG['delete']} {$LANG['paymentUc']}' " .
                       "href='index.php?module=payments&amp;view=delete&amp;stage=1&amp;id={$row['id']}' >" .
                        "<img src='images/delete.png' alt='{$LANG['delete']}'/>" .
                    "</a>";
            }

            $invoiceIds[] = $row['ac_inv_id'];

            $invoiceId =
                "<a class='index_table' title='{$LANG['invoice']} {$row['iv_index_id']}' " .
                   "href='index.php?module=invoices&amp;view=quickView&amp;id={$row['ac_inv_id']}'>{$row['iv_index_id']}" .
                "</a>";

            $pattern = '/^(.*)_(.*)$/';
            $tableRows[] = [
                'action' => $action,
                'paymentId' => $row['id'],
                'invoiceId' => $invoiceId,
                'customer' => $row['cname'],
                'biller' => $row['bname'],
                'amount' => $row['ac_amount'],
                'warehouseAmount' => $row['warehouse_amount'],
                'type' => $row['type'],
                'date' => $row['ac_date'],
                'currency_code' => $row['currency_code'],
                'locale' => preg_replace($pattern, '$1-$2', $row['locale'])
            ];
        }

        return $tableRows;
    }

    /**
     * @param int|null $id ID of payment(s) to access. Set to null if all payments are to be accessed.
     * @param int $idType is 0 for id type, 1 ac_inv_id type or 2 for customer_id type.
     * @param bool $manageTable false for return record(s), true for return managerTable format.
     * @return array Row(s) selected from database.
     * @throws PdoDbException
     * @throws Exception
     */
    private static function getPayments(?int $id = null, int $idType = 0, bool $manageTable = false): array
    {
        global $pdoDb;

        if (isset($id)) {
            switch ($idType) {
                case 0:
                    $column = 'ap.id';
                    break;

                case 1:
                    $column = 'ap.ac_inv_id';
                    break;

                case 2:
                    $column = 'customer_id';
                    break;

                default:
                    $str = "Payment::getPayments() - Invalid idType[$idType]";
                    error_log($str);
                    throw new PdoDbException($str);
            }

            $pdoDb->addSimpleWhere($column, $id, 'AND');
        }

        $pdoDb->setOrderBy(['ap.id', 'D']);
        $payments = [];
        try {
            $pdoDb->addSimpleWhere("ap.domain_id", DomainId::get());
            $pdoDb->setSelectList([
                'ap.*',
                "iv.index_id AS iv_index_id",
                "c.id AS customer_id",
                "c.name AS cname",
                "b.id AS biller_id",
                "b.name AS bname",
                "pt.pt_description AS description",
                "pr.locale AS locale",
                "pr.pref_currency_sign AS currency_sign",
                "pr.currency_code AS currency_code",
                "pw.id AS pwId",
                "pw.balance AS pwBalance",
                "pw.check_number AS pwCheckNumber",
                "pw.last_payment_id AS pwLastPaymentId"
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

            $jn = new Join('LEFT', 'payment_warehouse', 'pw');
            $jn->addSimpleItem('pw.customer_id', new DbField('iv.customer_id'));
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

            $rows = $pdoDb->request("SELECT", "payment", "ap");
            foreach ($rows as $row) {
                $row['notes_short'] = Util::TruncateStr($row['ac_notes'], '13', '...');
                $row['date'] = Util::date($row['ac_date']);

                $formatter = new NumberFormatter($row['locale'], NumberFormatter::CURRENCY);
                $precision = $formatter->getAttribute(NumberFormatter::FRACTION_DIGITS);
                $row['precision'] = $precision;

                $payments[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Payment::getPayments() - Error: " . $pde->getMessage());
        }

        if ($manageTable) {
            return self::manageTableInfo($payments);
        }

        return $payments;
    }

    /**
     * @param string $filter Set to "date" if date range to be selected, or "online_payment_id"
     *          to select by the online_payment_id. Online payments are made through portals
     *          such as PayPal, eWAY, etc.
     * @param array|int $value For $filter of date, an array containing the start and end date
     *          range to select by. Otherwise, set to "online_payment_id" and value contains the
     *          online_payment_id value to select payments for.
     * @return array
     */
    public static function selectByValue(string $filter, array|int $value): array
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
            error_log("Payment::selectByValue() - Error: " . $pde->getMessage());
        }
        return $rows;
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
     * @return bool true if record inserted. false if failed.
     */
    public static function insert(array $list, int|string $customerId): bool
    {
        global $pdoDb;

        $result = false;
        try {
            $acAmount = $list['ac_amount'];
            $pwPost = [];
            $pwExclude = [];
            $pwWhere = [];
            $pwRequest = null;
            $paymentWarehouse = PaymentWarehouse::getOne($customerId, 1);
            if (!empty($paymentWarehouse)) {
                $pwId = $paymentWarehouse['id'];
                // Note that if we have a warehouse record then the specified payment
                // amount cannot exceed the warehouse record balance based on amount
                // controls enforced on the payment screen.
                $warehouseBalance = $paymentWarehouse['balance'] - $acAmount;
                $warehouseAmount = -$acAmount;
                if ($warehouseBalance > 0) {
                    $pwPost = [
                        'balance' => $warehouseBalance,
                        'last_payment_id' => null
                    ];
                    $pwExclude = ["id", "customer_id", "payment_type", "check_number"];
                    $pwWhere = ["id" => $pwId];
                    $pwRequest = "UPDATE";
                } else {
                    $pwWhere = ["customer_id" => $customerId];
                    $pwRequest = "DELETE";
                }
            } else {
                // There is no warehouse record. So the payment amount can exceed the amount
                // owing. If it does, we will set the payment to the amount owing and warehouse
                // the excess.
                $inv = Invoice::getOne($list['ac_inv_id']);

                // Calculate balance for new warehouse record. This occurs when the payment amount
                // exceeds the amount owing on the invoice. If not greater than amount owing,
                // there is nothing to warehouse.
                $warehouseBalance = $acAmount - $inv['owing'];
                if ($warehouseBalance > 0) {
                    $warehouseAmount = $warehouseBalance;

                    // Payment set to actual due amount on invoice.
                    $acAmount = $inv['owing'];
                    $list['ac_amount'] = $acAmount;

                    // Add new warehouse record
                    $pwExclude = ['id'];
                    $pwPost = [
                        'customer_id' => $customerId,
                        'balance' => $warehouseBalance,
                        'payment_type' => $list['ac_payment_type'],
                        'check_number' => $list['ac_check_number']
                    ];
                    $pwRequest = "INSERT";
                } else {
                    $warehouseAmount = 0;
                }
            }

            if ($acAmount > 0) {
                $list['warehouse_amount'] = $warehouseAmount;
                if (!isset($list['customer_id'])) {
                    $list['customer_id'] = $customerId;
                }
                $pdoDb->setExcludedFields("id");
                $pdoDb->setFauxPost($list);
                $pmtId = $pdoDb->request("INSERT", "payment");

                if ($pmtId > 0) {
                    $pwPost['last_payment_id'] = $pmtId;
                }
            }

            if (isset($pwRequest)) {
                $pdoDb->setExcludedFields($pwExclude);
                $pdoDb->setFauxPost($pwPost);
                $cnt = count($pwWhere);
                foreach ($pwWhere as $key => $item) {
                    $cnt--;
                    if ($cnt > 0) {
                        $connector = 'AND';
                    } else {
                        $connector = '';
                    }
                    $pdoDb->addSimpleWhere($key, $item, $connector);
                }

                if (!$pdoDb->request($pwRequest, 'payment_warehouse')) {
                    error_log("Payment::insert() - Unable to $pwRequest payment_warehouse record.");
                }
            }
            Invoice::updateAging($list['ac_inv_id']);
            $result = true;
        } catch (PdoDbException $pde) {
            error_log("Payment::insert() - Error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Delete payment after its impact on warehouse information.
     * @param int $id ID of the payment record to delete.
     * @throws PdoDbException
     */
    public static function delete(int $id): bool
    {
        global $pdoDb;

        $payment = self::getOne($id);
        $whAmount = $payment['warehouse_amount'] * -1;

        $prevPaymentId = PaymentWarehouse::retrievePrevPaymentId($payment['ac_inv_id']);

        $pw = PaymentWarehouse::getOne($id, 2);
        if (empty($pw)) {
            if ($whAmount != 0) {
                // Generate new warehouse record if payment deletion makes balance non-zero.
                $result = PaymentWarehouse::insert($payment['customer_id'], $prevPaymentId, $whAmount,
                    $payment['ac_payment_type'], $payment['ac_check_number']);
                if (!$result) {
                    // Insert failed so abort deletion.
                    return false;
                }
            }
        } else {
            $pw['balance'] += $whAmount;
            // Balance can never be negative!
            if ($pw['balance'] > 0) {
                PaymentWarehouse::updateBalance($pw['id'], $pw['balance'], $prevPaymentId);
            } else {
                PaymentWarehouse::delete($pw['id']);
            }
        }

        // Delete payment then get invoice so that owing will be updated.
        $pdoDb->addSimpleWhere('id', $id);
        if ($pdoDb->request('DELETE', 'payment')) {
            Invoice::getOne($payment['ac_inv_id']);
            return true;
        }
        return false;
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
