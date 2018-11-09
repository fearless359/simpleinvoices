<?php

use Inc\Claz\DomainId;
use Inc\Claz\PdoDbException;
use Inc\Claz\SiLocal;

global $output, $pdoDb;

if($_GET['id']) {
    $rows = array();
    try {
        $pdoDb->addSimpleWhere("id", $_GET['id'], "AND");
        $pdoDb->addSimpleWhere("domain_id", DomainId::get());
        $pdoDb->setLimit(1);
        $pdoDb->setSelectList("cost");
        $rows = $pdoDb->request("SELECT", "products");
    } catch (PdoDbException $pde) {
        error_log("modules/invoices/product_inventory_ajaz.php - error: " . $pde->getMessage());
    }
    if (!empty($rows)) {
        $row = $rows[0];
        // Format with decimal places with precision for user's locale
        $output['cost'] = SiLocal::number($row['cost']);
    } else {
        $output .= '';
    }

    echo json_encode($output);
    exit();
}

echo "";
