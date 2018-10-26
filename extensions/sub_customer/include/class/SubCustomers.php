<?php

class SubCustomers {
    /**
     * Add extension database field if not present.
     * @return boolean true if no DB error; otherwise false.
     */
    public static function addParentCustomerId() {
        global $pdoDb;

        if ($pdoDb->checkFieldExists("customers", "parent_customer_id")) return true;

        try {
            $sql = "ALTER TABLE `" . TB_PREFIX . "customers` ADD `parent_customer_id` INT(11) NULL AFTER `custom_field4`;";
            $pdoDb->query($sql);
        } catch (Exception $e) {
            error_log("SubCustomers.php - addParentCustomerId(): " .
                      "Unable to perform request: sql[$sql]. " . print_r($e->getMessage(),true));
            return false;
        }
        return true;
    }

    /**
     * Add a new <b>si_customers</b> record.
     * @return int ID for new record. 0 if insert failed.
     * @throws PdoDbException
     */
    public static function insertCustomer() {
        global $config, $pdoDb;

        $pdoDb->addSimpleWhere("name", $_POST['name'], "AND");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $rows = $pdoDb->request("SELECT", "customers");
        if (!empty($rows)) {
            error_log("The specified customer name[{$_POST['name']}) already exists.");
            return false;
        }

        try {
            $excludeFields = array("id");
            if (empty($_POST['credit_card_number'])) {
                $excludeFields[] = 'credit_card_number';
            } else {
                $key = $config->encryption->default->key;
                $enc = new Encryption();
                $_POST['credit_card_number'] = $enc->encrypt($key, $_POST['credit_card_number']);
            }

            $pdoDb->setExcludedFields($excludeFields);
            $pdoDb->request('INSERT', 'customers');
        } catch (Exception $e) {
            error_log("SubCustomers::insertCustomer(): Unable to add the new customer record. Error: " . $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Update an existing <b>si_customers</b> record.
     * @return boolean <b>true</b> if update is successful; otherwise <b>false</b>.
     */
    public static function updateCustomer() {
        global $config, $pdoDb;

        try {
            $excludedFields = array('id', 'domain_id');
            if (empty($_POST['credit_card_number'])) {
                $excludedFields[] = 'credit_card_number';
            } else {
                $key = $config->encryption->default->key;
                $enc = new Encryption();
                $_POST['credit_card_number'] = $enc->encrypt($key, $_POST['credit_card_number']);
            }

            $pdoDb->setExcludedFields($excludedFields);
            $pdoDb->addSimpleWhere("id", $_GET['id']);
            $pdoDb->request('UPDATE', 'customers');
        } catch (Exception $e) {
            error_log("SubCustomers::updateCustomer(): Unable to update the customer record. Error: " . $e->getMessage());
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
        try {
            $pdoDb->addSimpleWhere("parent_customer_id", $parent_id, "AND");
            $pdoDb->addSimpleWhere("domain_id", domain_id::get());
            $rows = $pdoDb->request("SELECT", "customers");
        } catch (PdoDbException $pde) {
            $str = "SubCustomers::getSubCustomers(): " . $pde->getMessage();
            error_log($str);
            return array();
        }
        return $rows;
    }
}
