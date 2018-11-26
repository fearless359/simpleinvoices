<?php

use Inc\Claz\CustomFields;
use Inc\Claz\CustomFlags;
use Inc\Claz\Extensions;
use Inc\Claz\Product;
use Inc\Claz\ProductAttributes;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

$product_id = $_GET['id'];

$product = Product::select($product_id);

$customFieldLabel = CustomFields::getLabels(true);
$cflgs = CustomFlags::getCustomFlagsQualified('E');

$taxes = Taxes::getActiveTaxes();
$tax_selected = Taxes::getTaxRate($product['default_tax_id']);

$defaults = SystemDefaults::loadValues();
$smarty->assign("defaults"        , $defaults);
$product['attribute_decode'] = json_decode($product['attribute'],true);
$smarty->assign('product'         , $product);
$smarty->assign('taxes'           , $taxes);
$smarty->assign('tax_selected'    , $tax_selected);
$smarty->assign('customFieldLabel', $customFieldLabel);
$smarty->assign('cflgs', $cflgs);

$attributes = ProductAttributes::getAll();

$smarty->assign("attributes"      , $attributes);
$smarty->assign('pageActive'      , 'product_manage');
$subPageActive = $_GET['action'] == "view" ? "product_view" : "product_edit";
$smarty->assign('subPageActive'   , $subPageActive);
$smarty->assign('active_tab'      , '#product');
