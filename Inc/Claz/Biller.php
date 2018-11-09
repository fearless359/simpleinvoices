<?php
namespace Inc\Claz;

/**
 * Class Biller
 * @package Inc\Claz
 */
class Biller
{
    /**
     * Get all biller records.
     * @param boolean $active_only Set to <b>true</b> to get active billers only.
     *        Set to <b>false</b> or don't specify anything if you want all billers.
     * @return array Biller records retrieved.
     */
    public static function get_all($active_only = false)
    {
        global $LANG, $pdoDb;

        $billers = array();
        try {
            if ($active_only) {
                $pdoDb->addSimpleWhere("enabled", ENABLED, "AND");
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $ca = new CaseStmt("enabled", "wording_for_enabled");
            $ca->addWhen("=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $pdoDb->setOrderBy("name");

            $pdoDb->setSelectAll(true);

            $billers = $pdoDb->request("SELECT", "biller");
        } catch (PdoDbException $pde) {
            error_log("Biller::get_all() error: " . $pde->getMessage());
        }
        return $billers;
    }

    /**
     * Retrieve a specified biller record.
     * @param string $id ID of the biller to retrieve.
     * @return array Associative array for record retrieved.
     */
    public static function select($id)
    {
        global $LANG, $pdoDb;

        try {
            $pdoDb->addSimpleWhere("domain_id", DomainId::get(), "AND");
            $pdoDb->addSimpleWhere("id", $id);

            $ca = new CaseStmt("enabled", "wording_for_enabled");
            $ca->addWhen("=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $pdoDb->setSelectAll(true);

            $rows = $pdoDb->request("SELECT", "biller");
        } catch (PdoDbException $pde) {
            error_log("Biller::select(): id[$id] error: " . $pde->getMessage());
        }
        return (empty($rows) ? array() : $rows[0]);
    }

    /**
     * Get a default biller name.
     * @return array Default biller name
     */
    public static function getDefaultBiller()
    {
        global $pdoDb;

        try {
            $pdoDb->addSimpleWhere("s.name", "biller", "AND");
            $pdoDb->addSimpleWhere("s.domain_id", DomainId::get());

            $jn = new Join('LEFT', 'biller', 'b');
            $jn->addSimpleItem("b.id", new DbField("s.value"), "AND");
            $jn->addSimpleItem("b.domain_id", new DbField("s.domain_id"));
            $pdoDb->addToJoins($jn);

            $rows = $pdoDb->request("SELECT", "system_defaults", "s");
        } catch (PdoDbException $pde) {
            error_log("Biller::getDefaultBiller(): error: " . $pde->getMessage());
        }
        return (empty($rows) ? array("name" => '') : $rows[0]);
    }

    /**
     * Insert a new biller record
     * @return int ID of new record. 0 if failed to insert.
     */
    public static function insertBiller()
    {
        global $pdoDb;
        $_POST['domain_id'] = DomainId::get();
        if (empty($_POST['custom_field1'])) $_POST['custom_field1'] = "";
        if (empty($_POST['custom_field2'])) $_POST['custom_field2'] = "";
        if (empty($_POST['custom_field3'])) $_POST['custom_field3'] = "";
        if (empty($_POST['custom_field4'])) $_POST['custom_field4'] = "";

        $_POST['notes'] = (empty($_POST['note']) ? "" : trim($_POST['note']));

        $id = 0;
        try {
            $pdoDb->setExcludedFields("id");
            $id = $pdoDb->request("INSERT", "biller");
        } catch (PdoDbException $pde) {
            error_log("Biller::insertBiller() - error: " . $pde->getMessage());
        }
        return $id;
    }

    /**
     * Update <b>biller</b> table record.
     * @return boolean <b>true</b> if update successful
     */
    public static function updateBiller()
    {
        global $pdoDb;

        $result = false;
        try {
            // The fields to be update must be in the $_POST array indexed by their
            // actual field name.
            $pdoDb->setExcludedFields(array("id", "domain_id"));

            $pdoDb->addSimpleWhere("id", $_GET['id'], 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());

            $result = $pdoDb->request("UPDATE", "biller");
        } catch (PdoDbException $pde) {
            error_log("Biller::updateBiller() - error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Calculate the number of invoices in the database
     * @return integer Count of invoices in the database
     */
    public static function count()
    {
        global $pdoDb;

        $count = 0;
        try {
            $pdoDb->addToFunctions(new FunctionStmt("COUNT", "id", "count"));
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $rows = $pdoDb->request("SELECT", "biller");
            $count = $rows[0]['count'];
        } catch(PdoDbException $pde) {
            error_log("Biller::count() - error: " . $pde->getMessage());
        }
        return $count;
    }

    /**
     * Selection of record for the xml list screen
     * @param string $type - 'count' if only count of records desired, otherwise selection of records to display.
     * @param string $dir - Sort order (ASC or DESC)
     * @param string $sort - Field to sort on
     * @param string $rp - Number of records to select for this page
     * @param string $page - Pages processed.
     * @return mixed - Count if 'count' requested, Rows selected from biller table.
     */
    function xmlSql($type, $dir, $sort, $rp, $page)
    {
        global $LANG, $pdoDb;

        try {
            $count_type = ($type == "count");

            // If caller pass a null value, that mean there is no limit.
            if (isset($rp) && !$count_type) {
                if (empty($rp)) $rp = "25";
                if (empty($page)) $page = "1";
                $start = (($page - 1) * $rp);
                $pdoDb->setLimit($rp, $start);
            }

            if (!(empty($_POST['query']) || empty($_POST['qtype']))) {
                $query = $_POST['query'];
                $qtype = $_POST['qtype'];
                if (in_array($qtype, array("id", "name", "email"))) {
                    $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
                }
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            if ($type == "count") {
                $pdoDb->addToFunctions("COUNT(*) AS count");
                $rows = $pdoDb->request("SELECT", "biller");
                return $rows[0]['count'];
            }

            $expr_list = array("id", "domain_id", "name", "email");
            $pdoDb->setSelectList($expr_list);
            $pdoDb->setGroupBy($expr_list);

            $case = new CaseStmt("enabled");
            $case->addWhen("=", ENABLED, $LANG['enabled']);
            $case->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($case);

            if (empty($sort) ||
                !in_array($sort, array("id", "name", "email", 'enabled'))) $sort = "id";
            if (empty($dir)) $dir = "DESC";
            $pdoDb->setOrderBy(array($sort, $dir));

            $rows = $pdoDb->request("SELECT", "biller");
        } catch (PdoDbException $pde) {
            error_log("Biller::sql() - Error: " . $pde->getMessage());
            return array();
        }
        return $rows;
    }

}
