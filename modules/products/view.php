<?php

use Inc\Claz\CustomFields;
use Inc\Claz\CustomFlags;
use Inc\Claz\Product;
use Inc\Claz\ProductAttributes;
use Inc\Claz\ProductGroups;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$product = Product::getOne($_GET['id']);
$product['attribute_decode'] = json_decode($product['attribute'],true);

// @formatter:off
$smarty->assign('product'         , $product);
$smarty->assign('taxes'           , Taxes::getActiveTaxes());

$smarty->assign('tax_selected'    , empty($product['default_tax_id']) ? "" :Taxes::getOne($product['default_tax_id']));
$smarty->assign('customFieldLabel', CustomFields::getLabels(true));
$smarty->assign('cflgs'           , CustomFlags::getCustomFlagsQualified('products', true));
$smarty->assign("attributes"      , ProductAttributes::getAll());
$smarty->assign( "productGroup"   , ProductGroups::getOne($product['product_group']));
$smarty->assign("defaults"        , SystemDefaults::loadValues());

$smarty->assign('pageActive'      , 'product');
$smarty->assign('subPageActive'   , "productView");
$smarty->assign('activeTab'       , '#product');
// @formatter:on
