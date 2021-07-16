<?php

use Inc\Claz\DomainId;
use Inc\Claz\Log;

/**
 * Make the option section of a select statement for sub-customers.
 * @param string $parentCustomerId
 * @throws \Inc\Claz\PdoDbException
 */
function getSubCustomer(string $parentCustomerId='') {
    global $pdoDb;

    session_name('SiAuth');
    session_start();
    
    $pdoDb->addSimpleWhere('parent_customer_id', $parentCustomerId, 'AND');
    $pdoDb->addSimpleWhere('domain_id', DomainId::get());

    $rows = $pdoDb->request('SELECT', 'customers');
    if (empty($rows)) {
        exit(1);
    }

    $output = "<option value=''></option>";
    foreach ($rows as $row) {
        $name = htmlspecialchars($row['name'], ENT_QUOTES);
        $output .= "<option value='" . $row['id'] . "'>" . $name . "</option>";
    }
Log::out("subCustomerAjax() - output[$output]");
    echo json_encode($output);
    exit();
}

getSubCustomer($_GET['id']);
