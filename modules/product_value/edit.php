<?php

use Inc\Claz\ProductAttributes;
use Inc\Claz\ProductValues;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

//if valid then do save
if (!empty($_POST['value'])) {
    include "modules/product_value/save.php";
}

// @formatter:off
$productValue      = ProductValues::getOne($_GET['id']);
$productAttribute  = ProductAttributes::getOne($productValue['attribute_id']);

$smarty->assign("product_value"     , $productValue);
$smarty->assign("product_attribute" , $productAttribute['name']);
$smarty->assign("product_attributes", ProductAttributes::getAll());
// @formatter:on

$pageActive = "product_value_manage";
$smarty->assign('pageActive', $pageActive);
$smarty->assign('activeTab', '#product');

