<?php

namespace Inc\Claz;

/**
 * Class ExpenseAccount
 * @package Inc\Claz
 */
class ExpenseAccount
{

    /**
     * Get count of expense_account records for the current domain.
     * @return int count of records.
     */
    public static function count(): int
    {
        $rows = self::getExpenseAccounts();
        return count($rows);
    }

    /**
     * Retrieve <i>expense_account</i> record for the current domain and the specified <b>$id</b>
     * @param int $id ID of expense record to retrieve.
     * @return array Result
     */
    public static function getOne(int $id): array
    {
        return self::getExpenseAccounts($id);
    }

    /**
     * Get all records for the current domain_id.
     * @return array Rows retrieved.
     */
    public static function getAll(): array
    {
        return self::getExpenseAccounts();
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo(): array
    {
        global $LANG;

        $rows = self::getExpenseAccounts();
        $tableRows = [];
        foreach ($rows as $row) {
            $viewName = $LANG['view'] . ' ' . $row['name'];
            $editName = $LANG['edit'] . ' ' . $row['name'];

            $action =
                "<a class='index_table' title='$viewName' " .
                   "href='index.php?module=expense_account&amp;view=view&amp;id={$row['id']}'>" .
                    "<img src='images/view.png' alt='$viewName' />" .
                "</a>&nbsp;&nbsp;" .
                "<a class='index_table' title='$editName' " .
                   "href='index.php?module=expense_account&amp;view=edit&amp;id={$row['id']}'>" .
                    "<img src='images/edit.png' alt='$editName' />" .
                "</a>";

            $tableRows[] = [
                'action' => $action,
                'name' => $row['name']
            ];
        }

        return $tableRows;
    }

    /**
     * Get all records for the current domain_id and optional id.
     * @param int|null $id ID of the record to retrieve. Omit if all records desired.
     * @return array Rows retrieved.
     */
    private static function getExpenseAccounts(?int $id = null): array
    {
        global $pdoDb;

        $rows = [];
        try {
            if (isset($id)) {
                $pdoDb->addSimpleWhere('id', $id, 'AND');
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $pdoDb->setOrderBy("id");

            $rows = $pdoDb->request("SELECT", "expense_account");
        } catch (PdoDbException $pde) {
            error_log("ExpenseAccount::getAll() - error: " . $pde->getMessage());
        }

        if (empty($rows)) {
            return [];
        }

        return isset($id) ? $rows[0] : $rows;
    }

    /**
     * Insert a new <i>expense_account</i> record.
     * @return int ID of new record. 0 if insert failed.
     */
    public static function insert(): int
    {
        global $pdoDb;

        $id = 0;
        try {
            $pdoDb->setExcludedFields("id");
            $pdoDb->setFauxPost([
                "domain_id" => DomainId::get(),
                "name" => $_POST["name"]
            ]);
            $id = $pdoDb->request("INSERT", "expense_account");
        } catch (PdoDbException $pde) {
            error_log("ExpenseAccount::insert() - error: " . $pde->getMessage());
        }
        return $id;
    }

    /**
     * Update <i>expense_account</i> record.
     * @return bool <b>true</b> if record inserted, <b>false</b> if an error occurred.
     */
    public static function update(): bool
    {
        global $pdoDb;

        $result = false;
        try {
            $pdoDb->setExcludedFields(["id", "domain_id"]);
            $pdoDb->setFauxPost(["name" => $_POST["name"]]);
            $pdoDb->addSimpleWhere("domain_id", DomainId::get(), "AND");
            $pdoDb->addSimpleWhere("id", $_GET["id"]);
            $result = $pdoDb->request("UPDATE", "expense_account");
        } catch (PdoDbException $pde) {
            error_log("ExpenseAccount::update() - error: " . $pde->getMessage());
        }
        return $result;
    }

}
