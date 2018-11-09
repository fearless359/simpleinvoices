<?php

use Inc\Claz\ProductAttributes;
use Inc\Claz\ProductAttributeType;

global $smarty, $LANG;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if (!empty($_POST['name'])) {
    include("modules/product_attribute/save.php");
}

$id = $_GET['id'];

$attribute = ProductAttributes::get($id);
$types = ProductAttributeType::getAll();

$smarty->assign('product_attribute',$attribute);
$smarty -> assign("types", $types);

$pageActive = "product_attribute_manage";
$smarty->assign('pageActive', $pageActive);
$smarty->assign('active_tab', '#product');

