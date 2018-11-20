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
     * Get a specific products_attributes record plus the associated
     * product_attributes_type name field (assigned field name of "type").
     * @param string $id ID of record to retrieve.
     * @return array Associative array for record retrieved.
     */
    public static function get($id) {
        global $LANG, $pdoDb;

        $row = array();
        try {
            $pdoDb->setSelectList(array(
                new DbField('pa.id', 'id'),
                new DbField('pa.name', 'name'),
                new DbField('pa.type_id', 'type_id'),
                new DbField('pa.enabled', 'enabled'),
                new DbField('pa.visible', 'visible'),
                new DbField('pat.name', 'type')
            ));

            $pdoDb->addSimpleWhere("pa.id", $id);

            $oc = new OnClause(new OnItem(false, "type_id", "=", new DbField("pat.id"), false));
            $pdoDb->addToJoins(array("LEFT", "products_attribute_type", "pat", $oc));

            $ca = new CaseStmt("visible", "wording_for_visible");
            $ca->addWhen( "=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $ca = new CaseStmt("enabled", "wording_for_enabled");
            $ca->addWhen( "=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $rows = $pdoDb->request("SELECT", "products_attributes", 'pa');
            if (!empty($rows)) {
                $row = $rows[0];
            }
        } catch (PdoDbException $pde) {
            error_log("ProductAttributes::get() - id[$id] error: " . $pde->getMessage());
        }

        return $row;
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
     * @param bool $enabled set to true to select only enabled records. Otherwise
     *          set to false to select all records. Note the if not specified,
     *          false is assumed.
     * @return array Rows from table.
     */
    public static function getAll($enabled=false) {
        global $pdoDb;
        $rows = array();
        try {
            if ($enabled) {
                $pdoDb->addSimpleWhere('enabled', ENABLED);
            }
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

    /**
     * @param string $type 'count' if count of qualified rows requests. All other settings
     *          cause array of qualified rows is returned.
     * @param string $dir
     * @param string $sort
     * @param int $rp limit of rows to return if not type 'count'.
     * @param int $page of rows to return if not type 'count'.
     * @return array|int if $type is 'count', count of qualified rows returned, otherwise
     *          array of qualified rows returned.
     */
    public static function xmlSql($type, $dir, $sort, $rp, $page) {
        /**
         * @var PdoDb $pdoDb
         */
        global $pdoDb;

        $count_type = ($type == 'count');

        if (!$count_type) {
            if (intval($page) != $page) {
                $page = 1;
            }

            if (intval($rp) != $rp) {
                $rp = 25;
            }

            $start = (($page - 1) * $rp);
            $pdoDb->setLimit($rp, $start);
        }

        $rows = array();
        try {
            $query = isset($_POST['query']) ? $_POST['query'] : null;
            $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
            if (!empty($qtype) && !empty($query)) {
                if (in_array($qtype, array('id', 'name'))) {
                    $pdoDb->addToWhere(new WhereItem(false, $qtype, 'LIKE', $query, false));
                }
            }

            if (!preg_match('/^(asc|desc)$/iD', $dir)) {
                $dir = 'DESC';
            }

            if (!in_array($sort, array('id', 'name', 'enabled', 'visible'))) {
                $sort = "id";
            }

            $pdoDb->setOrderBy(array($sort, $dir));

            $pdoDb->setSelectList(array('id', 'name', 'enabled', 'visible'));

            $rows = $pdoDb->request('SELECT', 'products_attributes');
        } catch (PdoDbException $pde) {
            error_log('ProductAtributes::xmlSql() - Error: ' . $pde->getMessage());
        }
        if ($count_type) {
            return count($rows);
        }
        return $rows;

//        $sql = "SELECT id, name, enabled, visible FROM " . TB_PREFIX . "products_attributes
//                WHERE 1 $where
//                ORDER BY $sort $dir
//                LIMIT $start, $limit";
//        if (empty($query)) {
//            $sth = dbQuery($sql);
//        } else {
//            $sth = dbQuery($sql, ':query', "%$query%");
//        }
//        $customers = $sth->fetchAll(PDO::FETCH_ASSOC);
    }

}
