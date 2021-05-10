<?php

use Inc\Claz\CustomFlags;
use Inc\Claz\Product;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$defaults = SystemDefaults::loadValues();
$smarty->assign("defaults", $defaults);

$products = Product::manageTableInfo();
$data = json_encode(['data' => $products]);
if (file_put_contents("public/data.json", $data) === false) {
    die("Unable to create public/data.json file");
}
$smarty->assign("numberOfRows",count($products));

$cflgs = CustomFlags::getCustomFlagsQualified('products', true);
$smarty->assign("cflgs", $cflgs);

$smarty->assign('pageActive', 'product');
$smarty->assign('activeTab', '#product');
