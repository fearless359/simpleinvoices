<?php

use Inc\Claz\DomainId;
use Inc\Claz\Inventory;
use Inc\Claz\Product;

global $smarty;

if (!empty($_POST['op']) && $_POST['op'] =='edit' && !empty($_POST['product_id'])) {
    $saved = (Inventory::update() ? "true" : "false");
    $smarty->assign('saved', $saved);
}

$inventory = Inventory::select();
$product_all = Product::getAll();

$smarty->assign('product_all'  , $product_all);
$smarty->assign('inventory'    , $inventory);
$smarty->assign("domain_id"    , DomainId::get());

$smarty->assign('pageActive'   , 'inventory');

$smarty->assign('subPageActive', 'inventory_edit');
$smarty->assign('active_tab'   , '#product');
