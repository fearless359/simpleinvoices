<?php

namespace Inc\Claz;

/**
 * @name ProductAttributeType.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181107
 */

/**
 * Class ProductAttributeType
 * @package Inc\Claz
 */
class ProductAttributeType
{
    /**
     * Get all product_attribute_type records.
     * @return array
     */
    public static function getAll(): array
    {
        global $pdoDb;

        $rows = [];
        try {
            $pdoDb->setSelectAll(true);
            $rows = $pdoDb->request('SELECT', 'products_attribute_type');
        } catch (PdoDbException $pde) {
            error_log("ProductAttributeType::getAll() - Error: " . $pde->getMessage());
        }
        return $rows;
    }
}
