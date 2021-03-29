<?php

use Inc\Claz\ProductValues;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$productValues = ProductValues::getAll();
$smarty->assign('product_values', $productValues);
$smarty->assign("numberOfRows", count($productValues));

$smarty->assign('pageActive', "product_value_manage");
$smarty->assign('activeTab', '#product');
