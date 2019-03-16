<?php

use Inc\Claz\Inventory;
use Inc\Claz\Util;

/*
 *  Script: manage.php
 *      Manage Invoices page
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$inventories = Inventory::getAll();
$smarty->assign('inventories', $inventories);
$smarty->assign("number_of_rows",count($inventories));

$smarty->assign('pageActive', 'inventory');
$smarty->assign('active_tab', '#product');
