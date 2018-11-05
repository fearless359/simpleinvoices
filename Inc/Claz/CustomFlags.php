<?php
namespace Inc\Claz;

/**
 * @class CustomFlags
 */
class CustomFlags
{
    /**
     * Get the custom flag labels
     * @return array Custom flag labels
     */
    public static function getCustomFlagLabels()
    {
        global $pdoDb;
        $custom_flag_labels = array('', '', '', '', '', '', '', '', '', '');
        try {
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $rows = $pdoDb->request("SELECT", "custom_flags");
            $ndx = 0;
            foreach ($rows as $row) {
                if ($row['enabled'] == ENABLED) $custom_flag_labels[$ndx] = $row['field_label'];
                $ndx++;
            }
        } catch (PdoDbException $pde) {
            error_log('CustomFlags::getCustomFlagLabels() - error: ' . $pde->getMessage());
        }
        return $custom_flag_labels;
    }

    /**
     * Get a specific custom_flags record.
     * @param string $associated_table Database table the flag is for.
     *        Only "products" currently have custom flags.
     * @param integer $flg_id Number of the flag to get the record for.
     * @return array Requested custom flag.
     */
    public static function getCustomFlag($associated_table, $flg_id)
    {
        global $LANG, $pdoDb;

        $rows = array();
        try {
            $pdoDb->addSimpleWhere("domain_id", DomainId::get(), "AND");
            $pdoDb->addSimpleWhere("associated_table", $associated_table, "AND");
            $pdoDb->addSimpleWhere("flg_id", $flg_id);
            $rows = $pdoDb->request("SELECT", "custom_flags");
        } catch (PdoDbException $pde) {
            error_log("CustomFlags::getCustomFlag() - associated_table[$associated_table] flg_id[$flg_id] - error: " . $pde->getMessage());
        }

        $cflg = array();
        if (!empty($rows)) {
            $cflg = $rows[0];
            $cflg['wording_for_enabled'] = ($cflg['enabled'] == ENABLED ? $LANG['enabled'] : $LANG['disabled']);
        }
        return $cflg;
    }

    /**
     * Get all custom_flags records for the user's domain along with the enabled text.
     * @return array Custom_flags rows with an added value, "wording_for_enabled", that contains
     *         "Enabled" or "Disabled" corresponding with the enabled field setting.
     */
    public static function getCustomFlags()
    {
        return self::getCustomFlagsQualified('A');
    }

    /**
     * Get custom_flag record based on the specified qualifier.
     * @param string $qualifier - Qualifies records to return.
     *        Valid options are: (A) - All, (E) - Enabled.
     * @return array
     */
    public static function getCustomFlagsQualified($qualifier)
    {
        global $LANG, $pdoDb;

        $cflgs = array();
        try {
            if ($qualifier == "E") {
                $pdoDb->addSimpleWhere("enabled", ENABLED, "AND");
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $rows = $pdoDb->request("SELECT", "custom_flags");
            foreach ($rows as $cflg) {
                $cflg['wording_for_enabled'] = ($cflg['enabled'] == ENABLED ? $LANG['enabled'] : $LANG['disabled']);
                $cflgs[] = $cflg;
            }
        } catch (PdoDbException $pde) {
            error_log("CustomFlags::getCustomFlagQualified() - qualifier[$qualifier] - error: " . $pde->getMessage());
        }
        return $cflgs;
    }

    /**
     * Update the record with the specified values.
     * @param string $associated_table Associated table of record to update.
     *        Note: Only 'products' table defined at this time.
     * @param integer $flg_id Flag number (1 - 10) of record to update.
     * @param string $field_label The label that will be displayed on the screen where the custom flag is displayed.
     * @param mixed $enabled Can be boolean, string or integer. A value of true, 'Enabled', '1' or 1 is an
     *        enabled setting. Otherwise, it is disabled.
     * @param boolean $clear_flags If enabled the flags will be cleared.
     * @param string $field_help Help data to display for this field.
     * @return boolean <b>true</b> if update processed, <b>false</b> if not.
     */
    public static function updateCustomFlags($associated_table, $flg_id, $field_label, $enabled, $clear_flags, $field_help)
    {
        if (is_bool($enabled)) {
            $enabled = ($enabled ? ENABLED : DISABLED);
        } elseif (is_string($enabled)) {
            $enabled = ($enabled == 'Enabled' ? ENABLED :
                        $enabled == 'Disabled' ? DISABLED : intval($enabled));
        }

        try {
            // If the reset flags option was specified, do so now. Note that this is not considered critical.
            // Therefore failure to update will report in the error log for will not otherwise affect the update.
            $products = Product::select_all();
            $requests = new Requests();
            if ($clear_flags == ENABLED) {
                foreach ($products as $product) {
                    if (substr($product['custom_flags'], $flg_id - 1, 1) == ENABLED) {
                        $custom_flags = substr_replace($product['custom_flags'], DISABLED, $flg_id - 1, 1);

                        $request = new Request("UPDATE", "products");
                        $request->addSimpleWhere("id", $product['id'], "AND");
                        $request->addSimpleWhere("domain_id", $product['domain_id']);
                        $request->setFauxPost(array("custom_flags" => $custom_flags));
                        $requests->add($request);
                    }
                }
            }

            $request = new Request("UPDATE", "custom_flags");
            $request->addSimpleWhere("flg_id", $flg_id, "AND");
            $request->addSimpleWhere("domain_id", DomainId::get(), "AND");
            $request->addSimpleWhere("associated_table", $associated_table);
            $request->addFauxPostList(array("field_label" => $field_label, "enabled" => $enabled, "field_help" => $field_help));
            $requests->add($request);

            $requests->process();
        } catch (PdoDbException $pde) {
            error_log("CustomFlags::updateCustomFlags() - associated_table[$associated_table] flg_id[$flg_id] field_label[$field_label] - error: " . $pde->getMessage());
            return false;
        }

        return true;
    }

}