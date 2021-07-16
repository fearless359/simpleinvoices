<?php

use Inc\Claz\ProductAttributes;
use Inc\Claz\ProductAttributeValues;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

//if valid then do save
if (!empty($_POST['value'])) {
    include "modules/product_attribute_values/save.php";
}

$productAttributeValue = ProductAttributeValues::getOne($_GET['id']);
$productAttribute = ProductAttributes::getOne($productAttributeValue['attribute_id']);

$smarty->assign("product_attribute_values", $productAttributeValue);
$smarty->assign("product_attribute" , $productAttribute['name']);
$smarty->assign("product_attributes", ProductAttributes::getAll());

$smarty->assign('pageActive', 'productAttributeValues');
$smarty->assign('subPageActive'   , 'productAttributeValuesEdit');
$smarty->assign('activeTab', '#product');

