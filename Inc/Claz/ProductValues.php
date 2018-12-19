<?php
namespace Inc\Claz;

/**
 * Class ProductValues
 * @package Inc\Claz
 */
class ProductValues
{
    /**
     * Get count of products_values records.
     * @return integer
     */
    public static function count()
    {
        global $pdoDb;

        $rows = array();
        try {
            $rows = $pdoDb->request("SELECT", "products_values");
        } catch (PdoDbException $pde) {
            error_log("ProductValues::count() - Error: " . $pde->getMessage());
        }
        return count($rows);
    }

    /**
     * Retrieve a specific product_values record.
     * @param int $id of record to retrieve.
     * @return array Record retrieved. Check for empty for no records found.
     */
    public static function getOne($id)
    {
        return self::getProductValues($id);
    }

    /**
     * Retrieve all product_values records.
     * Retrieve all product_values records.
     * @return array Records retrieved.
     */
    public static function getAll()
    {
        return self::getProductValues();
    }

    /**
     * Retrieve the value for a specified product attribute and value id.
     *
     * @param int $attribute_id for product_attributes record to get the value for.
     * @param int $value_id of product_values record containing the value to return.
     * @return string If <b>attribute_id</b> is for a type, <i>list</i>, product,
     *         return the value from the <i>products_values</i> record for the
     *         specified <b>value_id</b>. Otherwise return the <b>value_id</b> parameter.
     */
    public static function getValue($attribute_id, $value_id)
    {
        $row = ProductAttributes::getOne($attribute_id);

        if (!empty($row) && $row['type'] == 'list') {
            $row = self::getOne($value_id);
            if (!empty($row)) {
                return $row['value'];
            }
        }
        return $value_id;
    }

    /**
     * Check for duplicate record based on $attribute_id and $value.
     * @param int $attribute_id to test for.
     * @param string $value to test for.
     * @return bool true if the $value exists for the $attribute_id; false if not.
     */
    public static function isDuplicate($attribute_id, $value)
    {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->addSimpleWhere('attribute_id', $attribute_id, 'AND');
            $pdoDb->addSimpleWhere('value', $value);
            $rows = $pdoDb->request('SELECT', 'products_values');
        } catch (PdoDbException $pde) {
            error_log("ProductValues::isDuplicate() - attribute_id[$attribute_id] value[$value] - error: " . $pde->getMessage());
        }

        return (!empty($rows));
    }
    /**
     * @param int $id If not null, id of record to retrieve.
     * @return array Record retrieved.
     */
    private static function getProductValues($id = null) {
        global $LANG, $pdoDb;

        $product_values = array();
        try {
            if (isset($id)) {
                $pdoDb->addSimpleWhere('pv.id', $id);
            }

            $pdoDb->setOrderBy('name');

            $pdoDb->setSelectList(array(
                new DbField('pv.id', 'id'),
                new DbField('pa.name', 'name'),
                new DbField('pv.value', 'value'),
                new DbField('pv.enabled', 'enabled'),
                new DbField('pv.attribute_id', 'attribute_id')
            ));

            $ca = new CaseStmt("pv.enabled", "enabled_text");
            $ca->addWhen( "=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $jn = new Join('LEFT', 'products_attributes', 'pa');
            $jn->addSimpleItem('pa.id', new DbField('pv.attribute_id'));
            $pdoDb->addToJoins($jn);

            $rows = $pdoDb->request('SELECT', 'products_values', 'pv');
            foreach ($rows as $row) {
                $row['vname'] = $LANG['view'] . ' ' . $row['name'];
                $row['ename'] = $LANG['edit'] . ' ' . $row['name'];
                $row['image'] = ($row['enabled'] == ENABLED ? 'images/common/tick.png' : 'images/common/cross.png');
                $product_values[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("ProductValues::getProductValues() - Error: " . $pde->getMessage());
        }

        if (empty($product_values)) {
            return array();
        }
        return (isset($id) ? $product_values[0] : $product_values);
    }

    /**
     * Insert a new product_values record using values from the $_POST global.
     * Note: DO NOT include the 'id' field in the $_POST as it is auto assigned.
     * @return int 0 if insert failed, otherwise the 'id' assigned to the new row.
     */
    public static function insert() {
        global $pdoDb;

        $result = 0;
        try {
            $pdoDb->setExcludedFields('id');
            $result = $pdoDb->request('INSERT', 'products_values');
        } catch (PdoDbException $pde) {
            error_log('ProductValues::insert() - Error: ' . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Update the product_values record from values in $_POST global for
     * the "id" in the $_GET['id'] global.
     */
    public static function update() {
        global $pdoDb;

        $result = false;
        try {
            $pdoDb->addSimpleWhere('id', $_GET['id']);
            $pdoDb->setExcludedFields('id');
            $result = $pdoDb->request('UPDATE', 'products_values');
        } catch (PdoDbException $pde) {
            error_log('ProductValues::update() - Error: ' . $pde->getMessage());
        }
        return $result;
    }
}