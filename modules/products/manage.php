<?php

use Inc\Claz\CustomFlags;
use Inc\Claz\Product;
use Inc\Claz\SystemDefaults;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$count = Product::count();

$defaults = SystemDefaults::loadValues();

$cflgs = CustomFlags::getCustomFlagsQualified('E');
$smarty->assign("cflgs", $cflgs);
$smarty->assign("defaults",$defaults);
$smarty->assign("number_of_rows",$count);

$smarty->assign('pageActive', 'product_manage');
$smarty->assign('active_tab', '#product');
