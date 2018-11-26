<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\Preferences;
use Inc\Claz\Product;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

// @formatter:off
$billers     = Biller::getAll();
$customers   = Customer::get_all();
$taxes       = Taxes::getTaxes();
$products    = Product::select_all();
$preferences = Preferences::getPreferences();

if (empty($billers) || empty($customers) || empty($products)) {
    $first_run_wizard =true;
    $smarty->assign("first_run_wizard",$first_run_wizard);
}

$smarty->assign("billers"    , $billers);
$smarty->assign("customers"  , $customers);
$smarty->assign("taxes"      , $taxes);
$smarty->assign("products"   , $products);
$smarty->assign("preferences", $preferences);
$smarty->assign('pageActive' , 'dashboard');
$smarty->assign('active_tab' , '#home');
// @formatter:on
