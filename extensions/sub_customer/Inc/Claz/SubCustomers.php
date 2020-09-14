<?php

use Inc\Claz\Customer;
use Inc\Claz\PdoDbException;

/**
 * Class SubCustomers
 */
class SubCustomers {
    /**
     * Add extension database field if not present.
     * @return bool true if no DB error; otherwise false.
     */
    public static function addParentCustomerId() {
        global $pdoDb;

        if ($pdoDb->checkFieldExists("customers", "parent_customer_id")) {
            return true;
        }

        try {
            $pdoDb->addTableConstraints('parent_customer_id', 'ADD ~ INT(11) NULL AFTER `custom_field4`');
            if (!$pdoDb->request('ALTER TABLE', 'customers')) {
                throw new PdoDbException('Unable to add "parent_customer_id" column to customers.');
            }
        } catch (PdoDbException $pde) {
            error_log("SubCustomers::addParentCustomerId() - Error: " . $pde->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Get a <b>sub-customer</b> records associated with a specific <b>parent_customer_id</b>.
     * @param number $parent_id ID of parent to which sub-customers are associated. 
     * @return array <b>si_customer</b> records retrieved.
     */
    public static function getSubCustomers($parent_id) {
        global $pdoDb;

        $rows = [];
        try {
            // This is a bit of a trick. We are adding a selection for the parent_customer_id
            // field for all customers that have this parent. Then we call the Customer::getAll()
            // method which only add the final select for the domain_id. So in essence we will
            // use the standard access to perform our selection and return the standard record
            // structure with the parent_customer_id added to the select list.
            $pdoDb->addSimpleWhere("parent_customer_id", $parent_id, "AND");
            $rows = Customer::getAll();
        } catch (PdoDbException $pde) {
            error_log("SubCustomers::getSubCustomers() - Error: " . $pde->getMessage());
        }

        return $rows;
    }

    /**
     * JSON encoded echoed output for all customers with this parent ID.
     * @param $parent_id
     */
    public static function getSubCustomerAjax($parent_id) {
        $rows = self::getSubCustomers($parent_id);
        $output = "<option value=''></option>";
        foreach($rows as $row) {
            $output .= "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        echo json_encode($output);
        exit();
    }

}
