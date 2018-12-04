<?php
namespace Inc\Claz;

/**
 * Class CustomFields
 * @package Inc\Claz
 */
class CustomFields {

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
    public static function getLabels($noUndefinedLabels = FALSE) {
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
     * @param string $sort
     * @param string $dir
     * @param int $rp
     * @param int $page
     * @return array
     */
    public static function xmlSql($sort, $dir, $rp, $page)
    {
        global $pdoDb;

        //SC: Safety checking values that will be directly subbed in
        if (intval($page) != $page) {
            $page = 0;
        }
        if (intval($rp) != $rp) {
            $rp = 25;
        }
        if (!preg_match('/^(asc|desc)$/iD', $dir)) {
            $dir = 'ASC';
        }

        $where = " WHERE domain_id = :domain_id";

        /*Check that the sort field is OK*/
        if (!in_array($sort, array('cf_id', 'cf_custom_label', 'enabled'))) {
            $sort = "cf_id";
        }

        $rows = array();
        try {
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $pdoDb->setOrderBy(array($sort, $dir));
            $pdoDb->setLimit($rp, $page);
            $rows = $pdoDb->request('SELECT', 'custom_fields');
        } catch (PdoDbException $pde) {
            error_log("modules/custom_fields/xml.php - error: " . $pde->getMessage());
        }

        $cfs = array();
        foreach ($rows as $row) {
            $row['field_name_nice'] = self::getCustomFieldName($row['cf_custom_field']);
            $cfs[] = $row;
        }

        return $cfs;
    }

}