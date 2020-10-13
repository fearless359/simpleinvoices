<?php

use Inc\Claz\ProductGroups;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$smarty->assign('productGroup', ProductGroups::getOne($_GET['name']));

$smarty->assign('pageActive'      , 'product_group_manage');
$smarty->assign('subPageActive'   , "product_group_edit");
$smarty->assign('activeTab'       , '#product');
// formatter:on
