<?php

use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// if valid then do save
if (!empty($_POST['name'])) {
    include "modules/product_groups/save.php";
} else {
    $defaults = SystemDefaults::loadValues();
    $smarty->assign("defaults", $defaults);

    $smarty->assign('pageActive', 'productGroups');
    $smarty->assign('subPageActive', 'productGroupsCreate');
    $smarty->assign('activeTab', '#product');
}
