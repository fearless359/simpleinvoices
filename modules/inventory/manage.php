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

$inventories = Inventory::manageTableInfo();
$data = json_encode(['data' => $inventories]);
if (file_put_contents("public/data.json", $data) === false) {
    die("Unable to create public/data.json file");
}

$smarty->assign("numberOfRows",count($inventories));

$smarty->assign('pageActive', 'inventory');
$smarty->assign('activeTab', '#product');
