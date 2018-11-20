<?php
namespace Inc\Claz;

/**
 * Class Preferences
 * @package Inc\Claz
 */
class Preferences {

    /**
     * Get a specific <b>si_preferences</b> record.
     * @param string $id Unique ID record to retrieve.
     * @param string $domain_id Domain ID logged into.
     * @return mixed Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function getPreference($id, $domain_id = '') {
        global $LANG, $pdoDb;
        $domain_id = DomainId::get($domain_id);

        $rows = array();
        try {
            $pdoDb->addSimpleWhere("pref_id", $id, "AND");
            $pdoDb->addSimpleWhere("domain_id", $domain_id);

            $ca = new CaseStmt("status", "status_wording");
            $ca->addWhen("=", ENABLED, $LANG['real']);
            $ca->addWhen("!=", ENABLED, $LANG['draft'], true);
            $pdoDb->addToCaseStmts($ca);

            $ca = new CaseStmt("pref_enabled", "enabled");
            $ca->addWhen("=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $pdoDb->setSelectAll(true);

            $rows = $pdoDb->request("SELECT", "preferences");
        } catch (PdoDbException $pde) {
            error_log("Preferences::getPreference() - id[$id] error: " . $pde->getMessage());
        }

        return (empty($rows) ? false : $rows[0]);
    }

    /**
     * Get all preferences records.
     * @param string $domain_id Domain ID logged into.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     *         Note that a field named, "enabled", was added to store the $LANG
     *         enable or disabled word depending on the "pref_enabled" setting
     *         of the record.
     */
    public static function getPreferences($domain_id = '') {
        global $LANG, $pdoDb;
        $domain_id = DomainId::get($domain_id);

        $rows = array();
        try {
            $pdoDb->addSimpleWhere("domain_id", $domain_id);
            $pdoDb->setOrderBy("pref_description");

            $ca = new CaseStmt("pref_enabled", "enabled");
            $ca->addWhen( "=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $pdoDb->setSelectAll(true);

            $rows = $pdoDb->request("SELECT", "preferences");
        } catch (PdoDbException $pde) {
            error_log("Preferences::getPreferences() - Error: " . $pde->getMessage());
        }

        return $rows;
    }

    /**
     * Get active preferences records for the current domain.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function getActivePreferences() {
        global $pdoDb;

        $rows = array();
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
    public static function getDefaultPreference() {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->addSimpleWhere("s.domain_id", DomainId::get());
            $jn = new Join("LEFT", "preferences", "p");
            $jn->addSimpleItem("p.domain_id", new DbField("s.domain_id"), "AND");
            $jn->addSimpleItem("p.pref_id", new DbField("s.value"));
            $pdoDb->addToJoins($jn);

            $rows = $pdoDb->request("SELECT", "system_defaults", "s");
        } catch (PdoDbException $pde) {
            error_log("Preferences::getDefaultPreference() - Error: " . $pde->getMessage());
        }

        return (empty($rows) ? $rows : $rows[0]);
    }

    /**
     * @param string $type If 'count', return the count of records in the table.
     *          Otherwise, select the next page of rows to display.
     * @param string $dir Sort direction 'ASC' or 'DESC'. Defaults to ASC.
     * @param string $sort Field to sort by. Defaults to 'pref_description'. Allowed
     *          values are: 'pref_id', 'pref_description', 'enabled'.
     * @param int $rp Record per page. Defaults to 25.
     * @param int $page Page of rows to display.
     * @return array/int If $type is 'count', the int count value is returned.
     *          Otherwise the selected rows are returned.
     */
    public static function xmlSql($type, $dir, $sort, $rp, $page) {
        global $pdoDb, $LANG;

        if (intval($rp) != $rp) $rp = 25;
        $start = (($page - 1) * $rp);

        $count_type = ($type == 'count');
        if (!$count_type) {
            $pdoDb->setLimit($rp, $start);
        }

        $rows = array();
        try {
            $query = isset($_POST['query']) ? $_POST['query'] : null;
            $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
            if (!empty($qtype) && !empty($query)) {
                if (in_array($qtype, array('pref_id', 'pref_description'))) {
                    $pdoDb->addToWhere(new WhereItem(false, $qtype, 'LIKE', $query, false));
                }
            }
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());

            if (!preg_match('/^(asc|desc)$/iD', $dir)) $dir = 'ASC';
            if (!in_array($sort, array('pref_id', 'pref_description', 'enabled'))) {
                $sort = "pref_description";
            }
            $pdoDb->setOrderBy(array($sort, $dir));

            $pdoDb->setSelectList(array('pref_id', 'pref_description', 'locale', 'language'));

            $ca = new CaseStmt('pref_enabled', 'enabled');
            $ca->addWhen('=', ENABLED, $LANG['enabled']);
            $ca->addWhen('!=', ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $rows = $pdoDb->request('SELECT', 'preferences');
        } catch (PdoDbException $pde) {
            error_log("Preferences::xmlSql() - type[$type] - error: " . $pde->getMessage());
        }

        if ($count_type) {
            $count = count($rows);
            return $count;
        }

        return $rows;
    }

}
