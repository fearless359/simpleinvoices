<?php
namespace Inc\Claz;

/**
 * Class ExpenseAccount
 * @package Inc\Claz
 */
class ExpenseAccount {

    /**
     * Get count of expense_account records for the current domain.
     * @return integer count of records.
     */
    public static function count() {
        global $pdoDb;

        $count = 0;
        try {
            $pdoDb->addToFunctions("count(*) as count");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $rows = $pdoDb->request("SELECT", "expense_account");
            $count = $rows[0]['count'];
        } catch (PdoDbException $pde) {
            error_log("ExpenseAccount::count() - error: " . $pde->getMessage());
        }
        return $count;
    }

    /**
     * Get all records for the current domain_id.
     * @return array Rows retrieved.
     */
    public static function getAll() {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->setOrderBy("id");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $rows = $pdoDb->request("SELECT", "expense_account");
        } catch (PdoDbException $pde) {
            error_log("ExpenseAccount::getAll() - error: " . $pde->getMessage());
        }
        return $rows;
    }

    /**
     * Retrieve <i>expense_account</i> record for the current domain and the specified <b>$id</b>
     * @param number $id ID of expense record to retrieve.
     * @return mixed Result
     */
    public static function select($id) {
        global $pdoDb;

        $expense_account = array();
        try {
            $pdoDb->addSimpleWhere("domain_id", DomainId::get(), 'AND');
            $pdoDb->addSimpleWhere("id", $id);
            $rows = $pdoDb->request("SELECT", "expense_account");
            $expense_account = $rows[0];
        } catch (PdoDbException $pde) {
            error_log("ExpenseAccount::select() - id($id] error: " . $pde->getMessage());
        }
        return $expense_account;
    }

    /**
     * Insert a new <i>expense_account</i> record.
     * @return int ID of new record. 0 if insert failed.
     */
    public static function insert() {
        global $pdoDb;

        $id = 0;
        try {
            $pdoDb->setExcludedFields("id");
            $pdoDb->setFauxPost(array("domain_id" => DomainId::get(),
                                      "name" => $_POST["name"]));
            $id = $pdoDb->request("INSERT", "expense_account");
        } catch (PdoDbException $pde) {
            error_log("ExpenseAccount::insert() - error: " . $pde->getMessage());
        }
        return $id;
    }

    /**
     * Update <i>expense_account</i> record.
     * @return boolean <b>true</b> if record inserted, <b>false</b> if an error occurred.
     */
    public static function update() {
        global $pdoDb;

        $result = false;
        try {
            $pdoDb->setExcludedFields(array("id", "domain_id"));
            $pdoDb->setFauxPost(array("name" => $_POST["name"]));
            $pdoDb->addSimpleWhere("domain_id", DomainId::get(), "AND");
            $pdoDb->addSimpleWhere("id", $_GET["id"]);
            $result = $pdoDb->request("UPDATE", "expense_account");
        } catch (PdoDbException $pde) {
            error_log("ExpenseAccount::update() - error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * @param string $type set to "count" if count of qualified records to be returned.
     *          All other values cause selected rows to be returned.
     * @param string $dir sort by direction (ASC/DESC)
     * @param string $sort field to sort by
     * @param int $rp report lines per page
     * @param int $page of current page
     * @return array/int If $type is count, the int count value is returned. Otherwise an
     *          array of rows selected is returned.
     */
    public static function xmlSql($type, $dir, $sort, $rp, $page ) {
        global $pdoDb;

        $rows = array();
        try {
            $validFields = array('id', 'name');

            // Set up WHERE clause which is needed for both count and data access modes.
            if (!empty($_POST['qtype']) && !empty($_POST['query'])) {
                $qtype = $_POST['qtype'];
                if (in_array($qtype, $validFields)) {
                    $query = $_POST['query'];
                    $pdoDb->addToWhere(new WhereItem(false, $qtype, 'LIKE', "%$query%", false, "AND"));
                }
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            if ($type == "count") {
                $pdoDb->addToFunctions("count(*) AS count");
                $rows = $pdoDb->request("SELECT", "expense_account");
                return (empty($rows) ? 0 : $rows[0]['count']);
            }

            if (!in_array($sort, $validFields)) $sort = "id";

            // Set up start offset.
            if (empty($page) || intval($page) != $page) $page = 1;

            if (intval($rp) != $rp) $rp = 25;

            $start = (($page - 1) * $rp);
            $pdoDb->setLimit($rp, $start);

            if (!preg_match('/^(asc|desc)$/iD', $dir)) $sort .= ' DESC';

            $rows = $pdoDb->request("SELECT", "expense_account");
        } catch (PdoDbException $pde) {
            error_log("ExpenseAccount::xmlSql() - Error: " . $pde->getMessage());
        }

        return $rows;
    }

}
