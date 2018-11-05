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
     * Get <i>products_attributes</i> information for a specified <b>id</b>.
     * @param string $id ID of record to retrieve.
     * @return array Associative array for record retrieved.
     */
    public static function get($id) {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->setSelectList(array("pa.*", "pat.name AS type"));
            $pdoDb->addSimpleWhere("pa.id", $id);
            $oc = new OnClause(new OnItem(false, "pa.type_id", "=", new DbField("pat.id"), false));
            $pdoDb->addToJoins(array("LEFT", "products_attribute_type", "pat", $oc));
            $rows = $pdoDb->request("SELECT", "products_attributes", "pa");
        } catch (PdoDbException $pde) {
            error_log("ProductAttributes::get() - id[$id] error: " . $pde->getMessage());
        }
        return $rows;
    }

    /**
     * Get the <i>product_attribute</i> name for the specified <b>id</b>.
     * @param string $id ID for the record to access.
     * @return string <b>name</b> setting for the specified record.
     */
    public static function getName($id) {
        global $pdoDb;

        $name = '';
        try {
            $pdoDb->addSimpleWhere("id", $id);
            $pdoDb->setSelectList("name");
            $attribute = $pdoDb->request("SELECT","products_attributes");
            $name = (empty($attribute['name']) ? '' : $attribute['name']);
        } catch (PdoDbException $pde) {
            error_log("ProductAttributes::get() - id[$id] error: " . $pde->getMessage());
        }
        return $name;
    }

    /**
     * Get the <i>product_attribute</i> type for the specified <b>id</b>.
     * @param string $id ID for record to access.
     * @return string <b>type</b> setting for the specified record.
     */
    public static function getType($id) {
        global $pdoDb;

        $type = '';
        try {
            $pdoDb->addSimpleWhere("id", $id);
            $pdoDb->setSelectList("type");
            $attribute = $pdoDb->request("SELECT", "products_attributes");
            $type = (empty($attribute['type']) ? '': $attribute['type']);
        } catch (PdoDbException $pde) {
            error_log("ProductAttributes::getType() - id[$id] error: " . $pde->getMessage());
        }
        return $type;
    }

    /**
     * Get the value for a specified product attribute and value ID.
     * @param string $attribute_id Product attribute.
     * @param string $value_id Product value ID.
     * @return string If <b>attribute_id</b> is for a type, <i>list</i>, product,
     *         return the value from the <i>products_values</i> record for the
     *         specified <b>value_id</b>. Otherwise return the <b>value_id</b> parameter.
     */
    public static function getValue($attribute_id, $value_id) {
        global $pdoDb;
        $type = self::getType($attribute_id);
        if ($type == 'list') {
            try {
                $pdoDb->addSimpleWhere("id", $value_id);
                $pdoDb->setSelectList("value");
                $attribute = $pdoDb->request("SELECT", "products_values");
                return $attribute['value'];
            } catch (PdoDbException $pde) {
                error_log("ProductAttributes::getValue() - attribute_id[$attribute_id] value_id[$value_id] error: " . $pde->getMessage());
            }
        }
        return $value_id;
    }

    /**
     * Determine if a <b>product_attribute</b> is flagged as visible.
     * @param string $id ID of record to check.
     * @return boolean <b>true</b> if record is visible; <b>false</b> if not.
     */
    public static function getVisible($id) {
        global $pdoDb;

        try {
            $pdoDb->addSimpleWhere("id", $id);
            $pdoDb->setSelectList("visible");
            $attribute = $pdoDb->request("SELECT", "products_attributes");
            return ($attribute['visible'] == ENABLED);
        } catch (PdoDbException $pde) {
            error_log("ProductAttributes::getVisible() - id[$id] error: " . $pde->getMessage());
        }
        return false;
    }

    /**
     * Get all <b>products_attributes</b> records
     * @return array Rows from table.
     */
    public static function getAll() {
        global $pdoDb;
        $rows = array();
        try {
            $rows = $pdoDb->request("SELECT", "products_attributes");
        } catch (PdoDbException $pde) {
            error_log("ProductAttributes::getAll() - Error: " . $pde->getMessage());
        }
        return $rows;
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
}
