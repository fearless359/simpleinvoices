<?php

use Inc\Claz\ProductAttributes;
use Inc\Claz\ProductValues;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

//if valid then do save
if (!empty($_POST['value'])) {
    include("modules/product_value/save.php");
}

#get the invoice id
$id = $_GET['id'];

// @formatter:off
$product_value      = ProductValues::getOne($id);
$product_attribute  = ProductAttributes::getOne($product_value['attribute_id']);
$product_attributes = ProductAttributes::getAll();

$smarty->assign("product_value"     , $product_value);
$smarty->assign("product_attribute" , $product_attribute['name']);
$smarty->assign("product_attributes", $product_attributes);
// @formatter:on

$pageActive = "product_value_manage";
$smarty->assign('pageActive', $pageActive);
$smarty->assign('active_tab', '#product');

