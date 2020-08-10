<?php
namespace Inc\Claz;

/**
 *  ProductAttributes class.
 *  @author Richard Rowley
 *
 *  Last modified:
 *      2016-08-15
 */
class ProductAttributes {

    /**
     * Retrieve a specific products_attributes record and associated product_attributes_type
     * information.
     * @param int $id of record to retrieve.
     * @return array Associative array for record retrieved. Test for empty for no matching record.
     */
    public static function getOne($id) {
        return self::getProductAttributes($id);
    }

    /**
     * Retrieve all <b>products_attributes</b> records
     * @param bool $enabled Set to true to retrieve enabled records only.
     *          Set to false (default) to retureve all records.
     * @return array Rows from table. Test for empty result for nothing found.
     */
    public static function getAll($enabled=false) {
        return self::getProductAttributes(null, $enabled);
    }

    /**
     * Retrieve product_attributes record(s) and associated information.
     * @param int $id ID of record to retrieve.
     * @param bool $enabled true if only enabled records are to be retrieved;
     *              false if all records are to be retrieved.
     * @return array Records retrieved. Test empty result for no records found.
     */
    private static function getProductAttributes($id = null, $enabled = false)
    {
        global $LANG, $pdoDb;

        $product_attributes = array();
        try {
            $pdoDb->setSelectList(array(
                'pa.id',
                'pa.name',
                'pa.type_id',
                'pa.enabled',
                'pa.visible',
                new DbField('pat.name', 'type_name')
            ));

            $connector = (isset($id) && $enabled ? 'AND' : '');
            if ($enabled) {
                $pdoDb->addSimpleWhere('enabled', ENABLED, $connector);
            }

            if (isset($id)) {
                $pdoDb->addSimpleWhere("pa.id", $id);
            }

            $jn = new Join('LEFT', 'products_attribute_type', 'pat');
            $jn->addSimpleItem('type_id', new DbField("pat.id"));
            $pdoDb->addToJoins($jn);

            $ca = new CaseStmt("visible", "visible_text");
            $ca->addWhen( "=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $ca = new CaseStmt("enabled", "enabled_text");
            $ca->addWhen( "=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $rows = $pdoDb->request("SELECT", "products_attributes", 'pa');
            foreach ($rows as $row) {
                $row['vname'] = $LANG['view'] . ' ' . $row['name'];
                $row['ename'] = $LANG['edit'] . ' ' . $row['name'];
                $row['enabled_image'] = ($row['enabled'] == ENABLED ? 'images/tick.png' : 'images/cross.png');
                $row['visible_image'] = ($row['visible'] == ENABLED ? 'images/tick.png' : 'images/cross.png');
                $product_attributes[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("ProductAttributes::getOne() - id[$id] error: " . $pde->getMessage());
        }

        if (empty($product_attributes)) {
            return array();
        }
        return (isset($id) ? $product_attributes[0] : $product_attributes);
    }

    /**
     * Get matrix of product attributes.
     * @return array Matrix values array of <i>product_attributes</i> with associated <i>products_values</i>.
     */
    public static function getMatrix() {
        global $pdoDb;

        $rows = array();
        try {
            $fn = new FunctionStmt(null, "CONCAT(a.id, '-', v.id)", "id");
            $pdoDb->addToFunctions($fn);

            $fn = new FunctionStmt(null, "CONCAT(a.name, '-', v.value)", "display");
            $pdoDb->addToFunctions($fn);

            $jn = new Join("LEFT", "products_values", "v");
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
    public static function insert() {
        global $pdoDb;

        $result = 0;
        try {
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
    public static function update() {
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
