<?php
namespace Inc\Claz;

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
}