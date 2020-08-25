<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\Preferences;
use Inc\Claz\Product;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// @formatter:off
$billers   = Biller::getAll();
$customers = Customer::getAll();
$products  = Product::getAll(true);

if (empty($billers) || empty($customers) || empty($products)) {
    $firstRunWizard =true;
} else {
    $firstRunWizard = false;
}
$smarty->assign("first_run_wizard",$firstRunWizard);

$smarty->assign("billers"    , $billers);
$smarty->assign("customers"  , $customers);
$smarty->assign("taxes"      , Taxes::getAll());
$smarty->assign("products"   , $products);
$smarty->assign("preferences", Preferences::getAll());

$smarty->assign('pageActive' , 'dashboard');
$smarty->assign('active_tab' , '#home');
// @formatter:on
