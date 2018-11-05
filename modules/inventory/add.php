<?php

use Inc\Claz\DomainId;
use Inc\Claz\Inventory;
use Inc\Claz\Product;

global $smarty;

if (!empty($_POST['op']) && $_POST['op'] =='add' && !empty($_POST['product_id'])) {
    try {
        $saved = "false";
        if (Inventory::insert() > 0) $saved = "true";
    } catch (PDOException $pde) {
        error_log("add.php insert error: " . $pde->getMessage());
    }
    $smarty->assign('saved', $saved);
}

$product_all = Product::select_all();

$smarty->assign('product_all', $product_all);
$smarty->assign("domain_id"  , DomainId::get());

$smarty->assign('pageActive'   , 'inventory');
$smarty->assign('subPageActive', 'inventory_add');
$smarty->assign('active_tab'   , '#product');
