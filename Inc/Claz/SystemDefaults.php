<?php
namespace Inc\Claz;

/**
 * Class SystemDefaults
 * @package Inc\Claz
 */
class SystemDefaults
{
    protected static $values_arrays = array();
    protected static $values = array();
    protected static $initialized = false;

    /**
     * Load content of the system defaults table into this object.
     * @param bool $databaseBuilt true if database has been built, false if not.
     * @param bool $valuesOnly true if associative array of value only return,
     *      false if associative array of arrays containing the values and extension_id
     *      returned.
     * @return array with system_defaults values or values with extension_id per
     *      parameter settings. Can be empty array if database not built.
     */
    public static function loadValues(bool $databaseBuilt = true, bool $valuesOnly = true)
    {
        global $pdoDb_admin;
        if (self::$initialized) {
            return ($valuesOnly ? self::$values : self::$values_arrays);
        }

        if (!$databaseBuilt) return array();

        try {
            // Logic for patch count >= 198
            $pdoDb_admin->setSelectList(array('def.name', 'def.value'));

            $jn = new Join("INNER", "extensions", "ext");
            $jn->addSimpleItem("def.domain_id", new DbField("ext.domain_id"));
            $pdoDb_admin->addToJoins($jn);

            $pdoDb_admin->addSimpleWhere('enabled', ENABLED, 'AND');
            $pdoDb_admin->addSimpleWhere('ext.name', 'core', 'AND');
            $pdoDb_admin->addSimpleWhere('def.domain_id', 0);

            $pdoDb_admin->setOrderBy("extension_id");

            $rows = $pdoDb_admin->request('SELECT', 'system_defaults', 'def');

            foreach ($rows as $row) {
                self::$values[$row['name']] = stripslashes($row['value']);
                self::$values_arrays[$row['name']] = array(
                    'value' => stripslashes($row['value']),
                    'extension_id' => $row['extension_id'],
                    'domain_id' => $row['domain_id']);

            }

            // Logic for patch count > 198
            // Why the overlap, I don't know. But items duplicate with ones
            // found previously will be overloaded. (RCR 20181004)
            $pdoDb_admin->setSelectList(array(
                'def.name',
                'def.value',
                'def.extension_id',
                'def.domain_id'));

            $jn = new Join('INNER', 'extensions', 'ext');
            $jn->addSimpleItem('def.extension_id', new DbField('ext.id'));
            $pdoDb_admin->addToJoins($jn);

            $pdoDb_admin->addSimpleWhere('enabled', ENABLED, 'AND');
            $pdoDb_admin->addSimpleWhere('def.domain_id', DomainId::get());

            $pdoDb_admin->setOrderBy('extension_id');

            $rows = $pdoDb_admin->request('SELECT', 'system_defaults', 'def');
            foreach ($rows as $row) {
                self::$values[$row['name']] = stripslashes($row['value']);
                self::$values_arrays[$row['name']] = array(
                    'value' => stripslashes($row['value']),
                    'extension_id' => $row['extension_id'],
                    'domain_id' => $row['domain_id']);
            }
        } catch (PdoDbException $pde) {
            error_log("SystemDefaults::loadValues() error thrown: " . $pde->getMessage());
            return array();
        }

        self::$initialized = true;
        return ($valuesOnly ? self::$values : self::$values_arrays);
    }

    /**
     * Update the default value for the specified name.
     * @param string $name of system default row
     * @param string $value of system default row
     * @param string $extension_name Key to extensions row to obtain extension_id for this row
     * @return boolean true if processed correctly, false if not.
     */
    public static function updateDefault($name, $value, $extension_name = "core")
    {
        global $pdoDb;

        $extension_id = Extensions::getExtensionId($extension_name);
        if (!($extension_id >= 0)) {
            error_log("SystemDefaults::updateDefault(): No such extension_name[$extension_name]");
            return false;
        }

        if (!isset(self::$values[$name])) {
            error_log("SystemDefault::updateDefault(): No such default for name[$name]");
            return false;
        }

        try {
            $pdoDb->setFauxPost(array(
                'value' => addslashes($value),
                'extension_id' => $extension_id
            ));
            $pdoDb->addSimpleWhere('name', $name, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $pdoDb->request("UPDATE", "system_defaults");
        } catch (PdoDbException $pde) {
            error_log("SystemDefaults::updateDefault(): Unable to add name[$name] value[$value] to database. " . $pde->getMessage());
            return false;
        }
        self::$values[$name] = array($value, $extension_id);
        return true;
    }

    /**
     * @param string $name Name of row to get the value for.
     * @param int $extension_id If specified (not null), the system default must
     *          contain this extension id.
     * @param bool $ret_string true if failed flag to return as 'DISABLED' string, false returns 0.
     * @return mixed Value of system_defaults row for specified name.
     */
    public static function getValue(string $name, $extension_id = null, $ret_string = true)
    {
        global $LANG;

        // This is needed as getDefaultLangauge is called in the language.php file
        // before the $LANG global has been set up. If $LANG is set up, use it so
        // a local language is reported.
        $disabled = (empty($LANG['disabled']) ? "Disabled" : $LANG['disabled']);
        $failed = ($ret_string ? $disabled : 0);
        if (empty(self::$values)) {
            return $failed;
        }

        if (!isset(self::$values[$name])) {
            error_log("SystemDefaults::getValue(): Invalid system_defaults name[$name]");
            return $failed;
        }

        $values = self::$values_arrays[$name];
        if (isset($extension_id) && $extension_id != $values['extension_id']) {
            return $failed;
        }

        return $values['value'];
    }

    /**
     * Count the system_default records for a specified extension id.
     * @param int $extension_id to count.
     * @return int Count of records for the specified $extension_id
     */
    public static function extensionCount($extension_id)
    {
        $count = 0;
        foreach (self::$values_arrays as $values) {
            if ($values['extension_id'] == $extension_id &&
                ($values['domain_id'] == 0 || $values['domain_id'] == DomainId::get())) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Delete a specific record from the system_defaults table.
     * @param int $extension_id for record to delete.
     * @return bool true if delete succeeded; otherwise false.
     */
    public static function delete($extension_id) {
        global $pdoDb;

        $result = false;
        try {
            $pdoDb->addSimpleWhere('extension_id', $extension_id, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());

            $result = $pdoDb->request('DELETE', 'system_defaults');
        } catch (PdoDbException $pde) {
            error_log("SystemDefaults::delete() - extension_id[$extension_id] - error: " . $pde->getMessage());
        }

        return $result;
    }
    /**
     * Get "delete" entry from the system_defaults table.
     * @return string "Enabled" or "Disabled"
     */
    public static function getDefaultDelete()
    {
        return self::getValue('delete');
    }

    /**
     * Get "expense" entry from the system_defaults table.
     * @return string "Enabled" or "Disabled"
     */
    public static function getDefaultExpense()
    {
        return self::getValue('expense');
    }

    /**
     * Get "inventory" entry from the system_defaults table.
     * @return string "Enabled" or "Disabled"
     */
    public static function getDefaultInventory()
    {
        return self::getValue('inventory');
    }

    /**
     * Get "language" entry from the system_defaults table.
     * @return string Language setting (ex: en_US)
     */
    public static function getDefaultLanguage()
    {
        $result = self::getValue('language');
        return $result;
    }

    /**
     * Get "logging" entry from the system_defaults table.
     * @return string "Enabled" or "Disabled"
     */
    public static function getDefaultLogging()
    {
        return self::getValue('logging');
    }

    /**
     * Get "logging" entry from the system_defaults table.
     * @return boolean <b>true</b> "1" or "0"
     */
    public static function getDefaultLoggingStatus()
    {
        return (self::getValue('logging', null, false) == ENABLED);
    }

    /**
     * Get "password_lower" entry from the system_defaults table.
     * @return string "Enabled" or "Disabled"
     */
    public static function getDefaultPasswordLower()
    {
        return self::getValue('password_lower');
    }

    /**
     * Get "password_min_length" entry from the system_defaults table.
     * @return string number setting.
     */
    public static function getDefaultPasswordMinLength()
    {
        return self::getValue('password_min_length', null, false);
    }

    /**
     * Get "password_number" entry from the system_defaults table.
     * @return string "Enabled" or "Disabled"
     */
    public static function getDefaultPasswordNumber()
    {
        return self::getValue('password_number');
    }

    /**
     * Get "password_special" entry from the system_defaults table.
     * @return string "Enabled" or "Disabled"
     */
    public static function getDefaultPasswordSpecial()
    {
        return self::getValue('password_special');
    }

    /**
     * Get "password_upper" entry from the system_defaults table.
     * @return string "Enabled" or "Disabled"
     */
    public static function getDefaultPasswordUpper()
    {
        return self::getValue('password_upper');
    }


    /**
     * Get "preference" entry from the system_defaults table.
     * @return mixed
     */
    public static function getDefaultPreference()
    {
        return self::getValue('preference');
    }

    /**
     * Get "product_attributes" entry from the system_defaults table.
     * @return string "Enabled" or "Disabled"
     */
    public static function getDefaultProductAttributes()
    {
        return self::getValue('product_attributes');
    }

    /**
     * Get "session_timeout" entry from the system_defaults table.
     * @return int Session timeout setting
     */
    public static function getDefaultSessionTimeout()
    {
        return self::getValue('session_timeout', null, false);
    }

}
