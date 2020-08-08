<?php
namespace Inc\Claz;

/**
 * Class Biller
 * @package Inc\Claz
 */
class Biller
{
    /**
     * Calculate the number of invoices in the database
     * @return int Count of invoices in the database
     */
    public static function count(): int
    {
        global $pdoDb;

        $count = 0;
        try {
            $pdoDb->setSelectAll(false);
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
     * Retrieve a specified biller record.
     * @param string $id ID of the biller to retrieve.
     * @return array Associative array for record retrieved.
     */
    public static function getOne($id): array
    {
        $rows = self::getBillers($id);
        return (empty($rows) ? array() : $rows[0]);
    }

    /**
     * Get all biller records.
     * @param boolean $active_only Set to <b>true</b> to get active billers only.
     *        Set to <b>false</b> or don't specify anything if you want all billers.
     * @return array Biller records retrieved.
     */
    public static function getAll($active_only = false): array
    {
        return self::getBillers(null, $active_only);
    }

    /**
     * Get all biller records.
     * @param int $id if not null, get record for that id; otherwise get all records
     *        based on $active_only setting.
     * @param boolean $active_only Set to <b>true</b> to get active billers only.
     *        Set to <b>false</b> or don't specify anything if you want all billers.
     * @return array Biller records retrieved.
     */
    private static function getBillers($id, $active_only = false): array
    {
        global $LANG, $pdoDb;

        $billers = array();
        try {
            if (isset($id)) {
                $pdoDb->addSimpleWhere('id', $id, 'AND');
            }

            if ($active_only) {
                $pdoDb->addSimpleWhere("enabled", ENABLED, "AND");
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $ca = new CaseStmt("enabled", "enabled_text");
            $ca->addWhen("=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $pdoDb->setOrderBy("name");

            $pdoDb->setSelectAll(true);

            $billers = $pdoDb->request("SELECT", "biller");
        } catch (PdoDbException $pde) {
            error_log("Biller::getAll() error: " . $pde->getMessage());
        }
        return $billers;
    }

    /**
     * Get a default biller name.
     * @return array Default biller name
     */
    public static function getDefaultBiller(): array
    {
        global $pdoDb;

        $billers = array();
        try {
            $pdoDb->addSimpleWhere("s.name", "biller", "AND");
            $pdoDb->addSimpleWhere("s.domain_id", DomainId::get());
            $rows = $pdoDb->request('SELECT', 'system_defaults', 's');
            if (!empty($rows)) {
                $biller_id = $rows[0]['value'];
                $pdoDb->addSimpleWhere('id', $biller_id);
                $billers = $pdoDb->request('SELECT', 'biller');
            }
        } catch (PdoDbException $pde) {
            error_log("Biller::getDefaultBiller(): error: " . $pde->getMessage());
        }
        return (empty($billers) ? array("name" => '') : $billers[0]);
    }

    /**
     * Insert a new biller record
     * @return int ID of new record. 0 if failed to insert.
     */
    public static function insertBiller(): int
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
     * @return bool <b>true</b> if update successful
     */
    public static function updateBiller(): bool
    {
        global $pdoDb;

        $result = false;
        try {
            // The fields to be update must be in the $_POST array indexed by their
            // actual field name.
            $pdoDb->setExcludedFields(array("id", "domain_id"));

            $pdoDb->addSimpleWhere("id", $_GET['id']);

            $result = $pdoDb->request("UPDATE", "biller");
        } catch (PdoDbException $pde) {
            error_log("Biller::updateBiller() - error: " . $pde->getMessage());
        }
        return $result;
    }

}
