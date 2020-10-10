<?php

use Inc\Claz\DomainId;
use Inc\Claz\Product;

global $smarty;

if (!empty($_POST['product_id'])) {
    include 'modules/inventory/save.php';
} else {
    // The products populate the dropdown list on the add screen.
    $smarty->assign('product_all', Product::getAll(true));
    $smarty->assign("domain_id", DomainId::get());

    $smarty->assign('pageActive', 'inventory');
    $smarty->assign('subPageActive', 'inventory_add');
    $smarty->assign('activeTab', '#product');
}
