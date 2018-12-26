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
     * Get a customer record.
     * @param string $id Unique ID record to retrieve.
     * @return array Row retrieved. Test for "=== false" to check for failure.
     */
    public static function getOne($id) {
        return self::getCustomers($id);
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
                $customers = $rows;
            } else {
                foreach ($rows as $row) {
                    self::getLastInvoiceIds($row['id'], $last_index_id, $last_id);
                    $row['last_index_id'] = $last_index_id;
                    $row['last_inv_id'] = $last_id;
                    $row['enabled_image'] = ($row['enabled'] == ENABLED ? 'images/common/tick.png' : 'images/common/cross.png');
                    $row['total'] = self::calcCustomerTotal($row['id']);
                    $row['paid'] = Payment::calcCustomerPaid($row['id']);
                    $row['owing'] = $row['total'] - $row['paid'];
                    $customers[] = $row;
                }
            }
        } catch (PdoDbException $pde) {
            error_log("Customer::getAll() - PdoDbException thrown: " . $pde->getMessage());
        }

        if (empty($customers)) {
            return array();
        }

        return (isset($id) ? $customers[0] : $customers);
    }

    /**
     * @param $id
     * @return array
     */
    public static function getCustomerInvoices($id) {
        global $pdoDb;


        $invoices = array();
        try {
            // Using a trick here to add a selection key then call get all with
            // no parameters so the selection key set here is applied in the
            // standard access method.
            $pdoDb->addSimpleWhere('customer_id', $id, 'AND');
            $invoices = Invoice::getAll();
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
     * Get the total owing for a customer.
     * @param int $id for customer to get the total for.
     * @param bool $is_real if true, selects customer only if preferences statis is ENABLED.
     *          If false (default), selection by ID and user's domain.
     * @return float total for customer.
     */
    public static function calcCustomerTotal($id, $is_real = false) {
        global $pdoDb;

        $total = 0;
        try {
            $pdoDb->addToFunctions(new FunctionStmt("COALESCE", "SUM(ii.total),0", "total"));

            $jn = new Join("INNER", "invoices", "iv");
            $jn->addSimpleItem("iv.id", new DbField("ii.invoice_id"), "AND");
            $jn->addSimpleItem("iv.domain_id", new DbField("ii.domain_id"));
            $pdoDb->addToJoins($jn);

            if ($is_real) {
                $jn = new Join("LEFT", "preferences", "pr");
                $jn->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
                $jn->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
                $pdoDb->addToJoins($jn);

                $pdoDb->addSimpleWhere("pr.status", ENABLED, "AND");
            }

            $pdoDb->addSimpleWhere("iv.customer_id", $id, "AND");
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
     * Insert a new customer record.
     * @param bool $excludeCreditCardNumber true if no credit card number to store, false otherwise.
     * @return bool true if insert succeeded, false if failed.
     */
    public static function insertCustomer($excludeCreditCardNumber) {
        global $pdoDb;

        try {
            $pdoDb->addSimpleWhere("name", $_POST['name'], "AND");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $rows = $pdoDb->request("SELECT", "customers");
            if (!empty($rows)) {
                error_log("The specified customer name[{$_POST['name']}) already exists.");
                return false;
            }

            if ($excludeCreditCardNumber) {
                $pdoDb->setExcludedFields('credit_card_number');
            }
            $pdoDb->setExcludedFields('id');
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
     * Mask a string with specified string of characters exposed.
     * @param string $value Value to be masked.
     * @param string $chr Character to replace masked characters.
     * @param int $num_to_show Number of characters to leave exposed.
     * @return string Masked value.
     */
    public static function maskCreditCardNumber($value, $chr='x', $num_to_show=4)
    {
        global $config;

        if (empty($value)) {
            return '';
        }

        // decrypt the value
        $key = $config->encryption->default->key;
        $enc = new Encryption();
        $decrypted_value = $enc->decrypt($key, $value);

        $len = strlen($decrypted_value);
        if ($len <= $num_to_show) {
            return $decrypted_value;
        }

        $mask_len = $len - $num_to_show;
        $masked_value = "";
        for ($i = 0; $i < $mask_len; $i++) {
            $masked_value .= $chr;
        }
        $masked_value .= substr($value, $mask_len);
        return $masked_value;
    }

}
