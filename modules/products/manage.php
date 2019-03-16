<?php

use Inc\Claz\CustomFlags;
use Inc\Claz\Product;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$products = Product::getAll();
$smarty->assign('products', $products);
$smarty->assign("number_of_rows",count($products));

$cflgs = CustomFlags::getCustomFlagsQualified('products', true);
$smarty->assign("cflgs", $cflgs);

$defaults = SystemDefaults::loadValues();
$smarty->assign("defaults",$defaults);

$smarty->assign('pageActive', 'product_manage');
$smarty->assign('active_tab', '#product');
