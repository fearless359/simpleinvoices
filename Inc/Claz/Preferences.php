<?php

namespace Inc\Claz;

/**
 * Class Preferences
 * @package Inc\Claz
 */
class Preferences
{

    /**
     * Get a specific <b>si_preferences</b> record.
     * @param int $id Unique ID record to retrieve.
     * @return array Row(s) retrieved. Empty array returned if nothing found.
     */
    public static function getOne(int $id): array
    {
        return self::getPreferences($id);
    }

    /**
     * Get all preferences records.
     * @return array Rows retrieved. Empty array returned if nothing found.
     *         Note that a field named, "enabled", was added to store the $LANG
     *         enable or disabled word depending on the "pref_enabled" setting
     *         of the record.
     */
    public static function getAll(): array
    {
        return self::getPreferences();
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
            $viewName = $LANG['view'] . " " . $LANG['preference'] . " " . $row['pref_description'];
            $editName = $LANG['edit'] . " " . $LANG['preference'] . " " . $row['pref_description'];

            $action =
                    "<a class='index_table' title='$viewName' " .
                       "href='index.php?module=preferences&amp;view=view&amp;id={$row['pref_id']}' >" .
                        "<img src='images/view.png' class='action' alt='$viewName' />" .
                    "</a>&nbsp;" .
                    "<a class='index_table' title='$editName' " .
                       "href='index.php?module=preferences&amp;view=edit&amp;id={$row['pref_id']}' >" .
                        "<img src='images/edit.png' class='action' alt='$editName' />" .
                    "</a>";

            $enabled = $row['pref_enabled'] == ENABLED;
            $image = $enabled ? "images/tick.png" : "images/cross.png";
            $enabledCol = "<span style='display: none'>{$row['enabled_text']}</span>" .
                "<img src='$image' alt='{$row['enabled_text']}' title='{$row['enabled_text']}' />";

            $enabled = $row['set_aging'] == ENABLED;
            $image = $enabled ? "images/tick.png" : "images/cross.png";
            $setAgingCol = "<span style='display: none'>{$row['set_aging_text']}</span>" .
                "<img src='$image' alt='{$row['set_aging_text']}' title='{$row['set_aging_text']}' />";

            $tableRows[] = [
                'action' => $action,
                'prefId' => $row['pref_id'],
                'prefDescription' => $row['pref_description'],
                'invoiceNumberingGroup' => $row['invoice_numbering_group'],
                'setAgingCol' => $setAgingCol,
                'language' => $row['language'],
                'locale' => $row['locale'],
                'enabled' => $enabledCol
            ];
        }

        return $tableRows;
    }

    /**
     * Get preference records based on specified parameters.
     * @param int|null $id If not null, then id of records to retrieve.
     * @return array Row(s) retrieved. Empty array returned if nothing found.
     */
    private static function getPreferences(?int $id = null): array
    {
        global $LANG, $pdoDb;

        $preferences = [];
        try {
            if (isset($id)) {
                $pdoDb->addSimpleWhere("pref_id", $id, "AND");
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $pdoDb->setOrderBy("pref_description");

            $ca = new CaseStmt("status", "status_wording");
            $ca->addWhen("=", ENABLED, $LANG['real']);
            $ca->addWhen("!=", ENABLED, $LANG['draft'], true);
            $pdoDb->addToCaseStmts($ca);

            $ca = new CaseStmt("pref_enabled", "enabled_text");
            $ca->addWhen("=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $ca = new CaseStmt("set_aging", "set_aging_text");
            $ca->addWhen("=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $pdoDb->setSelectAll(true);

            $rows = $pdoDb->request("SELECT", "preferences");
            foreach ($rows as $row) {
                $row['invoice_numbering_group'] = "";
                foreach ($rows as $r2) {
                    if ($row['index_group'] == $r2['pref_id']) {
                        $row['invoice_numbering_group'] = $r2['pref_description'];
                        break;
                    }
                }
                $preferences[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Preferences::getPreferences() - Error: " . $pde->getMessage());
        }
        if (empty($preferences)) {
            return $preferences;
        }

        return isset($id) ? $preferences[0] : $preferences;
    }

    /**
     * Get active preferences records for the current domain.
     * @return array Rows retrieved.
     */
    public static function getActivePreferences(): array
    {
        global $pdoDb;

        $rows = [];
        try {
            $pdoDb->addSimpleWhere("pref_enabled", ENABLED, "AND");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $pdoDb->setOrderBy("pref_description");
            $rows = $pdoDb->request("SELECT", "preferences");
        } catch (PdoDbException $pde) {
            error_log("Preferences::getActivePreferences() - Error: " . $pde->getMessage());
        }

        return $rows;
    }

    /**
     * Get a default preference information.
     * @return array Preference row and system default setting for it.
     */
    public static function getDefaultPreference(): array
    {
        global $pdoDb;

        $rows = [];
        try {
            $pdoDb->addSimpleWhere('name', 'preference', 'AND');
            $pdoDb->addSimpleWhere("s.domain_id", DomainId::get());
            $jn = new Join("LEFT", "preferences", "p");
            $jn->addSimpleItem("p.domain_id", new DbField("s.domain_id"), "AND");
            $jn->addSimpleItem("p.pref_id", new DbField("s.value"));
            $pdoDb->addToJoins($jn);

            $rows = $pdoDb->request("SELECT", "system_defaults", "s");
        } catch (PdoDbException $pde) {
            error_log("Preferences::getDefaultPreference() - Error: " . $pde->getMessage());
        }

        return empty($rows) ? $rows : $rows[0];
    }

    /**
     * Get preference records that have a numbering sequence defined in the si_index table.
     * @param int|null $indexGroup If specified, limit selection to preferences of this index_group.
     * @return array Rows retrieved.
     */
    public static function getPreferencesWithIndexDefined(?int $indexGroup = null): array
    {
        global $pdoDb;

        $domainId = DomainId::get();

        $rows = [];
        try {
            if (isset($indexGroup)) {
                $pdoDb->addSimpleWhere('index_group', $indexGroup, 'AND');
            }

            $pdoDb->addSimpleWhere('i.node', 'invoice', 'AND');
            $pdoDb->addSimpleWhere("i.domain_id", $domainId);

            $jn = new Join('LEFT', 'preferences', 'p');
            $jn->addSimpleItem('p.pref_id', new DbField('i.sub_node'), 'AND');
            $jn->addSimpleItem('p.domain_id', new DbField('i.domain_id'));
            $pdoDb->addToJoins($jn);

            $pdoDb->setSelectList([new DbField('i.id', 'last_invoice_id'), 'p.pref_id', 'p.pref_description']);
            $pdoDb->setOrderBy("p.pref_description");

            $rows = $pdoDb->request("SELECT", "index", 'i');
        } catch (PdoDbException $pde) {
            error_log("Preferences::getPreferencesWithIndexDefined() - Error: " . $pde->getMessage());
        }

        return $rows;
    }

}
