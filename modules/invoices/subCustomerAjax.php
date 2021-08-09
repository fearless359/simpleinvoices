<?php

use Inc\Claz\DomainId;
use Inc\Claz\PdoDbException;

/**
 * Make the option section of a select statement for sub-customers.
 * @param string $parentCustomerId
 */
function getSubCustomer(string $parentCustomerId='') {
    global $pdoDb;

    session_name('SiAuth');
    session_start();
    try {
        $pdoDb->addSimpleWhere('parent_customer_id', $parentCustomerId, 'AND');
        $pdoDb->addSimpleWhere('domain_id', DomainId::get());

        $rows = $pdoDb->request('SELECT', 'customers');
        if (empty($rows)) {
            exit(1);
        }
    } catch (PdoDbException $pde) {
        error_log("modules/invoices/subCustomers.php pdoDb->request exception: {$pde->getMessage()}");
        exit("Unable to process request. See error log for details.");
    }

    $output = "<option value=''></option>";
    foreach ($rows as $row) {
        $name = htmlspecialchars($row['name'], ENT_QUOTES);
        $output .= "<option value='" . $row['id'] . "'>" . $name . "</option>";
    }
    echo json_encode($output);
    exit();
}

getSubCustomer($_GET['id']);
