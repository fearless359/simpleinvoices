<?php

use Inc\Claz\ProductAttributeType;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

//if valid then do save
if (!empty($_POST['name'])) {
    include "modules/product_attribute/save.php";
} else {
    $smarty->assign("types", ProductAttributeType::getAll());

    $smarty->assign('pageActive', 'productAttribute');
    $smarty->assign('subPageActive', 'productAttributeCreate');
    $smarty->assign('activeTab', '#product');
}
