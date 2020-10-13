<?php

use Inc\Claz\ProductGroups;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $LANG, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$defaults = SystemDefaults::loadValues();
$smarty->assign("defaults", $defaults);

$productGroups = [];
$rows = ProductGroups::getAll();
foreach ($rows as $row) {
    $row['vname'] = $LANG['view'] . ' ' . Util::htmlSafe($row['name']);
    $row['ename'] = $LANG['edit'] . ' ' . Util::htmlSafe($row['name']);
    $row['image'] = $row['enabled'] == ENABLED ? 'images/tick.png' : 'images/cross.png';
    $productGroups[] = $row;
}

$smarty->assign('productGroups', $productGroups);
$smarty->assign("numberOfRows",count($productGroups));

$smarty->assign('pageActive', 'product_groups_manage');
$smarty->assign('activeTab', '#product');
