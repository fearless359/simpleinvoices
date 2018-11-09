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
            $pdoDb->addToFunctions("COUNT(*) AS count");
            $rows = $pdoDb->request("SELECT", "products_values");
        } catch (PdoDbException $pde) {
            error_log("ProductValues::count() - Error: " . $pde->getMessage());
        }
        return (empty($rows) ? 0 : $rows[0]['count']);
    }

    /**
     * Get a specific record.
     * @param $id
     * @return array for selected row.
     */
    public static function get($id)
    {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->setSelectAll(true);
            $pdoDb->addSimpleWhere('id', $id);
            $rows = $pdoDb->request('SELECT', 'products_values');
        } catch (PdoDbException $pde) {
            error_log("ProductValues::get() - id[$id] - error: " . $pde->getMessage());
        }
        return (empty($rows) ? $rows : $rows[0]);
    }

    /**
     * Get the value for a specified product attribute and value ID.
     *
     * @param string $attribute_id Product attribute.
     * @param string $value_id Product value ID.
     * @return string If <b>attribute_id</b> is for a type, <i>list</i>, product,
     *         return the value from the <i>products_values</i> record for the
     *         specified <b>value_id</b>. Otherwise return the <b>value_id</b> parameter.
     */
    public static function getValue($attribute_id, $value_id)
    {
        global $pdoDb;
        $type = ProductAttributes::getType($attribute_id);
        if ($type == 'list') {
            try {
                $pdoDb->addSimpleWhere("id", $value_id);
                $pdoDb->setSelectList("value");
                $attribute = $pdoDb->request("SELECT", "products_values");
                return $attribute['value'];
            } catch (PdoDbException $pde) {
                error_log("ProductValues::getValue() - attribute_id[$attribute_id] value_id[$value_id] error: " . $pde->getMessage());
            }
        }
        return $value_id;
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
            $result = $pdoDb->request('UPDATE', 'products_values');
        } catch (PdoDbException $pde) {
            error_log('ProductValues::update() - Error: ' . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Select records to display on the flexgrid list.
     * @param string $dir
     * @param string $sort
     * @param int $rp
     * @param int $page
     * @return array
     */
    public static function xmlSql($dir, $sort, $rp, $page) {
        global $pdoDb;

        $rows = array();
        try {
            if (intval($page) != $page) {
                $page = 1;
            }

            if (intval($rp) != $rp) {
                $rp = 25;
            }

            $start = (($page - 1) * $rp);

            $pdoDb->setLimit($rp, $start);

            $query = isset($_POST['query']) ? $_POST['query'] : null;
            $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
            if (!empty($qtype) && !empty($query)) {
                if (in_array($qtype, array('name', 'value'))) {
                    $pdoDb->addToWhere(new WhereItem(false, $qtype, 'LIKE', $query, false));
                }
            }

            if (!preg_match('/^(asc|desc)$/iD', $dir)) {
                $dir = 'ASC';
            }

            if (!in_array($sort, array('id', 'name', 'value', 'enabled'))) {
                $sort = "name";
            }

            $pdoDb->setOrderBy(array($sort, $dir));

            $pdoDb->setSelectList(array(
                new DbField('pv.id', 'id'),
                new DbField('pa.name', 'name'),
                new DbField('pv.value', 'value'),
                new DbField('pv.enabled', 'enabled')
            ));

            $jn = new Join('LEFT', 'products_attributes', 'pa');
            $jn->addSimpleItem('pa.id', new DbField('pv.attribute_id'));
            $pdoDb->addToJoins($jn);

            $rows = $pdoDb->request('SELECT', 'products_values', 'pv');
        } catch (PdoDbException $pde) {
            error_log("ProductValues::xmlSql() - Error: " . $pde->getMessage());
        }
        return $rows;
    }
}