<?php

use Inc\Claz\CustomFields;
use Inc\Claz\CustomFlags;
use Inc\Claz\ProductAttributes;
use Inc\Claz\ProductGroups;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$customFieldLabel = CustomFields::getLabels(true);
$cflgs = CustomFlags::getCustomFlagsQualified('products', true);
$taxes = Taxes::getActiveTaxes();
// if valid then do save
if (!empty($_POST['description'])) {
    include "modules/products/save.php";
} else {
    $defaults = SystemDefaults::loadValues();
    $smarty->assign("defaults", $defaults);
    $smarty->assign('customFieldLabel', $customFieldLabel);
    $smarty->assign('cflgs', $cflgs);
    $smarty->assign('taxes', $taxes);

    $smarty->assign("attributes", ProductAttributes::getAll(true));

    $smarty->assign('productGroups', ProductGroups::getAll());

    $smarty->assign('pageActive', 'product_add');
    $smarty->assign('activeTab', '#product');
}
