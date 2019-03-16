<?php

use Inc\Claz\ProductValues;
use Inc\Claz\Util;

global $smarty;
//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$product_values = ProductValues::getAll();
$smarty->assign('product_values', $product_values);
$smarty->assign("number_of_rows", count($product_values));

$smarty->assign('pageActive', "product_value_manage");
$smarty -> assign('active_tab', '#product');
