<?php

use Inc\Claz\ProductGroups;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $LANG, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$defaults = SystemDefaults::loadValues();
$smarty->assign("defaults", $defaults);

$productGroups = ProductGroups::manageTableInfo();

$data = json_encode(['data' => $productGroups]);
if (file_put_contents("public/data.json", $data) === false) {
    die("Unable to create public/data.json file");
}

$smarty->assign("numberOfRows",count($productGroups));

$smarty->assign('pageActive', 'productGroups');
$smarty->assign('activeTab', '#product');
