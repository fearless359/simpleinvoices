<?php

use Inc\Claz\ProductAttributes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$productAttributes = ProductAttributes::manageTableInfo();

$data = json_encode(['data' => mb_convert_encoding($productAttributes, 'UTF-8')]);
if (file_put_contents("public/data.json", $data) === false) {
    exit("Unable to create public/data.json file");
}

$smarty->assign("numberOfRows", count($productAttributes));

$smarty->assign('pageActive', "productAttribute");
$smarty->assign('activeTab', '#product');
