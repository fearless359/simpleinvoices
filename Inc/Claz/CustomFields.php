<?php
namespace Inc\Claz;

/**
 * Class CustomFields
 * @package Inc\Claz
 */
class CustomFields {

    /**
     * @return array All custom_fields records.
     */
    public static function getAll()
    {
        global $LANG, $pdoDb;

        $cfs = array();
        try {
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $pdoDb->setOrderBy("cf_id");
            $rows = $pdoDb->request('SELECT', 'custom_fields');
            foreach ($rows as $row) {
                $row['field_name_nice'] = self::getCustomFieldName($row['cf_custom_field']);
                $row['vname'] = $LANG['view'] . ' ' . $LANG['custom_field'] . ' ' . Util::htmlsafe($row['field_name_nice']);
                $row['ename'] = $LANG['edit'] . ' ' . $LANG['custom_field'] . ' ' . Util::htmlsafe($row['field_name_nice']);
                $cfs[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("CustomFields::getAll() - Error: " . $pde->getMessage());
        }

        if (empty($cfs)) {
            return array();
        }

        return $cfs;
    }

    /**
     * Used by manage_custom_fields to get the name of the custom field and which section it relates to (ie,
     * biller/product/customer)
     *
     * @param string $field - The custom field in question
     * @return mixed $custom field name or false if undefined entry in $field.
     */
    public static function getCustomFieldName($field) {
        global $LANG;

        // grab the first character of the field variable
        $get_cf_letter = $field[0];
        // grab the last character of the field variable
        $get_cf_number = $field[strlen($field) - 1];

        // function to return false if invalid custom_field
        switch ($get_cf_letter) {
            case "b":
                $custom_field_name = $LANG['biller'];
                break;
            case "c":
                $custom_field_name = $LANG['customer'];
                break;
            case "i":
                $custom_field_name = $LANG['invoice'];
                break;
            case "p":
                $custom_field_name = $LANG['products'];
                break;
            default:
                $custom_field_name = false;
        }

        // Append the rest of the string
        $custom_field_name .= " :: " . $LANG["custom_field"] . " " . $get_cf_number;
        return $custom_field_name;
    }

    /**
     * Get custom field labels.
     * @param boolean $noUndefinedLabels Defaults to <b>false</b>. When set to
     *        <b>true</b> custom fields that do not have a label defined will
     *        not a be assigned a default label so the undefined custom fields
     *        won't be displayed.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function getLabels($noUndefinedLabels = false) {
        global $LANG, $pdoDb_admin;

        $rows = array();
        try {
            $pdoDb_admin->addSimpleWhere("domain_id", DomainId::get());
            $pdoDb_admin->setOrderBy("cf_custom_field");
            $rows = $pdoDb_admin->request("SELECT", "custom_fields");
        } catch (PdoDbException $pde) {
            error_log("CustomFields::::getLabels() - Error: " . $pde->getMessage());
        }

        $cfl = $LANG['custom_field'] . ' ';
        $customFields = array();
        $i = 0;
        foreach($rows as $row) {
            // @formatter:off
            $customFields[$row['cf_custom_field']] =
                (empty($row['cf_custom_label']) ? ($noUndefinedLabels ? "" : $cfl . (($i % 4) + 1)) :
                                                  $row['cf_custom_label']);
            $i++;
            // @formatter:on
        }
        return $customFields;
    }

    /**
     * @param string $field The custom field in question
     * @return string
     */
    public static function getCustomFieldLabel($field) {
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
     * @param string $custom_field Name of the database field.
     * @param string $custom_field_value The value of this field.
     * @param string $permission Maintenance permission (read or write)
     * @param string $css_class_tr CSS class the the table row (tr)
     * @param string $css_class_th CSS class of the table heading (th)
     * @param string $css_class_td CSS class of the table detail (td)
     * @param string $td_col_span COLSPAN value to table detail row.
     * @param string $separator Value to display between two values.
     * @return string Display/input string for a custom field. For "read" permission, the field to
     *         display the data. For "write" permission, the formatted label and field.
     */
    public static function showCustomField($custom_field, $custom_field_value, $permission, $css_class_tr,
                                           $css_class_th, $css_class_td,$td_col_span , $separator) {
        global $help_image_path, $pdoDb;

        $domain_id = DomainId::get();

        $write_mode = ($permission == 'write'); // if false then in read mode.

        // Get the custom field number (last character of the name).
        $cfn = substr($custom_field, -1, 1);
        $cf_label = '';
        try {
            $pdoDb->setSelectList('cf_custom_label');
            $pdoDb->addSimpleWhere('cf_custom_field', $custom_field, 'AND');
            $pdoDb->addSimpleWhere('domain_id', $domain_id);

            $rows = $pdoDb->request('SELECT', 'custom_fields');
            if (!empty($rows)) {
                $cf_label = $rows[0]['cf_custom_label'];
            }
        } catch (PdoDbException $pde) {
            error_log("CustomFields::showCustomField() - custom_field[$custom_field] - error: " . $pde->getMessage());
        }

        $display_block = "";
        if (!empty($custom_field_value) || ($write_mode && !empty($cf_label))) {
            $custom_label_value = Util::htmlsafe(self::getCustomFieldLabel($custom_field));
            if ($write_mode) {
                $display_block =
                    "<tr>\n" .
                    "  <th class='$css_class_th'>$custom_label_value\n" .
                    "    <a class='cluetip' href='#' title='Custom Fields' \n" .
                    "       rel='index.php?module=documentation&amp;view=view&amp;page=help_custom_fields'>\n" .
                    "      <img src='{$help_image_path}help-small.png' alt='' />\n" .
                    "    </a>\n" .
                    "  </th>\n" .
                    "  <td>\n" .
                    "    <input type='text' name='custom_field{$cfn}' value='{$custom_field_value}' size='25' />\n" .
                    "  </td>\n" .
                    "</tr>\n";
            } else {
                $display_block =
                    "<tr class='{$css_class_tr}'>\n" .
                    "  <th class='{$css_class_th}'>{$custom_label_value}{$separator}</th>\n" .
                    "  <td class='{$css_class_td}' colspan='{$td_col_span}'>{$custom_field_value}</td>\n" .
                    "</tr>\n";
            }
        }
        return $display_block;
    }

    /**
     * Update custome field label.
     * @param int $cf_id of custom_fields record to update.
     * @param string $cf_label to set custom field label to in custom_fields table
     * @return bool true if processed without error; otherwise false.
     */
    public static function update($cf_id, $cf_label)
    {
        global $pdoDb;

        $result = false;
        try {
            $pdoDb->addSimpleWhere('cf_id', $cf_id, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());

            $pdoDb->setFauxPost(array("cf_custom_label" => $cf_label));

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
     * @param string $cf_field name of the custom field.
     * @return bool true if processed without error; otherwise false.
     */
    public static function clearFields($cf_field)
    {
        global $pdoDb;

        $result = false;
        try {
            $parts = explode("_", $cf_field);

            if (count($parts) == 2 && preg_match("/cf[1-4]/", $parts[1])) {
                // The table name part of cf_custom_field doesn't contain the needed "s" except for biller.
                $table = $parts[0] . (preg_match("/^(customer|product|invoice)$/", $parts[0]) ? 's' : '');
                $field = "custom_field" . substr($parts[1], 2, 1);

                $pdoDb->addSimpleWhere('domain_id', DomainId::get());
                $pdoDb->setFauxPost(array($field => ''));

                $result = $pdoDb->request('UPDATE', $table);
            }
        } catch (PdoDbException $pde) {
            error_log("CustomFields::clearFields - Error: " . $pde->getMessage());
        }

        return $result;
    }
}