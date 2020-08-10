<?php

namespace Inc\Claz;

/**
 * Class CustomFlags
 * @package Inc\Claz
 */
class CustomFlags
{
    /**
     * @var IDSEP Separator for the pseudo 'id' field.
     */
    const IDSEP = ':';

    /**
     * Test for custom flag field.
     * @param $field
     * @return bool
     */
    public static function isCustomFlagField($field)
    {
        global $smarty;
        $useit = false;
        $result = false;
        if (!empty($field)) {
            if (preg_match('/flag:/i', $field) == 1) {
                $useit = true;
            } else {
                $result = true;
            }
        }
        $smarty->assign('useit', $useit);
        return $result;
    }

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
    public static function getOne($associated_table, $flg_id)
    {
        return self::getCustomFlags($associated_table, $flg_id);
    }

    /**
     * Get all custom_flags records for the user's domain along with the enabled text.
     * @param string $associated_table Table flags are associated with. If not specified, all
     *          records for the user's domain are returned.
     * @return array Custom_flags rows with an added value, "enabled_text", that contains
     *         "Enabled" or "Disabled" corresponding with the enabled field setting.
     */
    public static function getAll($associated_table = '')
    {
        return self::getCustomFlags($associated_table);
    }

    /**
     * Get custom_flag record based on the specified qualifier.
     * @param string $associated_table If specified, qualifies records to retireve.
     * @param bool $enabled_only - If true, only enabled records are returned.
     * @return array
     */
    public static function getCustomFlagsQualified($associated_table, $enabled_only)
    {
        return self::getCustomFlags($associated_table, null, $enabled_only);
    }

    /**
     * Class common method to access custom flag records.
     * @param string $associated_table If specified, specifies table for records to retrieve.
     * @param int $flg_id If not null, Specified the flag id within the $associated_table to
     *          retrieve. Note that if specified, the $associated table must also be specified.
     * @param bool $enabled_only - If true, only enabled records are returned.
     * @return array
     */
    private static function getCustomFlags($associated_table, $flg_id = null, $enabled_only = false)
    {
        global $LANG, $pdoDb;

        $cflgs = array();
        try {
            if (isset($flg_id)) {
                $pdoDb->addSimpleWhere('flg_id', $flg_id, 'AND');
            }

            if ($enabled_only) {
                $pdoDb->addSimpleWhere('enabled', ENABLED, 'AND');
            }

            if (!empty($associated_table)) {
                $pdoDb->addSimpleWhere('associated_table', $associated_table, 'AND');
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $ca = new CaseStmt("enabled", "enabled_text");
            $ca->addWhen("=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $pdoDb->setSelectAll(true);

            $rows = $pdoDb->request("SELECT", "custom_flags");
            foreach ($rows as $row) {
                $row['id'] = self::implodeId($row['associated_table'], $row['flg_id']);
                $row['vname'] = $LANG['view'] . ' ' . $LANG['custom_flags_upper'];
                $row['ename'] = $LANG['edit'] . ' ' . $LANG['custom_flags_upper'];
                $row['image'] = ($row['enabled'] == ENABLED ? 'images/tick.png' : 'images/cross.png');
                $cflgs[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("CustomFlags::getCustomFlags() - Error: " . $pde->getMessage());
        }
        if (empty($cflgs)) {
            return array();
        }
        return (isset($flg_id) ? $cflgs[0] : $cflgs);
    }

    /**
     * Implode the $associated_table and $flg_id into a single field called $id.
     * @param string $associated_table Table the flags are associated with
     * @param int $flg_id. Flag ID of the row.
     * @return string Imploded result.
     */
    public static function implodeId($associated_table, $flg_id)
    {
        return implode(CustomFlags::IDSEP, array($associated_table, $flg_id));
    }

    /**
     * Explode the $id field into the $associated_table and $flg_id parts.
     * @param string $id Imploded field to be exploded.
     * @return array Contains parts from exploded $id field. Contains the #associated_table and
     *          $flg_id at indecies 0 and 1 respectively.
     */
    public static function explodeId($id)
    {
        return explode(CustomFlags::IDSEP, $id);
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
        } else if (is_string($enabled)) {
            $enabled = ($enabled == 'Enabled' ? ENABLED :
                $enabled == 'Disabled' ? DISABLED : intval($enabled));
        }

        try {
            // If the reset flags option was specified, do so now. Note that this is not considered critical.
            // Therefore failure to update will report in the error log for will not otherwise affect the update.
            $products = Product::getAll(true);
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