<?php

use Inc\Claz\ProductAttributeType;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if (!empty($_POST['name']) || isset($_POST['cancel'])) {
	include("modules/product_attribute/save.php");
} else {
    $types = ProductAttributeType::getAll();

    $smarty->assign("types", $types);

    $pageActive = "product_attribute_add";
    $smarty->assign('pageActive', $pageActive);
    $smarty->assign('active_tab', '#product');
}
