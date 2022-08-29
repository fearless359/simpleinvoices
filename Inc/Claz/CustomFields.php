<?php

namespace Inc\Claz;

/**
 * Class CustomFields
 * @package Inc\Claz
 */
class CustomFields
{
    /**
     * This function gets all the custom field associated with and id.
     * This function is purposely NOT named, getOne(), as will return
     * an array of rows for the specified ID, rather than a single row.
     *
     * @param int $id ID of the fields to retrieve.
     * @return array Row associated with the $id.
     */
    public static function getOne(int $id): array
    {
        return self::getCustomFields($id);
    }

    public static function getAll(): array
    {
        return self::getCustomFields();
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo(): array
    {
        global $LANG;

        $rows = self::getCustomFields();
        $tableRows = [];
        foreach ($rows as $row) {
            $viewName = $LANG['view'] . ' ' . $LANG['customField'] . ' ' . Util::htmlSafe($row['field_name_nice']);
            $editName = $LANG['edit'] . ' ' . $LANG['customField'] . ' ' . Util::htmlSafe($row['field_name_nice']);

            $action =
                "<a class='index_table' title='$viewName' " .
                   "href='index.php?module=custom_fields&amp;view=view&amp;id={$row['cf_id']}'>" .
                    "<img src='images/view.png' class='action' alt='$viewName'/>" .
                "</a>&nbsp;&nbsp;" .
                "<a class='index_table' title='$editName' " .
                   "href='index.php?module=custom_fields&amp;view=edit&amp;id={$row['cf_id']}'>" .
                    "<img src='images/edit.png' class='action' alt='$editName'/>" .
                "</a>";

            $tableRows[] = [
                'action' => $action,
                'fieldNameNice' => $row['field_name_nice'],
                'cfCustomLabel' => $row['cf_custom_label']
            ];
        }

        return $tableRows;
    }

    /**
     * Return custom fields for a specified ID or all of them if no
     * $id is specified.
     * @param int|null $id If specified, the cf_id of the rows to return.
     * @return array Selected rows.
     */
    private static function getCustomFields(?int $id=null): array
    {
        global $pdoDb;

        $cfs = [];
        try {
            if (isset($id)) {
                $pdoDb->addSimpleWhere('cf_id', $id, 'AND');
            }
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $pdoDb->setOrderBy("cf_id");
            $rows = $pdoDb->request('SELECT', 'custom_fields');
            foreach ($rows as $row) {
                $row['cf_custom_label'] = $row['cf_custom_label'] ?? '';
                $row['field_name_nice'] = self::getCustomFieldName($row['cf_custom_field']);
                $cfs[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("CustomFields::getAll() - Error: " . $pde->getMessage());
        }

        if (empty($cfs)) {
            return [];
        }

        return isset($id) ? $cfs[0] : $cfs;
    }

    /**
     * Used by manage_custom_fields to get the name of the custom field and which section it relates to (ie,
     * biller/product/customer)
     *
     * @param string $field - The custom field in question
     * @return string custom field name or false if undefined entry in $field.
     */
    public static function getCustomFieldName(string $field): string
    {
        global $LANG;

        // grab the first character of the field variable
        $getCfLetter = $field[0];
        // grab the last character of the field variable
        $getCfNumber = $field[strlen($field) - 1];

        // function to return false if invalid custom_field
        switch ($getCfLetter) {
            case "b":
                $customFieldName = $LANG['billerUc'];
                break;
            case "c":
                $customFieldName = $LANG['customerUc'];
                break;
            case "i":
                $customFieldName = $LANG['invoice'];
                break;
            case "p":
                $customFieldName = $LANG['productsUc'];
                break;
            default:
                error_log("CustomField::getCustomFieldName(): field[$field] - Undefined field name");
                $customFieldName = '';
        }

        // Append the rest of the string
        $customFieldName .= " :: " . $LANG["customField"] . " " . $getCfNumber;
        return $customFieldName;
    }

    /**
     * Get custom field labels.
     * @param bool $noUndefinedLabels Defaults to <b>false</b>. When set to
     *        <b>true</b>, custom fields that do not have a label defined will
     *        not be assigned a default label so the undefined custom fields
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

        $cfl = $LANG['customField'] . ' ';
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
        $cfl = $LANG['customField'] . $cfn;
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
     * @return string Display/input string for a custom field. For "read" permission, the field to
     *         display the data. For "write" permission, the formatted label and field.
     * @throws PdoDbException
     */
    public static function showCustomField(string $customField, ?string $customFieldValue, string $permission): string
    {
        global $helpImagePath, $LANG, $pdoDb;

        $writeMode = $permission == 'write'; // if false then in read mode.

        $cssClassHead = "grid__container grid__head-10";
        $cssClassLabel = "cols__1-span-2 align__text-right margin__right-1";
        if (!$writeMode) {
            $cssClassLabel .= " bold";
        }
        $cssClassField = "cols__3-span-8";
        $separator = ":";

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
            $helpCustomFields = Util::htmlSafe($LANG['helpCustomFields']);
            if ($writeMode) {
                $displayBlock =
                    "<div class='$cssClassHead'>\n" .
                    "  <label for='customField$cfn' class='$cssClassLabel'>$customLabelValue$separator\n" .
                    "    <img class='tooltip' title='$helpCustomFields' src='{$helpImagePath}help-small.png' alt='' />\n" .
                    "  </label>\n" .
                    "  <div class='$cssClassField'>\n" .
                    "    <input type='text' name='custom_field$cfn' id='customField$cfn' value='$customFieldValue' size='50'/>\n" .
                    "  </div>\n" .
                    "</div>\n";
            } else {
                $displayBlock =
                    "<div class='$cssClassHead'>\n" .
                    "  <div class='$cssClassLabel'>$customLabelValue$separator</div>\n" .
                    "  <div class='$cssClassField'>$customFieldValue</div>\n" .
                    "</div>\n";
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
