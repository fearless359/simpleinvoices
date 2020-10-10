<?php

use Inc\Claz\ProductAttributes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

//if valid then do save
if (!empty($_POST['value'])) {
    include "modules/product_value/save.php";
} else {
// Get attributes to display on add screen dropdown.
    $smarty->assign("product_attributes", ProductAttributes::getAll());

    $smarty->assign('pageActive', "product_value_add");
    $smarty->assign('activeTab', '#product');
}
