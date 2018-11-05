<?php

use Inc\Claz\CustomFlags;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;

global $smarty, $pdoDb;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$customFieldLabel = getCustomFieldLabels('',true);
$cflgs = CustomFlags::getCustomFlagsQualified('E');
$taxes = Taxes::getActiveTaxes();
// if valid then do save
if (!empty($_POST['description'])) {
    include("modules/products/save.php");
} else {
    $defaults = SystemDefaults::loadValues();
    $smarty->assign("defaults", $defaults);
    $smarty->assign('customFieldLabel', $customFieldLabel);
    $smarty->assign('cflgs', $cflgs);
    $smarty->assign('taxes', $taxes);

    $pdoDb->addSimpleWhere("enabled", ENABLED);
    $attributes = $pdoDb->request("SELECT", "products_attributes");

    $smarty->assign("attributes", $attributes);

    $smarty->assign('pageActive', 'product_add');
    $smarty->assign('active_tab', '#product');
}