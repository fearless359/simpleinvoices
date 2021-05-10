<?php

use Inc\Claz\ProductValues;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$productValues = productValues::manageTableInfo();

$data = json_encode(['data' => $productValues]);
if (file_put_contents("public/data.json", $data) === false) {
    die("Unable to create public/data.json file");
}

$smarty->assign("numberOfRows", count($productValues));

$smarty->assign('pageActive', "productValue");
$smarty->assign('activeTab', '#product');
