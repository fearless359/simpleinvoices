<?php
namespace Inc\Claz;

/**
 * Class Customer
 * @package Inc\Claz
 */
class Customer {

    /**
     * Calculate count of customer records.
     * @return integer
     */
    public static function count() {
        global $pdoDb;

        try {
            $pdoDb->addToFunctions(new FunctionStmt("COUNT", "id", "count"));
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $rows = $pdoDb->request("SELECT", "customers");
        } catch (PdoDbException $pde) {
            error_log("Customer::count() - Error: " . $pde->getMessage());
            return 0;
        }
        return (empty($rows) ? 0 : $rows[0]['count']);
    }

    /**
     * Insert a new customer record.
     * @param bool $excludeCreditCardNumber true if no credit card number to store, false otherwise.
     * @return bool true if insert succeeded, false if failed.
     */
    public static function insertCustomer($excludeCreditCardNumber) {
        global $pdoDb;

        try {
            if ($excludeCreditCardNumber) {
                $pdoDb->setExcludedFields('credit_card_number');
            }
            $pdoDb->request('INSERT', 'customers');
        } catch (PdoDbException $pde) {
            error_log("Customer::insertCustomer(): Unable to add new customer record. Error: " . $pde->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Update an existing customer record.
     * @param int $id of customer to update.
     * @param bool $excludeCreditCardNumber true if credit card number field should be
     *          excluded, false to include it.
     * @param array $fauxPostList If specified, the associative array of fields to update.
     * @return bool true if update ok, false otherwise.
     */
    public static function updateCustomer($id, $excludeCreditCardNumber, $fauxPostList = null) {
        global $pdoDb;

        try {
            $excludedFields = array('id', 'domain_id');
            if ($excludeCreditCardNumber) $excludedFields[] = 'credit_card_number';
            $pdoDb->setExcludedFields($excludedFields);

            if (!empty($fauxPostList)) {
                $pdoDb->setFauxPost($fauxPostList);
            }
            $pdoDb->addSimpleWhere('id', $id, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $pdoDb->request('UPDATE', 'customers');
        } catch (PdoDbException $pde) {
            error_log("Customer::updateCustomer(): Unable to update the customer record. Error: " . $pde->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Get a customer record.
     * @param string $id Unique ID record to retrieve.
     * @return array Row retrieved. Test for "=== false" to check for failure.
     */
    public static function getOne($id) {
        $rows = self::getCustomers($id);
        return (empty($rows) ? $rows : $rows[0]);
    }

    /**
     * Retrieve all <b>customers</b> records per specified option.
     * @param boolean $enabled_only (Defaults to <b>false</b>) If set to <b>true</b> only Customers 
     *        that are <i>Enabled</i> will be selected. Otherwise all <b>customers</b> records are returned.
     * @param int $incl_cust_id If set, makes sure this customer is included if not enabled regardles
     *        of the $enabled_only option setting.
     * @param boolean $no_totals true if only customer record fields to be returned, false (default) to add
     *        calculated totals field.
     * @return array Customers selected.
     */
    public static function getAll($enabled_only = false, $incl_cust_id = null, $no_totals=false) {
        return self::getCustomers(null, $enabled_only, $incl_cust_id, $no_totals);
    }

    /**
     * Retrieve all <b>customers</b> records per specified option.
     * @param int $id of customer to retrieve, set to null if all customers to be considered.
     * @param boolean $enabled_only (Defaults to <b>false</b>) If set to <b>true</b> only Customers
     *        that are <i>Enabled</i> will be selected. Otherwise all <b>customers</b> records are returned.
     * @param int $incl_cust_id If set, makes sure this customer is included if not enabled regardles
     *        of the $enabled_only option setting.
     * @param boolean $no_totals true if only customer record fields to be returned, false (default) to add
     *        calculated totals field.
     * @return array Customers selected.
     */
    private static function getCustomers($id = null, $enabled_only = false, $incl_cust_id=null, $no_totals=false) {
        global $LANG, $pdoDb;

        $customers = array();
        try {
            if ($enabled_only) {
                if (!empty($incl_cust_id)) {
                    $pdoDb->addToWhere(new WhereItem(true, "id", "=", $incl_cust_id, false, "OR"));
                    $pdoDb->addToWhere(new WhereItem(false, "enabled", "=", ENABLED, true, "AND"));
                } else {
                    $pdoDb->addSimpleWhere("enabled", ENABLED, "AND");
                }
            }
            if (isset($id)) {
                $pdoDb->addSimpleWhere('id', $id, 'AND');
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $case = new CaseStmt("enabled", "enabled_text");
            $case->addWhen("=", ENABLED, $LANG['enabled']);
            $case->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($case);

            $pdoDb->setOrderBy("name");

            $pdoDb->setSelectAll(true);

            $rows = $pdoDb->request("SELECT", "customers");
            if ($no_totals) {
                return $rows;
            }

            foreach ($rows as $customer) {
                self::getLastInvoiceIds($customer['id'], $last_index_id, $last_id);
                $customer['last_index_id'] = $last_index_id;
                $customer['last_inv_id'] = $last_id;
                $customer['enabled_image'] = ($customer['enabled'] == ENABLED ? 'images/common/tick.png' : 'images/common/cross.png');
                $customer['enabled'] = ($customer['enabled'] == ENABLED ? $LANG['enabled'] : $LANG['disabled']);
                $customer['total'] = self::calc_customer_total($customer['id']);
                $customer['paid'] = Payment::calcCustomerPaid($customer['id']);
                $customer['owing'] = $customer['total'] - $customer['paid'];
                $customers[] = $customer;
            }
        } catch (PdoDbException $pde) {
            error_log("Customer::getAll() - PdoDbException thrown: " . $pde->getMessage());
        }
        return $customers;
    }

    /**
     * @param $id
     * @return array
     */
    public static function getCustomerInvoices($id) {
        global $pdoDb;

        $invoices = array();
        try {
            $fn = new FunctionStmt("SUM", "COALESCE(ii.total,0)");
            $fr = new FromStmt("invoice_items", "ii");
            $wh = new WhereClause();
            $wh->addSimpleItem("ii.invoice_id", new DbField("iv.id"), "AND");
            $wh->addSimpleItem("ii.domain_id", new DbField("iv.domain_id"));
            $se = new Select($fn, $fr, $wh, null, "invd");
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt("SUM", "COALESCE(ap.ac_amount, 0)");
            $fr = new FromStmt("payment", "ap");
            $wh = new WhereClause();
            $wh->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
            $wh->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
            $se = new Select($fn, $fr, $wh, null, "pmt");
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt("COALESCE", "invd, 0");
            $se = new Select($fn, null, null, null, "total");
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt("COALESCE", "pmt, 0");
            $se = new Select($fn, null, null, null, "paid");
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt("", "total - paid");
            $se = new Select($fn, null, null, null, "owing");
            $pdoDb->addToSelectStmts($se);

            $pdoDb->setSelectList(array("iv.id", "iv.index_id", "iv.date", "iv.type_id",
                "pr.status", "pr.pref_inv_wording", "b.name"));

            $jn = new Join("LEFT", "preferences", "pr");
            $oc = new OnClause();
            $oc->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
            $oc->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
            $jn->setOnClause($oc);
            $pdoDb->addToJoins($jn);

            $jn = new Join("LEFT", "biller", "b");
            $oc = new OnClause();
            $oc->addSimpleItem('b.id', new DbField('iv.biller_id'), 'AND');
            $oc->addSimpleItem('b.domain_id', new DbField('iv.domain_id'));
            $jn->setOnClause($oc);
            $pdoDb->addToJoins($jn);

            $pdoDb->addSimpleWhere("iv.customer_id", $id, "AND");
            $pdoDb->addSimpleWhere("iv.domain_id", DomainId::get());

            $pdoDb->setOrderBy(array("iv.id", "D"));

            $rows = $pdoDb->request("SELECT", "invoices", "iv");
            foreach ($rows as $row) {
                $row['calc_date'] = date('Y-m-d', strtotime($row['date']));
                $row['date'] = SiLocal::date($row['date']);
                $invoices[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Customer::getCustomerInvoices() - id[$id] error: " . $pde->getMessage());
        }
        return $invoices;
    }

    /**
     * Get a default customer name.
     * @return array Default customer row
     */
    public static function getDefaultCustomer() {
        global $pdoDb;

        try {
            $pdoDb->addSimpleWhere("s.name", "customer", "AND");
            $pdoDb->addSimpleWhere("s.domain_id", DomainId::get());

            $jn = new Join("LEFT", "customers", "c");
            $jn->addSimpleItem("c.id", new DbField("s.value"), "AND");
            $jn->addSimpleItem("c.domain_id", new DbField("s.domain_id"));
            $pdoDb->addToJoins($jn);

            $pdoDb->setSelectList(array("c.name AS name", "s.value AS id"));
            $rows = $pdoDb->request("SELECT", "system_defaults", "s");
        } catch (PdoDbException $pde) {
            error_log("Customer::getDefaultCustomer() - error: " . $pde->getMessage());
        }
        return (empty($rows) ? array() : $rows[0]);
    }

    /**
     * @param $customer_id
     * @param bool $isReal
     * @return float total for customer.
     */
    public static function calc_customer_total($customer_id, $isReal = false) {
        global $pdoDb;

        $total = 0;
        try {
            $pdoDb->addToFunctions(new FunctionStmt("COALESCE", "SUM(ii.total),0", "total"));

            $jn = new Join("INNER", "invoices", "iv");
            $jn->addSimpleItem("iv.id", new DbField("ii.invoice_id"), "AND");
            $jn->addSimpleItem("iv.domain_id", new DbField("ii.domain_id"));
            $pdoDb->addToJoins($jn);

            if ($isReal) {
                $jn = new Join("LEFT", "preferences", "pr");
                $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
                $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
                $pdoDb->addToJoins($jn);

                $pdoDb->addSimpleWhere("pr.status", ENABLED, "AND");
            }

            $pdoDb->addSimpleWhere("iv.customer_id", $customer_id, "AND");
            $pdoDb->addSimpleWhere("ii.domain_id", DomainId::get());

            $rows = $pdoDb->request("SELECT", "invoice_items", "ii");
            if (!empty($rows)) {
                $total = $rows[0]['total'];
            }
        } catch (PdoDbException $pde) {
            error_log("Customer::calc_customer_total() - error: " . $pde->getMessage());
        }

        return $total;
    }

    /**
     * Find the last invoice index_id for a customer.
     * @param int $customer_id
     * @param int $last_index_id
     * @param int $last_id
     */
    public static function getLastInvoiceIds($customer_id, &$last_index_id, &$last_id) {
        global $pdoDb;

        $last_index_id = 0;
        $last_id = 0;
        try {
            $pdoDb->addSimpleWhere('customer_id', $customer_id, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());

            $fn = new FunctionStmt("MAX", new DbField("id"), 'last_id');
            $pdoDb->addToFunctions($fn);

            $fn = new FunctionStmt("MAX", new DbField("index_id"), 'last_index_id');
            $pdoDb->addToFunctions($fn);

            $rows = $pdoDb->request('SELECT', 'invoices');
            if (empty($rows)) {
                return;
            }
        } catch (PdoDbException $pde) {
            error_log("Customer::getLastInvoiceIds() - Error: " . $pde->getMessage());
            return;
        }

        $last_index_id = $rows[0]['last_index_id'];
        $last_id = $rows[0]['last_id'];
    }

    /**
     * Mask a string with specified string of characters exposed.
     * @param string $value Value to be masked.
     * @param string $chr Character to replace masked characters.
     * @param int $num_to_show Number of characters to leave exposed.
     * @return string Masked value.
     */
    public static function maskCreditCardNumber($value, $chr='x', $num_to_show=4) {
        $len = strlen($value);
        if ($len <= $num_to_show) return $value;
        $mask_len = $len - $num_to_show;
        $masked_value = "";
        for ($i=0; $i<$mask_len; $i++) $masked_value .= $chr;
        $masked_value .= substr($value, $mask_len);
        return $masked_value;
    }

}
