<?php

use Inc\Claz\ProductAttributes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$productAttributes = ProductAttributes::getAll();
$smarty->assign('product_attributes', $productAttributes);
$smarty->assign("numberOfRows", count($productAttributes));

$smarty->assign('pageActive', "product_attribute_manage");
$smarty->assign('activeTab', '#product');
