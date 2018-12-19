<?php

use Inc\Claz\ProductAttributes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

$product_attributes = ProductAttributes::getAll();
$smarty->assign('product_attributes', $product_attributes);
$smarty->assign("number_of_rows", count($product_attributes));

$smarty->assign('pageActive', "product_attribute_manage");
$smarty->assign('active_tab', '#product');
