<?php

use Inc\Claz\DomainId;
use Inc\Claz\Product;

global $smarty;

if (!empty($_POST['op']) && $_POST['op'] == 'add' && !empty($_POST['product_id'])) {
    include 'modules/inventory/save.php';
} else {
    // The products populate the dropdown list on the add screen.
    $product_all = Product::getAll(true);
    $smarty->assign('product_all', $product_all);
    $smarty->assign("domain_id", DomainId::get());

    $smarty->assign('pageActive', 'inventory');
    $smarty->assign('subPageActive', 'inventory_add');
    $smarty->assign('active_tab', '#product');
}
