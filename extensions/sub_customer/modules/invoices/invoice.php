<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\Preferences;
use Inc\Claz\Product;
use Inc\Claz\ProductAttributes;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

/*
 * Script: invoice.php
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *   2016-07-27
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *   https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// @formatter:off
$billers     = Biller::getAll(true);
$customers   = Customer::getAll(true);
$taxes       = Taxes::getActiveTaxes();
$products    = Product::getAll(true);
$preferences = Preferences::getActivePreferences();
$defaults    = SystemDefaults::loadValues();
$matrix      = ProductAttributes::getMatrix();

$first_run_wizard = false;
if (empty($billers) || empty($customers) || empty($products)) {
    $first_run_wizard =true;
}
$smarty->assign("first_run_wizard",$first_run_wizard);

$defaults['biller']     = (isset($_GET['biller']))     ? $_GET['biller']     : $defaults['biller'];
$defaults['customer']   = (isset($_GET['customer']))   ? $_GET['customer']   : $defaults['customer'];
$defaults['preference'] = (isset($_GET['preference'])) ? $_GET['preference'] : $defaults['preference'];

$defaultTax        = Taxes::getDefaultTax();
$defaultPreference = Preferences::getDefaultPreference();
$defaultCustomer   = Customer::getDefaultCustomer();
$sub_customers     = SubCustomers::getSubCustomers($defaults['customer']);
if (!empty( $_GET['line_items'] )) {
    $dynamic_line_items = $_GET['line_items'];
} else {
    $dynamic_line_items = $defaults['line_items'] ;
}

$customFields = array();
for($i=1;$i<=4;$i++) {
    // Note that this is a 1's based array and not a 0's based array.
    $customFields[$i] = CustomFields::showCustomField("invoice_cf$i"  , '', "write", '',
                                                             "details_screen", '', ''     , '');
}

$smarty->assign('matrix'            , $matrix);
$smarty->assign('billers'           , $billers);
$smarty->assign('customers'         , $customers);
$smarty->assign('sub_customers'     , $sub_customers);
$smarty->assign('taxes'             , $taxes);
$smarty->assign('defaultTax'        , $defaultTax);
$smarty->assign('products'          , $products);
$smarty->assign('preferences'       , $preferences);
$smarty->assign('defaultPreference' , $defaultPreference);
$smarty->assign('dynamic_line_items', $dynamic_line_items);
$smarty->assign('customFields'      , $customFields);
$smarty->assign('defaultCustomerID' , $defaultCustomer['id']);
$smarty->assign('defaults'          , $defaults);
$smarty->assign('active_tab'        , '#money');
// @formatter:on
