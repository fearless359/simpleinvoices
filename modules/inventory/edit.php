<?php

use Inc\Claz\Inventory;
use Inc\Claz\Product;

global $smarty;

$smarty->assign('inventory', Inventory::getOne($_GET['id']));
$smarty->assign('product_all', Product::getAll(true));

$smarty->assign('pageActive', 'inventory');
$smarty->assign('subPageActive', 'inventoryEdit');
$smarty->assign('activeTab', '#product');
