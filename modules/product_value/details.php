<?php

use Inc\Claz\ProductAttributes;
use Inc\Claz\ProductValues;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if (!empty($_POST['value'])) {
	include("modules/product_value/save.php");
}

#get the invoice id
$id = $_GET['id'];

$product_value = ProductValues::get($id);

$product_attribute = ProductAttributes::get($product_value['attribute_id']);

$smarty->assign("product_value", $product_value);
$smarty->assign("product_attribute", $product_attribute['name']);

$pageActive = "product_value_manage";
$smarty->assign('pageActive', $pageActive);
$smarty->assign('active_tab', '#product');

$sql_attr = "select * from ".TB_PREFIX."products_attributes";
$sth_attr =  dbquery($sql_attr);
$product_attributes = $sth_attr->fetchall();
$smarty->assign("product_attributes", $product_attributes);
