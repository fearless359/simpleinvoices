<?php

namespace Inc\Claz;

/**
 * Class ProductGroups
 */
class ProductGroups
{
    public static function getOne(string $name): array
    {
        $rows = self::getGroups($name);
        return empty($rows) ? [] : $rows[0];
    }

    public static function getAll():array
    {
        return self::getGroups();
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo(): array
    {
        global $LANG;

        $rows = self::getGroups();
        $tableRows = [];
        foreach ($rows as $row) {
            $action =
                "<a class='index_table' title='{$LANG['view']} {$row['name']}' href='index.php?module=product_groups&amp;view=view&amp;name={$row['name']}'>" .
                    "<img src='images/view.png' class='action' alt='view'/>" .
                "</a>&nbsp;&nbsp;" .
                "<a class='index_table' title='{$LANG['edit']} {$row['name']}' href='index.php?module=product_groups&amp;view=edit&amp;name={$row['name']}'>" .
                    "<img src='images/edit.png' class='action' alt='edit'/>" .
                "</a>";

            $tableRows[] = [
                'action' => $action,
                'name' => $row['name'],
                'markUp' => $row['markup'] . '%'
            ];
        }

        return $tableRows;
    }

    private static function getGroups(?string $name = null): array
    {
        global $pdoDb;

        try {
            if (!empty($name)) {
                $pdoDb->addSimpleWhere('name', $name);
            }
            $groups = $pdoDb->request('SELECT', 'product_groups');
        } catch (PdoDbException $pde) {
            $str = "ProductGroups::getGroups() - Unexpected database error: {$pde->getMessage()}";
            error_log($str);
            exit($str);
        }
        return $groups;
    }

    /**
     * Insert a new product group. This value inserted will come from the super global $_POST and
     * the field in this area must match the database fields. In this case, $_POST['name'] & $_POST['markup'].
     * Note that since there is no ID field in this set, no last inserted ID is available to return.
     * @return bool true if success, false if failure.
     */
    public static function insertGroup(): bool
    {
        global $pdoDb;

        try {
            $pdoDb->request('INSERT', 'product_groups');
            $result = true;
        } catch (PdoDbException $pde) {
            error_log("ProductGroups::insertGroup() - Error: {$pde->getMessage()}");
            $result = false;
        }
        return $result;
    }

    public static function updateGroup(): bool
    {
        global $pdoDb;

        try {
            $pdoDb->setExcludedFields("name");
            $pdoDb->addSimpleWhere("name", $_POST['name']);
            $result = $pdoDb->request('UPDATE', 'product_groups');
        } catch (PdoDbException $pde) {
            error_log("ProductGroups::updateGroup() - Error: {$pde->getMessage()}");
            $result = false;
        }
        return $result;
    }
}
