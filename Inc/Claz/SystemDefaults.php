<?php /** @noinspection DuplicatedCode */

namespace Inc\Claz;

/**
 * Class SystemDefaults
 * @package Inc\Claz
 */
class SystemDefaults
{
    protected static array $valuesArrays = [];
    protected static array $values = [];
    protected static bool $initialized = false;

    /**
     * Load content of the system defaults table into this object.
     * @param bool $databaseBuilt true if database has been built, false if not.
     * @param bool $valuesOnly true if associative array of value only return,
     *      false if associative array of arrays containing the values and extension_id
     *      returned.
     * @return array with system_defaults values or values with extension_id per
     *      parameter settings. Can be empty array if database not built.
     */
    public static function loadValues(bool $databaseBuilt = true, bool $valuesOnly = true): array
    {
        global $pdoDbAdmin;

        if (self::$initialized) {
            return $valuesOnly ? self::$values : self::$valuesArrays;
        }

        if (!$databaseBuilt) {
            return [];
        }

        try {
            // Logic for patch count >= 198
            $pdoDbAdmin->setSelectList(['def.name', 'def.value']);

            $jn = new Join("INNER", "extensions", "ext");
            $jn->addSimpleItem("def.domain_id", new DbField("ext.domain_id"));
            $pdoDbAdmin->addToJoins($jn);

            $pdoDbAdmin->addSimpleWhere('enabled', ENABLED, 'AND');
            $pdoDbAdmin->addSimpleWhere('ext.name', 'core', 'AND');
            $pdoDbAdmin->addSimpleWhere('def.domain_id', 0);

            $pdoDbAdmin->setOrderBy("extension_id");

            $rows = $pdoDbAdmin->request('SELECT', 'system_defaults', 'def');

            foreach ($rows as $row) {
                self::$values[$row['name']] = stripslashes($row['value']);
                self::$valuesArrays[$row['name']] = [
                    'value' => stripslashes($row['value']),
                    'extension_id' => $row['extension_id'],
                    'domain_id' => $row['domain_id']
                ];

            }

            // Logic for patch count > 198
            // Why the overlap, I don't know. But items duplicate with ones
            // found previously will be overloaded. (RCR 20181004)
            $pdoDbAdmin->setSelectList([
                'def.name',
                'def.value',
                'def.extension_id',
                'def.domain_id'
            ]);

            $jn = new Join('INNER', 'extensions', 'ext');
            $jn->addSimpleItem('def.extension_id', new DbField('ext.id'));
            $pdoDbAdmin->addToJoins($jn);

            $pdoDbAdmin->addSimpleWhere('enabled', ENABLED, 'AND');
            $pdoDbAdmin->addSimpleWhere('def.domain_id', DomainId::get());

            $pdoDbAdmin->setOrderBy('extension_id');

            $rows = $pdoDbAdmin->request('SELECT', 'system_defaults', 'def');
            foreach ($rows as $row) {
                self::$values[$row['name']] = stripslashes($row['value']);
                self::$valuesArrays[$row['name']] = [
                    'value' => stripslashes($row['value']),
                    'extension_id' => $row['extension_id'],
                    'domain_id' => $row['domain_id']
                ];
            }
        } catch (PdoDbException $pde) {
            error_log("SystemDefaults::loadValues() error thrown: " . $pde->getMessage());
            return [];
        }

        self::$initialized = true;

        return $valuesOnly ? self::$values : self::$valuesArrays;
    }

    /**
     * Update the default value for the specified name.
     * @param string $name of system default row
     * @param string $value of system default row
     * @param string $extensionName Key to extensions row to obtain extension_id for this row
     * @return bool true if processed correctly, false if not.
     */
    public static function updateDefault(string $name, string $value, string $extensionName = "core"): bool
    {
        global $pdoDb;

        $extensionId = Extensions::getExtensionId($extensionName);
        if (!($extensionId >= 0)) {
            error_log("SystemDefaults::updateDefault(): No such extension_name[$extensionName]");
            return false;
        }

        if (!isset(self::$values[$name])) {
            error_log("SystemDefault::updateDefault(): No such default for name[$name]");
            return false;
        }

        try {
            $pdoDb->setFauxPost([
                'value' => addslashes($value),
                'extension_id' => $extensionId
            ]);
            $pdoDb->addSimpleWhere('name', $name, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $pdoDb->request("UPDATE", "system_defaults");
        } catch (PdoDbException $pde) {
            error_log("SystemDefaults::updateDefault(): Unable to add name[$name] value[$value] to database. " . $pde->getMessage());
            return false;
        }
        self::$values[$name] = [$value, $extensionId];
        return true;
    }

    /**
     * @param string $name Name of row to get the value for.
     * @param string $extensionId If specified (not empty), the system default must
     *          contain this extension id.
     * @param bool $ret_string true if failed flag to return as 'DISABLED' string, false returns 0.
     * @return string|int Value of system_defaults row for specified name.
     */
    public static function getValue(string $name, string $extensionId = "", bool $ret_string = true)
    {
        global $LANG;

        // This is needed as getDefaultLanguage is called in the language.php file
        // before the $LANG global has been set up. If $LANG is set up, use it so
        // a local language is reported.
        $disabled = empty($LANG['disabled']) ? "Disabled" : $LANG['disabled'];
        $failed = $ret_string ? $disabled : 0;
        if (empty(self::$values)) {
            return $failed;
        }

        if (!isset(self::$values[$name])) {
            error_log("SystemDefaults::getValue(): Invalid system_defaults name[$name]");
            return $failed;
        }

        $values = self::$valuesArrays[$name];
        if (!empty($extensionId) && $extensionId != $values['extension_id']) {
            return $failed;
        }

        return $values['value'];
    }

    /**
     * Count the system_default records for a specified extension id.
     * @param int $extensionId to count.
     * @return int Count of records for the specified $extensionId
     */
    public static function extensionCount(int $extensionId): int
    {
        $count = 0;
        foreach (self::$valuesArrays as $values) {
            if ($values['extension_id'] == $extensionId &&
                ($values['domain_id'] == 0 || $values['domain_id'] == DomainId::get())) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Delete a specific record from the system_defaults table.
     * @param int $extensionId for record to delete.
     * @return bool true if delete succeeded; otherwise false.
     */
    public static function delete(int $extensionId): bool
    {
        global $pdoDb;

        $result = false;
        try {
            $pdoDb->addSimpleWhere('extension_id', $extensionId, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());

            $result = $pdoDb->request('DELETE', 'system_defaults');
        } catch (PdoDbException $pde) {
            error_log("SystemDefaults::delete() - extension_id[$extensionId] - error: " . $pde->getMessage());
        }

        return $result;
    }

    /**
     * Get "delete" entry from the system_defaults table.
     * @return int 1 if enabled or 0 if not enabled
     */
    public static function getDelete(): int
    {
        return self::getValue('delete');
    }

    /**
     * Get "display_department" entry from the system_defaults table.
     * @return int 1 if enabled or 0 if not enabled
     */
    public static function getDisplayDepartment(): int
    {
        return self::getValue('display_department');
    }

    /**
     * Get "expense" entry from the system_defaults table.
     * @return int 1 if enabled or 0 if not enabled
     */
    public static function getExpense(): int
    {
        return self::getValue('expense');
    }

    /**
     * Get "inventory" entry from the system_defaults table.
     * @return int 1 if enabled or 0 if not enabled
     */
    public static function getInventory(): int
    {
        return self::getValue('inventory');
    }

    /**
     * Get "invoice_description_open" entry from the system_defaults table.
     * @return int 1 if enabled or 0 if not enabled
     */
    public static function getInvoiceDescriptionOpen(): int
    {
        return self::getValue('invoice_description_open');
    }

    /**
     * Get "invoice_display_days" entry from the system_defaults table.
     * @return int Number of days in the past that invoices will be selected
     *      to display on the initial manage screen.
     */
    public static function getInvoiceDisplayDays(): int
    {
        return self::getValue('invoice_display_days');
    }

    /**
     * Get "language" entry from the system_defaults table.
     * @return string Language setting (ex: en_US)
     */
    public static function getLanguage(): string
    {
        return self::getValue('language');
    }

    /**
     * Get "logging" entry from the system_defaults table.
     * @return int 1 if enabled or 0 if not enabled
     */
    public static function getLogging(): int
    {
        return self::getValue('logging');
    }

    /**
     * Get "password_lower" entry from the system_defaults table.
     * @return int Number of lower case letters required in the password.
     */
    public static function getPasswordLower(): int
    {
        return self::getValue('password_lower');
    }

    /**
     * Get "password_min_length" entry from the system_defaults table.
     * @return int Minimum number of characters required in the password.
     */
    public static function getPasswordMinLength(): int
    {
        return self::getValue('password_min_length', "", false);
    }

    /**
     * Get "password_number" entry from the system_defaults table.
     * @return int Number of digits required in the password
     */
    public static function getPasswordNumber(): int
    {
        return self::getValue('password_number');
    }

    /**
     * Get "password_special" entry from the system_defaults table.
     * @return int Number of special characters required in the password.
     */
    public static function getPasswordSpecial(): int
    {
        return self::getValue('password_special');
    }

    /**
     * Get "password_upper" entry from the system_defaults table.
     * @return int Number of upper case letters required in the password.
     */
    public static function getPasswordUpper(): int
    {
        return self::getValue('password_upper');
    }

    /**
     * Get "payment_delete_days" entry from the system_defaults table.
     * @return int Number of days in the past that a payment record can be deleted.
     *      If 0, payments cannot be deleted.
     */
    public static function getPaymentDeleteDays(): int
    {
        return self::getValue('payment_delete_days');
    }


    /**
     * Get "preference" entry from the system_defaults table.
     * @return int 1 if enabled or 0 if not enabled
     * @noinspection PhpUnused
     */
    public static function getPreference(): int
    {
        return self::getValue('preference');
    }

    /**
     * Get "product_attributes" entry from the system_defaults table.
     * @return int 1 if enabled or 0 if not enabled
     */
    public static function getProductAttributes(): int
    {
        return self::getValue('product_attributes');
    }

    /**
     * Get "product_groups" entry from the system_defaults table.
     * @return int 1 if enabled or 0 if not enabled.
     */
    public static function getProductGroups(): int
    {
        return self::getValue('product_groups');
    }

    /**
     * Get "sub_customer" entry from the system_defaults table.
     * @return int 1 if enabled or 0 if not enabled.
     */
    public static function getSubCustomer(): int
    {
        return self::getValue('sub_customer');
    }

    /**
     * Get "session_timeout" entry from the system_defaults table.
     * @return int Session timeout setting in seconds
     */
    public static function getSessionTimeout(): int
    {
        return self::getValue('session_timeout', "", false);
    }

}
