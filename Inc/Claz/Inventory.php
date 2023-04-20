<?php

namespace Inc\Claz;

use Exception;

/**
 * Class Inventory
 */
class Inventory
{

    /**
     * @return int Count of rows in table.
     */
    public static function count(): int
    {
        global $pdoDb;

        $rows = 0;
        try {
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $rows = $pdoDb->request("SELECT", "inventory");
        } catch (PdoDbException $pde) {
            error_log("Inventory::count() - Error: " . $pde->getMessage());
        }
        return count($rows);
    }

    /**
     * Retrieve a specific inventory record.
     * @param int $inv_id of Inventory record to retrieve.
     * @return array Row retrieved. Test for empty for no record found.
     */
    public static function getOne(int $inv_id): array
    {
        return self::getInventories($inv_id);
    }

    /**
     * Retrieve all inventory records.
     * @return array of records retrieved. Test for empty if none found.
     */
    public static function getAll(): array
    {
        return self::getInventories();
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo(): array
    {
        global $config;

        $rows = self::getInventories();
        $tableRows = [];
        foreach ($rows as $row) {
            // @formatter:off
            $action = "<a class='index_table' title='{$row['vname']}' " .
                         "href='index.php?module=inventory&amp;view=view&amp;id={$row['id']}'>" .
                            "<img src='images/view.png' class='action' alt='{$row['vname']}' />" .
                       "</a>&nbsp;&nbsp;" .
                      "<a class='index_table' title='{$row['ename']}' " .
                         "href='index.php?module=inventory&amp;view=edit&amp;id={$row['id']}'>" .
                          "<img src='images/edit.png' class='action' alt='{$row['ename']}' />" .
                      "</a>";
            // @formatter:on

            $tableRows[] = [
                'action' => $action,
                'date' => $row['date'],
                'description' => $row['description'],
                'quantity' => $row['quantity'],
                'cost' => $row['cost'],
                'totalCost' => $row['total_cost'],
                'currencyCode' => $config['localCurrencyCode'],
                'locale' => preg_replace('/^(.*)_(.*)$/', '$1-$2', $config['localLocale'])
            ];
        }
        return $tableRows;
    }

    /**
     * Retrieve inventory record(s).
     * @param int|null $inv_id ID of the inventory to retrieve or 0 if all records to be retrieved.
     * @return array Row(s) retrieved.
     */
    private static function getInventories(?int $inv_id = null): array
    {
        global $config, $LANG, $pdoDb;

        $inventories = [];
        try {
            if (isset($inv_id)) {
                $pdoDb->addSimpleWhere('inv.id', $inv_id, 'AND');
            }
            $pdoDb->addSimpleWhere('inv.domain_id', DomainId::get());

            $jn = new Join('LEFT', 'products', 'p');
            $jn->addSimpleItem('p.id', new DbField('inv.product_id'), 'AND');
            $jn->addSimpleItem('p.domain_id', new DbField('inv.domain_id'));
            $pdoDb->addToJoins($jn);

            $pdoDb->setOrderBy('p.id');

            $pdoDb->addToFunctions(new FunctionStmt('COALESCE', 'p.reorder_level,0', 'reorder_level'));
            $pdoDb->addToFunctions(new FunctionStmt('COALESCE', 'inv.quantity * inv.cost,0', 'total_cost'));
            $exprList = [
                'inv.id',
                'inv.product_id',
                'inv.quantity',
                'inv.cost',
                'inv.date',
                'inv.note',
                'p.description'
            ];
            $pdoDb->setSelectList($exprList);
            $pdoDb->setGroupBy($exprList);

            $rows = $pdoDb->request('SELECT', 'inventory', 'inv');
            foreach ($rows as $row) {
                $row['vname'] = $LANG['view'] . ' ' . $row['id'];
                $row['ename'] = $LANG['edit'] . ' ' . $row['id'];

                $row['locale'] = $config['localLocale'];
                $row['currency_code'] = $config['localCurrencyCode'];

                $inventories[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Inventory::getInventories() - Error: " . $pde->getMessage());
        }
        if (empty($inventories)) {
            return [];
        }
        return isset($inv_id) ? $inventories[0] : $inventories;
    }

    /**
     * @return int|mixed
     */
    public static function insert()
    {
        global $pdoDb;

        $result = 0;
        try {
            $pdoDb->setExcludedFields('id');
            $result = $pdoDb->request('INSERT', 'inventory');
        } catch (PdoDbException $pde) {
            error_log("Inventory::insert(): Error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * @return bool|mixed
     */
    public static function update()
    {
        global $pdoDb;

        $result = false;
        try {
            $pdoDb->setExcludedFields(['id', 'domain_id']);
            $pdoDb->addSimpleWhere('id', $_GET['id'], 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $result = $pdoDb->request('UPDATE', 'inventory');
        } catch (PdoDbException $pde) {
            error_log("Inventory::update() - Error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    public static function delete()
    {
        throw new Exception("inventory::delete(): delete not supported.");
    }

    /**
     * Send a reorder request to the biller. Note that it comes from the biller also.
     * @return array
     */
    public static function sendReorderNotificationEmail(): array
    {
        $biller = Biller::getDefaultBiller();

        $rows = self::getAll();
        $result = [];
        $emailMessage = '';
        foreach ($rows as $row) {
            if (($qty = Util::number($row['quantity'])) < 0) {
                $qty = 0;
            }

            if ($qty <= $row['reorder_level']) {
                $message = "Time to reorder product, {$row['description']}. " .
                    "Quantity on hand is " . Util::number($row['quantity']) .
                    ". , which is equal to or below its reorder level of {$row['reorder_level']}";
                $result[$row['id']]['message'] = $message;
                $emailMessage .= $message . "<br />\n";
            }
        }

        $email = new Email();
        $email->setBody($emailMessage);
        $email->setFrom([$biller['email'] => $biller['name']]);
        $email->setEmailTo([$biller['email'] => $biller['name']]);
        $email->setSubject("SimpleInvoices inventory reorder level email");
        $email->send();

        return $result;
    }
}
