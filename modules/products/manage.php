<?php

use Inc\Claz\CustomFlags;
use Inc\Claz\Product;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$smarty->assign("defaults", SystemDefaults::loadValues());

$products = Product::manageTableInfo();
$data = json_encode(['data' => mb_convert_encoding($products, 'UTF-8')]);

if (json_last_error() !== JSON_ERROR_NONE) {
    exit(json_last_error_msg());
}

if (file_put_contents("public/data.json", $data) === false) {
    exit("Unable to create public/data.json file");
}
$smarty->assign("numberOfRows",count($products));

$cflgs = CustomFlags::getCustomFlagsQualified('products', true);
$smarty->assign("cflgs", $cflgs);

$smarty->assign('pageActive', 'product');
$smarty->assign('activeTab', '#product');
