<?php

use Inc\Claz\ProductAttributes;
use Inc\Claz\Log;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

//if valid then do save
if (!empty($_POST['value'])) {
    include "modules/product_value/save.php";
}

// Get attributes to display on add screen dropdown.
$smarty->assign("product_attributes", ProductAttributes::getAll());

$smarty->assign('pageActive', "product_value_add");
$smarty->assign('active_tab', '#product');

Log::out("pv add 99", Zend_Log::DEBUG);
