<?php

namespace Inc\Claz;

/**
 * Class ProductAttributeValues
 * @package Inc\Claz
 */
class ProductAttributeValues
{
    /**
     * Get count of products_attributes_values records.
     * @return int count of products_attributes_values records
     */
    public static function count(): int
    {
        global $pdoDb;

        $rows = [];
        try {
            $rows = $pdoDb->request("SELECT", "products_attributes_values");
        } catch (PdoDbException $pde) {
            error_log("ProductAttributeValues::count() - Error: " . $pde->getMessage());
        }
        return count($rows);
    }

    /**
     * Check for duplicate record based on $attributeId and $value.
     * @param int $attributeId to test for.
     * @param string $value to test for.
     * @return bool true if the $value exists for the $attributeId; false if not.
     */
    public static function isDuplicate(int $attributeId, string $value): bool
    {
        global $pdoDb;

        $rows = [];
        try {
            $pdoDb->addSimpleWhere('attribute_id', $attributeId, 'AND');
            $pdoDb->addSimpleWhere('value', $value);
            $rows = $pdoDb->request('SELECT', 'products_attributes_values');
        } catch (PdoDbException $pde) {
            error_log("ProductAttributeValues::isDuplicate() - attribute_id[$attributeId] value[$value] - error: " . $pde->getMessage());
        }

        return !empty($rows);
    }

    /**
     * Retrieve a specific product_attribute_values record.
     * @param int $id of record to retrieve.
     * @return array Record retrieved. Check for empty for no records found.
     */
    public static function getOne(int $id): array
    {
        return self::getProductAttributeValues($id);
    }

    /**
     * Retrieve all product_attribute_values records.
     * @return array Records retrieved.
     */
    public static function getAll(): array
    {
        return self::getProductAttributeValues();
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo(): array
    {
        global $LANG;

        $rows = self::getProductAttributeValues();
        $tableRows = [];
        foreach ($rows as $row) {
            $viewName = $LANG['view'] . ' ' . $row['name'];
            $editName = $LANG['edit'] . ' ' . $row['name'];

            $action =
                    "<a class='index_table' title='$viewName' " .
                       "href='index.php?module=product_attribute_values&amp;view=view&amp;id={$row['id']}'>" .
                        "<img src='images/view.png' alt='$viewName' class='action' />" .
                    "</a>&nbsp;&nbsp;" .
                    "<a class='index_table' title='$editName' " .
                       "href='index.php?module=product_attribute_values&amp;view=edit&amp;id={$row['id']}'>" .
                        "<img src='images/edit.png' alt='$editName' class='action'/>" .
                    "</a>";

            $image = $row['enabled'] == ENABLED ? "images/tick.png" : "images/cross.png";
            $enabled = "<span style='display: none'>{$row['enabled_text']}</span>" .
                       "<img src='$image' alt='{$row['enabled_text']}' title='{$row['enabled_text']}' />";

            $tableRows[] = [
                'action' => $action,
                'name' => $row['name'],
                'value' => $row['value'],
                'enabled' => $enabled
            ];
        }

        return $tableRows;
    }

    /**
     * @param int|null $id If not null, id of record to retrieve.
     * @return array Record retrieved.
     */
    private static function getProductAttributeValues(?int $id = null): array
    {
        global $LANG, $pdoDb;

        $productAttributeValues = [];
        try {
            if (isset($id)) {
                $pdoDb->addSimpleWhere('pv.id', $id);
            }

            $pdoDb->setOrderBy([['name','A'],['value','A']]);

            $pdoDb->setSelectList([
                new DbField('pv.id', 'id'),
                new DbField('pa.name', 'name'),
                new DbField('pv.value', 'value'),
                new DbField('pv.enabled', 'enabled'),
                new DbField('pv.attribute_id', 'attribute_id')
            ]);

            $ca = new CaseStmt("pv.enabled", "enabled_text");
            $ca->addWhen("=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $jn = new Join('LEFT', 'products_attributes', 'pa');
            $jn->addSimpleItem('pa.id', new DbField('pv.attribute_id'));
            $pdoDb->addToJoins($jn);

            $productAttributeValues = $pdoDb->request('SELECT', 'products_attributes_values', 'pv');
        } catch (PdoDbException $pde) {
            error_log("ProductAttributeValues::getProductAttributeValues() - Error: " . $pde->getMessage());
        }

        if (empty($productAttributeValues)) {
            return [];
        }
        return isset($id) ? $productAttributeValues[0] : $productAttributeValues;
    }

    /*
     * Retrieve the value for a specified product attribute and value id.
     *
     * @param int $attributeId for product_attributes record to get the value for.
     * @param int $valueId of product_attribute_values record containing the value to return.
     * @return string If <b>attribute_id</b> is for a type, <i>list</i>, product,
     *         return the value from the <i>products_attributes_values</i> record for the
     *         specified <b>value_id</b>. Otherwise return the <b>value_id</b> parameter.
     */
    //    public static function getValue(int $attributeId, int $valueId): string
    //    {
    //        $row = ProductAttributes::getOne($attributeId);
    //
    //        if (!empty($row) && $row['type'] == 'list') {
    //            $row = self::getOne($valueId);
    //            if (!empty($row)) {
    //                return $row['value'];
    //            }
    //        }
    //        return $valueId;
    //    }

    /**
     * Insert a new product_attribute_values record using values from the $_POST global.
     * Note: DO NOT include the 'id' field in the $_POST as it is auto assigned.
     * @return int 0 if insert failed, otherwise the 'id' assigned to the new row.
     */
    public static function insert(): int
    {
        global $pdoDb;

        $result = 0;
        try {
            $pdoDb->setExcludedFields('id');
            $result = $pdoDb->request('INSERT', 'products_attributes_values');
        } catch (PdoDbException $pde) {
            error_log('ProductAttributeValues::insert() - Error: ' . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Update the product_attribute_values record from values in $_POST global for
     * the "id" in the $_GET['id'] global.
     * @return bool
     */
    public static function update(): bool
    {
        global $pdoDb;

        $result = false;
        try {
            $pdoDb->addSimpleWhere('id', $_GET['id']);
            $pdoDb->setExcludedFields('id');
            $result = $pdoDb->request('UPDATE', 'products_attributes_values');
        } catch (PdoDbException $pde) {
            error_log('ProductAttributeValues::update() - Error: ' . $pde->getMessage());
        }
        return $result;
    }
}
