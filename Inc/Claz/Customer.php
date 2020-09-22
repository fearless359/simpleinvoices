<?php

namespace Inc\Claz;

use Encryption;

/**
 * Class Customer
 * @package Inc\Claz
 */
class Customer
{

    /**
     * Calculate count of customer records.
     * @return int
     */
    public static function count(): int
    {
        global $pdoDb;

        try {
            $pdoDb->addToFunctions(new FunctionStmt("COUNT", "id", "count"));
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $rows = $pdoDb->request("SELECT", "customers");
        } catch (PdoDbException $pde) {
            error_log("Customer::count() - Error: " . $pde->getMessage());
            return 0;
        }
        return empty($rows) ? 0 : $rows[0]['count'];
    }

    /**
     * Get a customer record.
     * @param int $id Unique ID record to retrieve.
     * @return array Row retrieved. Empty array returned if no record for $id.
     */
    public static function getOne(int $id): array
    {
        return self::getCustomers(['id' => $id]);
    }

    /**
     * Retrieve all <b>customers</b> records per specified option.
     * @param array $params Array of options for processing the request. Settings are:
     *          enabled_only - Set to <b>true</b> if only Customers that are <i>Enabled</i>
     *              will be selected. Set to <b>false</b> to select all <b>customers</b> records.
     *          incl_cust_id - If set, makes sure this customer is included regardless of the
     *              <b>enabled_only</b> option setting.
     *          no_totals - Set to <b>true</b> if only customer record fields to be returned.
     *              Set to <b>false</b> to add calculated totals field (Default if not specified).
     *          An empty array if none of these parameters needed.
     * @return array Customers selected.
     */
    public static function getAll(array $params = []): array
    {
        return self::getCustomers($params);
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo()
    {
        global $config, $LANG, $pdoDb;

        session_name("SiAuth");
        session_start();

        $customerSession = $_SESSION['role_name'] == 'customer';

        $viewCust = $LANG['view'] . " " . $LANG['customer'];
        $editCust = $LANG['edit'] . " " . $LANG['customer'];
        $defaultInv = $LANG['newUc'] . " " . $LANG['defaultInvoice'];

        try {
            $pdoDb->setOrderBy([['enabled', 'D'], ['name', 'A']]);
        } catch (PdoDbException $pde) {
            error_log("Customer::manageTableInfo(): Unable to set OrderBy - " . $pde->getMessage());
        }

        $rows = self::getCustomers(['order_by_set' => true]);

        $tableRows = [];
        foreach ($rows as $row) {
            $enabled = $row['enabled'] == ENABLED;
            // @formatter:off
            $action = "<a class='index_table' title='{$viewCust} {$row['name']}' " .
                         "href='index.php?module=customers&amp;view=view&amp;id={$row['id']}'>" .
                          "<img src='images/view.png' class='action' alt='{$viewCust}' />" .
                      "</a>" .
                      "<a class='index_table' title='{$editCust} {$row['name']}' " .
                         "href='index.php?module=customers&amp;view=edit&amp;id={$row['id']}'>" .
                          "<img src='images/edit.png' class='action' alt='{$editCust}' />" .
                      "</a>";
            if ($enabled && !$customerSession) {
                $action .= "<a class='index_table' title='{$defaultInv}' " .
                              "href='index.php?module=invoices&amp;view=usedefault&amp;customer_id={$row['id']}&amp;action=view'>" .
                               "<img src='images/add.png' class='action' alt='add' />" .
                           "</a>";
            }

            $quickView = "<a class='index_table' title='quick view' " .
                             "href='index.php?module=invoices&amp;view=quick_view&amp;id={$row['last_inv_id']}'>" .
                              "{$row['last_index_id']}" .
                         "</a>";

            $image = $enabled ? "images/tick.png" : "images/cross.png";
            $enabledCol = "<span style='display: none'>{$row['enabled_text']}</span>" .
                          "<img src='{$image}' alt='{$row['enabled_text']}' title='{$row['enabled_text']}' />";
            // @formatter::on

            $pattern = '/^(.*)_(.*)$/';
            $replPattern = '$1-$2';
            $tableRows[] = [
                'action' => $action,
                'name' => $row['name'],
                'department' => $row['department'],
                'quick_view' => $quickView,
                'total' => $row['total'],
                'paid' => $row['paid'],
                'owing' => $row['owing'],
                'enabled' => $enabledCol,
                'currencyCode' => $config['localCurrencyCode'],
                'locale' => preg_replace($pattern, $replPattern, $config['localLocale'])
            ];
        }

        return $tableRows;
    }

    /**
     * Retrieve all <b>customers</b> records per specified option.
     * @param array $params Array of options for processing the request. Settings are:
     *          id - id of customer to retrieve. Defaults to all customers to be considered.
     *          enabled_only - Set to <b>true</b> if only Customers that are <i>Enabled</i>
     *              will be selected. Set to <b>false</b> to select all <b>customers</b> records.
     *          incl_cust_id - If set, makes sure this customer is included regardless of the
     *              <b>enabled_only</b> option setting.
     *          no_totals - Set to <b>true</b> if only customer record fields to be returned.
     *              Set to <b>false</b> to add calculated totals field (Default if not specified).
     *          order_by_set - Set to <b>true</b> if caller set the ORDER BY option. Set to
     *              <b>false</b> to order by name (default if not specified.
     * @return array Customers selected.
     */
    private static function getCustomers(array $params): array {
        global $LANG, $pdoDb;

        // @formatter::off
        $id          = empty($params['id'])           ? null  : $params['id'];
        $enabledOnly = empty($params['enabled_only']) ? false : $params['enabled_only'];
        $inclCustId  = empty($params['incl_cust_id']) ? null  : $params['incl_cust_id'];
        $noTotals    = empty($params['no_totals'])    ? false : $params['no_totals'];
        $orderBySet  = empty($params['order_by_set']) ? false : $params['order_by_set'];
        // @formatter:on

        $customers = [];
        try {
            if ($enabledOnly) {
                if (!empty($inclCustId)) {
                    $pdoDb->addToWhere(new WhereItem(true, "id", "=", $inclCustId, false, "OR"));
                    $pdoDb->addToWhere(new WhereItem(false, "enabled", "=", ENABLED, true, "AND"));
                } else {
                    $pdoDb->addSimpleWhere("enabled", ENABLED, "AND");
                }
            }

            session_name("SiAuth");
            session_start();

            // If user role is customer or biller, then restrict invoices to those they have access to.
            if ($_SESSION['role_name'] == 'customer') {
                $pdoDb->addSimpleWhere("id", $_SESSION['user_id'], "AND");
            }

            if (!empty($id)) {
                $pdoDb->addSimpleWhere('id', $id, 'AND');
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $case = new CaseStmt("enabled", "enabled_text");
            $case->addWhen("=", ENABLED, $LANG['enabled']);
            $case->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($case);

            if (!$orderBySet) {
                $pdoDb->setOrderBy('name');
            }

            $pdoDb->setSelectAll(true);

            $rows = $pdoDb->request("SELECT", "customers");
            if ($noTotals) {
                $customers = $rows;
            } else {
                foreach ($rows as $row) {
                    self::getLastInvoiceIds($row['id'], $lastIndexId, $lastId);
                    $row['last_index_id'] = $lastIndexId;
                    $row['last_inv_id'] = $lastId;
                    $row['enabled_image'] = $row['enabled'] == ENABLED ? 'images/tick.png' : 'images/cross.png';
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
            return [];
        }

        return !empty($id) ? $customers[0] : $customers;
    }

    /**
     * Get the invoices for a specified customer id.
     * @param int $id of Customer.
     * @return array Invoices retrieved.
     */
    public static function getCustomerInvoices(int $id): array
    {
        global $pdoDb;


        $invoices = [];
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
    public static function getDefaultCustomer(): array
    {
        global $pdoDb;

        try {
            $pdoDb->addSimpleWhere("s.name", "customer", "AND");
            $pdoDb->addSimpleWhere("s.domain_id", DomainId::get());

            $jn = new Join("LEFT", "customers", "c");
            $jn->addSimpleItem("c.id", new DbField("s.value"), "AND");
            $jn->addSimpleItem("c.domain_id", new DbField("s.domain_id"));
            $pdoDb->addToJoins($jn);

            $pdoDb->setSelectList(["c.name AS name", "s.value AS id"]);
            $rows = $pdoDb->request("SELECT", "system_defaults", "s");
        } catch (PdoDbException $pde) {
            error_log("Customer::getDefaultCustomer() - error: " . $pde->getMessage());
        }
        return empty($rows) ? [] : $rows[0];
    }

    /**
     * Get the total owing for a customer.
     * @param int $id for customer to get the total for.
     * @param bool $is_real if true, selects customer only if preferences statis is ENABLED.
     *          If false (default), selection by ID and user's domain.
     * @return float total for customer.
     */
    public static function calcCustomerTotal(int $id, bool $is_real = false): float
    {
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
     * @param int $customer_id ID of customer to get info for.
     * @param int $lastIndexId Last index-id value for this customer
     * @param int $lastId Last id value for this customer
     */
    public static function getLastInvoiceIds(int $customer_id, &$lastIndexId, &$lastId): void
    {
        global $pdoDb;

        $lastIndexId = 0;
        $lastId = 0;
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

        $lastIndexId = $rows[0]['last_index_id'];
        $lastId = $rows[0]['last_id'];
    }

    /**
     * Insert a new customer record.
     * @param bool $excludeCreditCardNumber true if no credit card number to store, false otherwise.
     * @return bool true if insert succeeded, false if failed.
     */
    public static function insertCustomer(bool $excludeCreditCardNumber): bool
    {
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
    public static function updateCustomer(int $id, bool $excludeCreditCardNumber, array $fauxPostList = []): bool
    {
        global $pdoDb;

        try {
            $excludedFields = ['id', 'domain_id'];
            if ($excludeCreditCardNumber) {
                $excludedFields[] = 'credit_card_number';
            }
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
     * @param string|null $value Value to be masked.
     * @param string $chr Character to replace masked characters.
     * @param int $num_to_show Number of characters to leave exposed.
     * @return string Masked value.
     */
    public static function maskCreditCardNumber(?string $value, string $chr = 'x', int $num_to_show = 4): string
    {
        global $config;

        if (empty($value)) {
            return '';
        }

        // decrypt the value
        $key = $config['encryptionDefaultKey'];
        $enc = new Encryption();
        $decryptedValue = $enc->decrypt($key, $value);

        $len = strlen($decryptedValue);
        if ($len <= $num_to_show) {
            return $decryptedValue;
        }

        $maskLen = $len - $num_to_show;
        $maskedValue = "";
        for ($ndx = 0; $ndx < $maskLen; $ndx++) {
            $maskedValue .= $chr;
        }
        $maskedValue .= substr($value, $maskLen);
        return $maskedValue;
    }

}
