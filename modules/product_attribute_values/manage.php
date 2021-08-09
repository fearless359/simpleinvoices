<?php

use Inc\Claz\ProductAttributeValues;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$productAttributeValues = ProductAttributeValues::manageTableInfo();

$data = json_encode(['data' => $productAttributeValues]);
if (file_put_contents("public/data.json", $data) === false) {
    exit("Unable to create public/data.json file");
}

$smarty->assign("numberOfRows", count($productAttributeValues));

$smarty->assign('pageActive', "productAttributeValues");
$smarty->assign('activeTab', '#product');
