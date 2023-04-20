<?php

namespace Inc\Claz;

use Encryption;
use Exception;

/**
 * Class Customer
 * @package Inc\Claz
 */
class Customer
{

    /**
     * Calculate count of customer records.
     * @return int Count of customer records in the database. Note that if an exception is thrown,
     *      it will be reported in the error log but a count of 0 will be returned.
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
     * @param array $params Array of options for processing the request. Settings are:<br/>
     *          <i>id</i> - If set, id of customer to retrieve. Defaults to all customers to be considered.<br/>
     *          <i>includeCustId</i> - If set, contains ID of customer that should be included regardless of the
     *                      <b>enabledOnly</b> option setting.<br/>
     *          <i>enabledOnly</i> - Set to <b>true</b> if only Customers that are <i>Enabled</i>
     *                      will be selected. Set to <b>false</b> to select all <b>customers</b> records.<br/>
     *          <i>noTotals</i> - Set to <b>true</b> if only customer record fields to be returned.
     *                      Set to <b>false</b> to add calculated totals field (Default if not specified).<br/>
     *          <i>orderBySet</i> - Set to <b>true</b> if caller set the ORDER BY option. Set to
     *                      <b>false</b> to order by name (default if not specified).<br/>
     *          <i>notInWarehouse</i> - If set to <b>true</b> when only customer records that have no entry in
     *                      the payment_warehouse table should be included. If not set or is set to <b>false</b>
     *                      if payment_warehouse should not be checked.
     * @return array Customers selected.
     * @noinspection PhpDocSignatureInspection
     */
    public static function getAll(array $params = []): array
    {
        return self::getCustomers($params);
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @param bool $defaultDisplayDepartment User option of what to display in the department/phone field.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo(bool $defaultDisplayDepartment): array
    {
        global $config, $LANG, $pdoDb;

        $customerSession = $_SESSION['role_name'] == 'customer';

        $viewCust = $LANG['view'] . " " . $LANG['customerUc'];
        $editCust = $LANG['edit'] . " " . $LANG['customerUc'];
        $defaultInv = $LANG['newUc'] . " " . $LANG['defaultInvoice'];

        try {
            $pdoDb->setOrderBy([['enabled', 'D'], ['name', 'A']]);
        } catch (PdoDbException $pde) {
            error_log("Customer::manageTableInfo(): Unable to set OrderBy - " . $pde->getMessage());
        }

        $rows = self::getCustomers(['orderBySet' => true]);

        $tableRows = [];
        foreach ($rows as $row) {
            $enabled = $row['enabled'] == ENABLED;
            // @formatter:off
            $action = "<a class='index_table' title='$viewCust {$row['name']}' " .
                         "href='index.php?module=customers&amp;view=view&amp;id={$row['id']}'>" .
                          "<img src='images/view.png' class='action' alt='$viewCust' />" .
                      "</a>&nbsp;" .
                      "<a class='index_table' title='$editCust {$row['name']}' " .
                         "href='index.php?module=customers&amp;view=edit&amp;id={$row['id']}'>" .
                          "<img src='images/edit.png' class='action' alt='$editCust' />" .
                      "</a>";
            if ($enabled && !$customerSession) {
                $action .= "&nbsp;<a class='index_table' title='$defaultInv' " .
                              "href='index.php?module=invoices&amp;view=usedefault&amp;customer_id={$row['id']}&amp;action=view'>" .
                               "<img src='images/add.png' class='action' alt='add' />" .
                           "</a>";
            }

            $quickView = "<a class='index_table' title='quick view' " .
                             "href='index.php?module=invoices&amp;view=quickView&amp;id={$row['last_inv_id']}'>" .
                              "{$row['last_index_id']}" .
                         "</a>";

            $image = $enabled ? "images/tick.png" : "images/cross.png";
            $enabledCol = "<span style='display: none'>{$row['enabled_text']}</span>" .
                          "<img src='$image' alt='{$row['enabled_text']}' title='{$row['enabled_text']}' />";
            // @formatter::on

            if ($defaultDisplayDepartment) {
                $deptOrPhoneFieldValue = $row['department'];
            } else {
                $deptOrPhoneFieldValue = empty($row['mobile_phone']) ? $row['phone'] : $row['mobile_phone'];
            }

            $pattern = '/^(.*)_(.*)$/';
            $replPattern = '$1-$2';
            $tableRows[] = [
                'action' => $action,
                'name' => $row['name'],
                'departmentOrPhone' => $deptOrPhoneFieldValue,
                'quickView' => $quickView,
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
     * Private accessor for all <b>customers</b> records per specified option.
     * @param array $params Array of options for processing the request. Settings are:
     *          id - If set, id of customer to retrieve. Defaults to all customers to be considered.
     *          includeCustId - If set, contains ID of customer that should be included regardless of the
     *                      <b>enabledOnly</b> option setting.
     *          enabledOnly - Set to <b>true</b> if only Customers that are <i>Enabled</i>
     *                      will be selected. Set to <b>false</b> to select all <b>customers</b> records.
     *          noTotals - Set to <b>true</b> if only customer record fields to be returned.
     *                      Set to <b>false</b> to add calculated totals field (Default if not specified).
     *          orderBySet - Set to <b>true</b> if caller set the ORDER BY option. Set to
     *                      <b>false</b> to order by name (default if not specified).
     *          notInWarehouse - If set to <b>true</b> when only customer records that have no entry in
     *                      the payment_warehouse table should be included. If not set or is set to <b>false</b>
     *                      if payment_warehouse should not be checked.
     * @return array Customers selected.
     * @noinspection PhpTernaryExpressionCanBeReplacedWithConditionInspection*/
    private static function getCustomers(array $params): array
    {
        global $LANG, $pdoDb;

        // formatter:off
        $id             = $params['id']            ?? null;
        $inclCustId     = $params['includeCustId'] ?? null;
        $enabledOnly    = empty($params['enabledOnly'])    ? false : $params['enabledOnly'];
        $noTotals       = empty($params['noTotals'])       ? false : $params['noTotals'];
        $orderBySet     = empty($params['orderBySet'])     ? false : $params['orderBySet'];
        $notInWarehouse = empty($params['notInWarehouse']) ? false : $params['notInWarehouse'];
        // formatter:on

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
            foreach ($rows as $row) {
                if ($notInWarehouse) {
                    $pw = PaymentWarehouse::getOne($row['id'], 1);
                    if (!empty($pw)) {
                        // Skip customer that is in the payment_warehouse.
                        continue;
                    }
                }

                if (!$noTotals) {
                    self::getLastInvoiceIds($row['id'], $lastIndexId, $lastId);
                    $row['last_index_id'] = $lastIndexId;
                    $row['last_inv_id'] = $lastId;
                    $row['enabled_image'] = $row['enabled'] == ENABLED ? 'images/tick.png' : 'images/cross.png';
                    $row['total'] = self::calcCustomerTotal($row['id']);
                    $row['paid'] = Payment::calcCustomerPaid($row['id']);
                    $row['owing'] = $row['total'] - $row['paid'];
                }
                $customers[] = $row;
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
     * @param bool $isReal if true, selects customer only if preferences status is ENABLED.
     *          If false (default), selection by ID and user's domain.
     * @return float total for customer.
     */
    public static function calcCustomerTotal(int $id, bool $isReal = false): float
    {
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
     * @param int $customerId ID of customer to get info for.
     * @param int|null &$lastIndexId Last index-id value for this customer
     * @param int|null &$lastId Last id value for this customer
     */
    public static function getLastInvoiceIds(int $customerId, ?int &$lastIndexId, ?int &$lastId): void
    {
        global $pdoDb;

        try {
            $lastIndexId = 0;
            $lastId = 0;
            $pdoDb->addSimpleWhere('customer_id', $customerId, 'AND');
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
     * Check to see if this customer is a parent of other customers.
     * @param int $cId of customer to check for having children.
     * @return array Contains the list of sub-customers for the specified $cid.
     */
    public static function getMyChildren(int $cId): array
    {
        global $pdoDb;
        try {
            $pdoDb->addSimpleWhere("parent_customer_id", $cId, "AND");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $pdoDb->setSelectList(['id', 'name']);
            $rows = $pdoDb->request("SELECT", "customers");
        } catch (PdoDbException $pde) {
            error_log("Customer::isParent() - Error: " . $pde->getMessage());
            return [];
        }
        return $rows;
    }

    /**
     * Get the list of customers that can be a parent.
     * @param int|null $parentId
     * @return array
     */
    public static function getMyParent(?int $parentId): array
    {
        global $pdoDb;

        $id = isset($parentId) ?? 0;

        $rows = [];
        try {
            $pdoDb->addSimpleWhere("id", $id, "AND");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $pdoDb->setSelectList(['id', 'name']);
            $rows = $pdoDb->request("SELECT", "customers");
        } catch (PdoDbException $pde) {
            error_log("SubCustomers::getSubCustomers() - Error: " . $pde->getMessage());
        }

        return $rows;
    }

    /**
     * Get a <b>sub-customer</b> records associated with a specific <b>parent_customer_id</b>.
     * @param int $parentId
     * @return array <b>si_customer</b> records retrieved.
     */
    public static function getSubCustomers(int $parentId): array
    {
        global $pdoDb;

        $rows = [];
        try {
            // This is a bit of a trick. We are adding a selection for the parent_customer_id
            // field for all customers that have this parent. Then we call the Customer::getAll()
            // method which will add the final select for the domain_id. So in essence we will
            // use the standard access to perform our selection and return the standard record
            // structure with the parent_customer_id added to the select list.
            $pdoDb->addSimpleWhere("parent_customer_id", $parentId, "AND");
            $rows = Customer::getAll();
        } catch (PdoDbException $pde) {
            error_log("SubCustomers::getSubCustomers() - Error: " . $pde->getMessage());
        }

        return $rows;
    }

    /**
     * JSON encoded echoed output for all customers with this parent ID.
     * @param int $parentId
     * @noinspection PhpUnused
     */
    public static function getSubCustomerAjax(int $parentId): never
    {
        $rows = self::getSubCustomers($parentId);
        $output = "<option value=''></option>";
        foreach ($rows as $row) {
            $output .= "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        echo json_encode($output);
        exit();
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
     * @param string $maskChr Character to replace masked characters.
     * @param int $numToShow Number of characters to leave exposed.
     * @return string Masked value.
     */
    public static function maskCreditCardNumber(?string $value, string $maskChr = 'x', int $numToShow = 4): string
    {
        global $config;

        if (empty($value)) {
            return '';
        }

        try {
            // decrypt the value
            $key = $config['encryptionDefaultKey'];
            $enc = new Encryption();
            $decryptedValue = $enc->decrypt($key, $value);

            $len = strlen($decryptedValue);
            if ($len <= $numToShow) {
                return $decryptedValue;
            }

            $maskLen = $len - $numToShow;
            $maskedValue = str_repeat($maskChr, $maskLen);
            $maskedValue .= substr($decryptedValue, $maskLen);
        } catch (Exception) {
            return $value;
        }
        return $maskedValue;
    }

}
