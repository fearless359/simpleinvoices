<?php

use Inc\Claz\DomainId;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;

global $pdoDb, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

//if valid then do save
if (!empty($_POST['value'])) {
	include("modules/product_value/save.php");
}

// Get attributes to display on add screen dropdown.
$product_attributes = array();
try {
    $pdoDb->setSelectAll(true);
    $product_attributes = $pdoDb->request('SELECT', 'products_attributes');
} catch (PdoDbException $pde) {
    error_log("modules/product_value/add.php - error: " . $pde->getMessage());
}

$smarty->assign("product_attributes", $product_attributes);
$smarty->assign('domain_id', DomainId::get());

$pageActive = "product_value_add";
$smarty->assign('pageActive', $pageActive);
$smarty->assign('active_tab', '#product');
