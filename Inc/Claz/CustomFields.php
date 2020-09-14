<?php

namespace Inc\Claz;

/**
 * Class CustomFields
 * @package Inc\Claz
 */
class CustomFields
{

    /**
     * @return array All custom_fields records.
     */
    public static function getAll(): array
    {
        global $LANG, $pdoDb;

        $cfs = [];
        try {
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $pdoDb->setOrderBy("cf_id");
            $rows = $pdoDb->request('SELECT', 'custom_fields');
            foreach ($rows as $row) {
                $row['field_name_nice'] = self::getCustomFieldName($row['cf_custom_field']);
                $row['vname'] = $LANG['view'] . ' ' . $LANG['custom_field'] . ' ' . Util::htmlSafe($row['field_name_nice']);
                $row['ename'] = $LANG['edit'] . ' ' . $LANG['custom_field'] . ' ' . Util::htmlSafe($row['field_name_nice']);
                $cfs[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("CustomFields::getAll() - Error: " . $pde->getMessage());
        }

        if (empty($cfs)) {
            return [];
        }

        return $cfs;
    }

    /**
     * Used by manage_custom_fields to get the name of the custom field and which section it relates to (ie,
     * biller/product/customer)
     *
     * @param string $field - The custom field in question
     * @return array|bool custom field name or false if undefined entry in $field.
     */
    public static function getCustomFieldName(string $field)
    {
        global $LANG;

        // grab the first character of the field variable
        $getCfLetter = $field[0];
        // grab the last character of the field variable
        $getCfNumber = $field[strlen($field) - 1];

        // function to return false if invalid custom_field
        switch ($getCfLetter) {
            case "b":
                $customFieldName = $LANG['biller'];
                break;
            case "c":
                $customFieldName = $LANG['customer'];
                break;
            case "i":
                $customFieldName = $LANG['invoice'];
                break;
            case "p":
                $customFieldName = $LANG['products'];
                break;
            default:
                $customFieldName = false;
        }

        // Append the rest of the string
        $customFieldName .= " :: " . $LANG["custom_field"] . " " . $getCfNumber;
        return $customFieldName;
    }

    /**
     * Get custom field labels.
     * @param bool $noUndefinedLabels Defaults to <b>false</b>. When set to
     *        <b>true</b> custom fields that do not have a label defined will
     *        not a be assigned a default label so the undefined custom fields
     *        won't be displayed.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function getLabels(bool $noUndefinedLabels = false): array
    {
        global $LANG, $pdoDbAdmin;

        $rows = [];
        try {
            $pdoDbAdmin->addSimpleWhere("domain_id", DomainId::get());
            $pdoDbAdmin->setOrderBy("cf_custom_field");
            $rows = $pdoDbAdmin->request("SELECT", "custom_fields");
        } catch (PdoDbException $pde) {
            error_log("CustomFields::::getLabels() - Error: " . $pde->getMessage());
        }

        $cfl = $LANG['custom_field'] . ' ';
        $customFields = [];
        $idx = 0;
        foreach ($rows as $row) {
            // @formatter:off
            $customFields[$row['cf_custom_field']] =
                empty($row['cf_custom_label']) ? ($noUndefinedLabels ? "" : $cfl . ($idx % 4 + 1)) :
                                                  $row['cf_custom_label'];
            $idx++;
            // @formatter:on
        }
        return $customFields;
    }

    /**
     * @param string $field The custom field in question
     * @return string
     */
    public static function getCustomFieldLabel(string $field): string
    {
        global $LANG, $pdoDb;

        // grab the last character of the field variable
        $cfn = $field[strlen($field) - 1];
        $cfl = $LANG['custom_field'] . $cfn;
        try {
            $pdoDb->setSelectList('cf_custom_label');
            $pdoDb->addSimpleWhere('cf_custom_field', $field, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());

            $rows = $pdoDb->request('SELECT', 'custom_fields');
            if (!empty($rows)) {
                $cfl = $rows[0]['cf_custom_label'];
            }
        } catch (PdoDbException $pde) {
            error_log("CustomFields::getCustomFieldLabel() - field[$field] - error: " . $pde->getMessage());
        }

        return $cfl;
    }

    /**
     * Build screen values for displaying a custom field.
     * @param string $customField Name of the database field.
     * @param string|null $customFieldValue The value of this field.
     * @param string $permission Maintenance permission (read or write)
     * @param string $cssClassTr CSS class the the table row (tr)
     * @param string $cssClassTh CSS class of the table heading (th)
     * @param string $cssClassTd CSS class of the table detail (td)
     * @param string $tdColSpan COLSPAN value to table detail row.
     * @param string $separator Value to display between two values.
     * @return string Display/input string for a custom field. For "read" permission, the field to
     *         display the data. For "write" permission, the formatted label and field.
     * @throws PdoDbException
     */
    public static function showCustomField(string $customField, ?string $customFieldValue, string $permission, string $cssClassTr,
                                           string $cssClassTh, string $cssClassTd, string $tdColSpan, string $separator): string
    {
        global $helpImagePath, $pdoDb;

        $writeMode = $permission == 'write'; // if false then in read mode.

        // Get the custom field number (last character of the name).
        $cfn = substr($customField, -1, 1);
        $cfLabel = '';
        try {
            $pdoDb->setSelectList('cf_custom_label');
            $pdoDb->addSimpleWhere('cf_custom_field', $customField, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());

            $rows = $pdoDb->request('SELECT', 'custom_fields');
            if (!empty($rows)) {
                $cfLabel = $rows[0]['cf_custom_label'];
            }
        } catch (PdoDbException $pde) {
            error_log("CustomFields::showCustomField() - custom_field[$customField] - error: " . $pde->getMessage());
            throw $pde;
        }

        $displayBlock = "";
        if (!empty($customFieldValue) || $writeMode && !empty($cfLabel)) {
            $customLabelValue = Util::htmlSafe(self::getCustomFieldLabel($customField));
            if ($writeMode) {
                $displayBlock =
                    "<tr>\n" .
                    "  <th class='$cssClassTh'>$customLabelValue\n" .
                    "    <a class='cluetip' href='#' title='Custom Fields' \n" .
                    "       rel='index.php?module=documentation&amp;view=view&amp;page=help_custom_fields'>\n" .
                    "      <img src='{$helpImagePath}help-small.png' alt='' />\n" .
                    "    </a>\n" .
                    "  </th>\n" .
                    "  <td>\n" .
                    "    <input type='text' name='custom_field{$cfn}' value='{$customFieldValue}' size='25' />\n" .
                    "  </td>\n" .
                    "</tr>\n";
            } else {
                $displayBlock =
                    "<tr class='{$cssClassTr}'>\n" .
                    "  <th class='{$cssClassTh}'>{$customLabelValue}{$separator}</th>\n" .
                    "  <td class='{$cssClassTd}' colspan='{$tdColSpan}'>{$customFieldValue}</td>\n" .
                    "</tr>\n";
            }
        }
        return $displayBlock;
    }

    /**
     * Update custom field label.
     * @param int $cfId of custom_fields record to update.
     * @param string $cfLabel to set custom field label to in custom_fields table
     * @return bool true if processed without error; otherwise false.
     */
    public static function update(int $cfId, string $cfLabel): bool
    {
        global $pdoDb;

        $result = false;
        try {
            $pdoDb->addSimpleWhere('cf_id', $cfId, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());

            $pdoDb->setFauxPost(["cf_custom_label" => $cfLabel]);

            $result = $pdoDb->request('UPDATE', 'custom_fields');
        } catch (PdoDbException $pde) {
            error_log("CustomFields::update - Error: " . $pde->getMessage());
        }

        return $result;
    }

    /**
     * Clear uses of custom_field for a specific field.
     * Split the value of the field name into parts and use that data to build
     * the sql statement to clear the field in the associated table.
     *     EX: Field name is: customer_cf2. The split values are "customer" and "cf2".
     *         The test for a missing "s" will cause the table name to be "customers".
     *         The field name will be the constant, "custom_field", with the field number
     *         from the end of "cf2" to be appended resulting in "custom_field2".
     * @param string $cfField name of the custom field.
     * @return bool true if processed without error; otherwise false.
     */
    public static function clearFields(string $cfField): bool
    {
        global $pdoDb;

        $result = false;
        try {
            $parts = explode("_", $cfField);

            if (count($parts) == 2 && preg_match("/cf[1-4]/", $parts[1])) {
                // The table name part of cf_custom_field doesn't contain the needed "s" except for biller.
                $table = $parts[0] . (preg_match("/^(customer|product|invoice)$/", $parts[0]) ? 's' : '');
                $field = "custom_field" . substr($parts[1], 2, 1);

                $pdoDb->addSimpleWhere('domain_id', DomainId::get());
                $pdoDb->setFauxPost([$field => '']);

                $result = $pdoDb->request('UPDATE', $table);
            }
        } catch (PdoDbException $pde) {
            error_log("CustomFields::clearFields - Error: " . $pde->getMessage());
        }

        return $result;
    }
}
