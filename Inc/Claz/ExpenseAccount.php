<?php
namespace Inc\Claz;

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
}
