<?php

use Inc\Claz\Inventory;
use Inc\Claz\Product;

global $smarty;

$inventory = Inventory::getOne($_GET['id']);
$product_all = Product::getAll(true);

$smarty->assign('inventory', $inventory);
$smarty->assign('product_all', $product_all);

$smarty->assign('pageActive', 'inventory');
$smarty->assign('subPageActive', 'inventory_edit');
$smarty->assign('active_tab', '#product');
