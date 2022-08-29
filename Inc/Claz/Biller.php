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
     * @param int $id ID of the biller to retrieve.
     * @return array Associative array for record retrieved.
     */
    public static function getOne(int $id): array
    {
        $rows = self::getBillers($id);
        return empty($rows) ? [] : $rows[0];
    }

    /**
     * Get all biller records.
     * @param bool $active_only Set to <b>true</b> to get active billers only.
     *        Set to <b>false</b> or don't specify anything if you want all billers.
     * @return array Biller records retrieved.
     */
    public static function getAll(bool $active_only = false): array
    {
        return self::getBillers(null, $active_only);
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
            $action =
                    "<a class='index_table' title='{$LANG['view']} {$row['name']})' href='index.php?module=billers&amp;view=view&amp;id={$row['id']}'>" .
                        "<img src='images/view.png' class='action' alt='view'/>" .
                    "</a>&nbsp;" .
                    "<a class='index_table' title='{$LANG['edit']} {$row['name']}' href='index.php?module=billers&amp;view=edit&amp;id={$row['id']}'>" .
                        "<img src='images/edit.png' class='action' alt='edit'/>" .
                    "</a>";

            $enabled = $row['enabled'] == ENABLED;
            $image = $enabled ? "images/tick.png" : "images/cross.png";
            $enabledCol = "<span style='display: none'>{$row['enabled_text']}</span>" .
                "<img src='$image' alt='{$row['enabled_text']}' title='{$row['enabled_text']}' />";

            $tableRows[] = [
                'action' => $action,
                'name' => $row['name'],
                'streetAddress' => $row['street_address'],
                'city' => $row['city'],
                'state' => $row['state'],
                'zipCode' => $row['zip_code'],
                'email' => $row['email'],
                'enabled' => $enabledCol
            ];
        }

        return $tableRows;
    }

    /**
     * Get all biller records.
     * @param int|null $id if not null, get record for that id; otherwise get all records
     *        based on $active_only setting.
     * @param bool $active_only Set to <b>true</b> to get active billers only.
     *        Set to <b>false</b> or don't specify anything if you want all billers.
     * @return array Biller records retrieved.
     */
    private static function getBillers(?int $id, bool $active_only = false): array
    {
        global $LANG, $pdoDb;

        $billers = [];
        try {
            session_name('SiAuth');
            session_start();

            // If user role is customer or biller, then restrict billers to the one for this session.
            if ($_SESSION['role_name'] == 'biller') {
                $pdoDb->addSimpleWhere("id", $_SESSION['user_id'], "AND");
            }

            if (!empty($id)) {
                $pdoDb->addSimpleWhere("id", $id, "AND");
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

        $billers = [];
        try {
            $pdoDb->addSimpleWhere("s.name", "biller", "AND");
            $pdoDb->addSimpleWhere("s.domain_id", DomainId::get());
            $rows = $pdoDb->request('SELECT', 'system_defaults', 's');
            if (!empty($rows)) {
                $billerId = $rows[0]['value'];
                $pdoDb->addSimpleWhere('id', $billerId);
                $billers = $pdoDb->request('SELECT', 'biller');
            }
        } catch (PdoDbException $pde) {
            error_log("Biller::getDefaultBiller(): error: " . $pde->getMessage());
        }
        return empty($billers) ? ["name" => '', "email" => ''] : $billers[0];
    }

    /**
     * Insert a new biller record
     * @return int ID of new record. 0 if failed to insert.
     */
    public static function insertBiller(): int
    {
        global $pdoDb;
        $_POST['domain_id'] = DomainId::get();
        if (empty($_POST['custom_field1'])) {
            $_POST['custom_field1'] = "";
        }
        if (empty($_POST['custom_field2'])) {
            $_POST['custom_field2'] = "";
        }
        if (empty($_POST['custom_field3'])) {
            $_POST['custom_field3'] = "";
        }
        if (empty($_POST['custom_field4'])) {
            $_POST['custom_field4'] = "";
        }

        $_POST['notes'] = empty($_POST['note']) ? "" : trim($_POST['note']);

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
            // The fields to be updated must be in the $_POST array indexed by their
            // actual field name.
            $pdoDb->setExcludedFields(["id", "domain_id"]);

            $pdoDb->addSimpleWhere("id", $_GET['id']);

            $result = $pdoDb->request("UPDATE", "biller");
        } catch (PdoDbException $pde) {
            error_log("Biller::updateBiller() - error: " . $pde->getMessage());
        }
        return $result;
    }

}
