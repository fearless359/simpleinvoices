<?php

namespace Inc\Claz;

/**
 *  ProductAttributes class.
 * @author Richard Rowley
 *
 *  Last modified:
 *      2016-08-15
 */
class ProductAttributes
{

    /**
     * Retrieve a specific products_attributes record and associated product_attributes_type
     * information.
     * @param int $id of record to retrieve.
     * @return array Associative array for record retrieved. Test for empty for no matching record.
     */
    public static function getOne(int $id): array
    {
        return self::getProductAttributes($id);
    }

    /**
     * Retrieve all <b>products_attributes</b> records
     * @param bool $enabled Set to true to retrieve enabled records only.
     *          Set to false (default) to retrieve all records.
     * @return array Rows from table. Test for empty result for nothing found.
     */
    public static function getAll(bool $enabled = false): array
    {
        return self::getProductAttributes(null, $enabled);
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo(): array
    {
        global $LANG;

        $rows = self::getProductAttributes();
        $tableRows = [];
        foreach ($rows as $row) {
            $viewName = $LANG['view'] . ' ' . $row['name'];
            $editName = $LANG['edit'] . ' ' . $row['name'];

            $action =
                  "<a class='index_table' title='$viewName' " .
                     "href='index.php?module=product_attribute&amp;view=view&amp;id={$row['id']}''>" .
                    "<img src='images/view.png' alt='$viewName' class='action'/>" .
                  "</a>&nbsp;&nbsp;" .
                  "<a class='index_table' title='$editName' " .
                     "href='index.php?module=product_attribute&amp;view=edit&amp;id={$row['id']}'>" .
                    "<img src='images/edit.png' alt='$editName' class='action'/>" .
                  "</a>";

            $image = $row['enabled'] == ENABLED ? "images/tick.png" : "images/cross.png";
            $enabled = "<span style='display:none'>{$row['enabled_text']}</span>" .
                       "<img src='$image' alt='{$LANG['enabled']}'>";

            $image = $row['enabled'] == ENABLED ? "images/tick.png" : "images/cross.png";
            $visible = "<span style='display:none'>{$row['visible_text']}</span>" .
                       "<img src='$image' alt='{$LANG['enabled']}'>";


                $tableRows[] = [
                'action' => $action,
                'name' => $row['name'],
                'typeName' => ucwords($row['type_name']),
                'enabled' => $enabled,
                'visible' => $visible
            ];
        }

        return $tableRows;
    }

    /**
     * Retrieve product_attributes record(s) and associated information.
     * @param int|null $id ID of record to retrieve.
     * @param bool $enabled true if only enabled records are to be retrieved;
     *              false if all records are to be retrieved.
     * @return array Records retrieved. Test empty result for no records found.
     */
    private static function getProductAttributes(?int $id = null, bool $enabled = false): array
    {
        global $LANG, $pdoDb;

        $productAttributes = [];
        try {
            $pdoDb->setSelectList([
                new DbField('pa.id', 'id'),
                new DbField('pa.name', 'name'),
                new DbField('pa.type_id', 'typeId'),
                new DbField('pa.enabled', 'enabled'),
                new DbField('pa.visible', 'visible'),
                new DbField('pat.name', 'type')
            ]);

            $connector = isset($id) && $enabled ? 'AND' : '';
            if ($enabled) {
                $pdoDb->addSimpleWhere('pa.enabled', ENABLED, $connector);
            }

            if (isset($id)) {
                $pdoDb->addSimpleWhere("pa.id", $id);
            }

            $jn = new Join('LEFT', 'products_attribute_type', 'pat');
            $jn->addSimpleItem('pa.type_id', new DbField("pat.id"));
            $pdoDb->addToJoins($jn);

            $ca = new CaseStmt("visible", "visibleText");
            $ca->addWhen("=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $ca = new CaseStmt("enabled", "enabledText");
            $ca->addWhen("=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $rows = $pdoDb->request("SELECT", "products_attributes", 'pa');
        } catch (PdoDbException $pde) {
            error_log("ProductAttributes::getOne() - id[$id] error: {$pde->getMessage()}");
            return [];
        }

        if (empty($rows)) {
            return [];
        }

        $attrVals = null;
        foreach ($rows as $row) {
            if ($row['enabled'] == ENABLED) {
                try {
                    $pdoDb->setSelectList([
                        new DbField('a.name', 'attrName'),
                        new DbField('v.id', 'id'),
                        new DbField('v.value', 'value'),
                        new DbField('v.enabled', 'enabled')
                    ]);

                    $jn = new Join("LEFT", "products_attributes_values", "v");
                    $jn->addSimpleItem("v.attribute_id", $row["id"]);
                    $pdoDb->addToJoins($jn);

                    if ($enabled) {
                        $pdoDb->addSimpleWhere('v.enabled', ENABLED, 'AND');
                    }

                    $pdoDb->addSimpleWhere('a.id', $row['id']);

                    $pdoDb->setOrderBy([['attrName', 'A'], ['value', 'A']]);

                    $attrVals = $pdoDb->request('SELECT', 'products_attributes', 'a');
                } catch (PdoDbException $pde) {
                    error_log("ProductAttributes::getOne() - Error access products_attributes_values for id[$id] error: {$pde->getMessage()}");
                }
            }

            $row['attrVals'] = $attrVals;
            $productAttributes[] = $row;
        }

        return isset($id) ? $productAttributes[0] : $productAttributes;
    }

    /**
     * Get matrix of product attributes.
     * @return array Matrix values array of <i>product_attributes</i> with associated <i>products_attributes_values</i>.
     */
    public static function getMatrix(): array
    {
        global $pdoDb;

        $rows = [];
        try {
            $fn = new FunctionStmt(null, "CONCAT(a.id, '-', v.id)", "id");
            $pdoDb->addToFunctions($fn);

            $fn = new FunctionStmt(null, "CONCAT(a.name, '-', v.value)", "display");
            $pdoDb->addToFunctions($fn);

            $jn = new Join("LEFT", "products_attributes_values", "v");
            $jn->addSimpleItem("a.id", new DbField("v.attribute_id"));
            $pdoDb->addToJoins($jn);

            $rows = $pdoDb->request("SELECT", "products_attributes", "a");
        } catch (PdoDbException $pde) {
            error_log("ProductAttributes::getMatrix() - Error: " . $pde->getMessage());
        }
        return $rows;
    }

    /**
     * Insert a new product_attributes record using values from the $_POST global.
     * Note: DO NOT include the 'id' field in the $_POST as it is auto assigned.
     * @return int 0 if insert failed, otherwise the 'id' assigned to the new row.
     */
    public static function insert(): int
    {
        global $pdoDb;

        $result = 0;
        try {
            $pdoDb->setExcludedFields("id");
            $result = $pdoDb->request('INSERT', 'products_attributes');
        } catch (PdoDbException $pde) {
            error_log('ProductAttributes::insert() - Error: ' . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Update the product_attributes record from values in $_POST global for
     * the "id" in the $_GET['id'] global.
     */
    public static function update(): bool
    {
        global $pdoDb;

        $result = false;
        try {
            $pdoDb->addSimpleWhere('id', $_GET['id']);
            $result = $pdoDb->request('UPDATE', 'products_attributes');
        } catch (PdoDbException $pde) {
            error_log('ProductAttributes::update() - Error: ' . $pde->getMessage());
        }
        return $result;
    }

}
