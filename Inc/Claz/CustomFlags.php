<?php

namespace Inc\Claz;

/**
 * Class CustomFlags
 * @package Inc\Claz
 */
class CustomFlags
{
    private const ID_SEPARATOR = ':';

    /**
     * Test for custom flag field.
     * @param string $field
     * @return bool
     */
    public static function isCustomFlagField(string $field): bool
    {
        global $smarty;
        $useIt = false;
        $result = false;
        if (!empty($field)) {
            if (preg_match('/flag:/i', $field) == 1) {
                $useIt = true;
            } else {
                $result = true;
            }
        }
        $smarty->assign('useIt', $useIt);
        return $result;
    }

    /**
     * Get the custom flag labels
     * @return array Custom flag labels
     */
    public static function getCustomFlagLabels(): array
    {
        global $pdoDb;
        $customFlagLabels = ['', '', '', '', '', '', '', '', '', ''];
        try {
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $rows = $pdoDb->request("SELECT", "custom_flags");
            $ndx = 0;
            foreach ($rows as $row) {
                if ($row['enabled'] == ENABLED) {
                    $customFlagLabels[$ndx] = $row['field_label'];
                }
                $ndx++;
            }
        } catch (PdoDbException $pde) {
            error_log('CustomFlags::getCustomFlagLabels() - error: ' . $pde->getMessage());
        }
        return $customFlagLabels;
    }

    /**
     * Get a specific custom_flags record.
     * @param string $associatedTable Database table the flag is for.
     *        Only "products" currently have custom flags.
     * @param int $flgId Number of the flag to get the record for.
     * @return array Requested custom flag.
     */
    public static function getOne(string $associatedTable, int $flgId): array
    {
        return self::getCustomFlags($associatedTable, $flgId);
    }

    /**
     * Get all custom_flags records for the user's domain along with the enabled text.
     * @param string $associatedTable Table flags are associated with. If not specified, all
     *          records for the user's domain are returned.
     * @return array Custom_flags rows with an added value, "enabled_text", that contains
     *         "Enabled" or "Disabled" corresponding with the enabled field setting.
     */
    public static function getAll(string $associatedTable = ''): array
    {
        return self::getCustomFlags($associatedTable);
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo(): array
    {
        global $LANG;

        $rows = self::getAll();
        $tableRows = [];
        foreach ($rows as $row) {
            $viewName = $LANG['view'] . ' ' . $LANG['customFlagsUc'];
            $editName = $LANG['edit'] . ' ' . $LANG['customFlagsUc'];

            $action =
                "<a class='index_table' title='$viewName' " .
                   "href='index.php?module=custom_flags&amp;view=view&amp;id={$row['id']}'>" .
                    "<img src='images/view.png' alt='$viewName'/>" .
                "</a>&nbsp;&nbsp;" .
                "<a class='index_table' title='$editName' " .
                   "href='index.php?module=custom_flags&amp;view=edit&amp;id={$row['id']}'>" .
                    "<img src='images/edit.png' alt='$editName'/>" .
                "</a>";

            $enabled = $row['enabled'] == ENABLED;
            $image = $enabled ? "images/tick.png" : "images/cross.png";
            $enabledCol = "<span style='display: none'>{$row['enabled_text']}</span>" .
                "<img src='$image' alt='{$row['enabled_text']}' title='{$row['enabled_text']}' />";

            $tableRows[] = [
                'action' => $action,
                'associatedTable' => $row['associated_table'],
                'flgId' => $row['flg_id'],
                'fieldLabel' => $row['field_label'],
                'enabled' => $enabledCol,
                'fieldHelp' => $row['field_help']
            ];
        }

        return $tableRows;
    }

    /**
     * Get custom_flag record based on the specified qualifier.
     * @param string $associatedTable If specified, the table for records to retrieve.
     * @param bool $enabledOnly - If true, only enabled records are returned.
     * @return array
     */
    public static function getCustomFlagsQualified(string $associatedTable, bool $enabledOnly): array
    {
        return self::getCustomFlags($associatedTable, null, $enabledOnly);
    }

    /**
     * Class common method to access custom flag records.
     * @param string $associatedTable If specified, specifies table for records to retrieve.
     * @param int|null $flgId If not null, Specified the flag id within the $associatedTable to
     *          retrieve. Note that if specified, the $associated table must also be specified.
     * @param bool $enabledOnly - If true, only enabled records are returned.
     * @return array
     */
    private static function getCustomFlags(string $associatedTable, ?int $flgId = null, bool $enabledOnly = false): array
    {
        global $LANG, $pdoDb;

        $cflgs = [];
        try {
            if (isset($flgId)) {
                $pdoDb->addSimpleWhere('flg_id', $flgId, 'AND');
            }

            if ($enabledOnly) {
                $pdoDb->addSimpleWhere('enabled', ENABLED, 'AND');
            }

            if (!empty($associatedTable)) {
                $pdoDb->addSimpleWhere('associated_table', $associatedTable, 'AND');
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
                $cflgs[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("CustomFlags::getCustomFlags() - Error: " . $pde->getMessage());
        }
        if (empty($cflgs)) {
            return [];
        }
        return isset($flgId) ? $cflgs[0] : $cflgs;
    }

    /**
     * Implode the $associatedTable and $flgId into a single field called $id.
     * @param string $associatedTable Table the flags are associated with
     * @param int $flgId. Flag ID of the row.
     * @return string Imploded result.
     */
    public static function implodeId(string $associatedTable, int $flgId): string
    {
        return implode(CustomFlags::ID_SEPARATOR, [$associatedTable, $flgId]);
    }

    /**
     * Explode the $id field into the $associatedTable and $flgId parts.
     * @param string $id Imploded field to be exploded.
     * @return array Contains parts from exploded $id field. Contains the associated_table and
     *          flag id at indices 0 and 1 respectively.
     * @noinspection PhpUnused
     */
    public static function explodeId(string $id): array
    {
        return explode(CustomFlags::ID_SEPARATOR, $id);
    }

    /**
     * Update the record with the specified values.
     * @param string $associatedTable Associated table of record to update.
     *        Note: Only 'products' table defined at this time.
     * @param int $flgId Flag number (1 - 10) of record to update.
     * @param string $fieldLabel The label that will be displayed on the screen where the custom flag is displayed.
     * @param bool|int|string $enabled Can be boolean, string or integer. A value of true, 'Enabled', '1' or 1 is an
     *        enabled setting. Otherwise, it is disabled.
     * @param bool $clearFlags If enabled the flags will be cleared.
     * @param string $fieldHelp Help data to display for this field.
     * @return bool <b>true</b> if update processed, <b>false</b> if not.
     */
    public static function updateCustomFlags(string $associatedTable, int $flgId, string $fieldLabel, $enabled, bool $clearFlags, string $fieldHelp): bool
    {
        global $config;

        if (is_bool($enabled)) {
            $enabled = $enabled ? ENABLED : DISABLED;
        } elseif (is_string($enabled)) {
            $enabled = $enabled == 'Enabled' ? ENABLED : ($enabled == 'Disabled' ? DISABLED : intval($enabled));
        }

        try {
            // If the reset flags option was specified, do so now. Note that this is not considered critical.
            // Therefore, failure to update will report in the error log for will not otherwise affect the update.
            $products = Product::getAll(true);
            $requests = new Requests($config);
            if ($clearFlags == ENABLED) {
                foreach ($products as $product) {
                    if (substr($product['custom_flags'], $flgId - 1, 1) == ENABLED) {
                        $customFlags = substr_replace($product['custom_flags'], DISABLED, $flgId - 1, 1);

                        $request = new Request("UPDATE", "products");
                        $request->addSimpleWhere("id", $product['id'], "AND");
                        $request->addSimpleWhere("domain_id", $product['domain_id']);
                        $request->setFauxPost(["custom_flags" => $customFlags]);
                        $requests->add($request);
                    }
                }
            }

            $request = new Request("UPDATE", "custom_flags");
            $request->addSimpleWhere("flg_id", $flgId, "AND");
            $request->addSimpleWhere("domain_id", DomainId::get(), "AND");
            $request->addSimpleWhere("associated_table", $associatedTable);
            $request->addFauxPostList(["field_label" => $fieldLabel, "enabled" => $enabled, "field_help" => $fieldHelp]);
            $requests->add($request);

            $requests->process();
        } catch (PdoDbException $pde) {
            error_log("CustomFlags::updateCustomFlags() - associated_table[$associatedTable] flg_id[$flgId] field_label[$fieldLabel] - error: " . $pde->getMessage());
            return false;
        }

        return true;
    }

}
