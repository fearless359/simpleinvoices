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
$customers   = Customer::getAll(['enabled_only' => true]);
$products    = Product::getAll(true);
$defaults    = SystemDefaults::loadValues();

$firstRunWizard = false;
if (empty($billers) || empty($customers) || empty($products)) {
    $firstRunWizard =true;
}
$smarty->assign("first_run_wizard",$firstRunWizard);

$defaults['biller']     = isset($_GET['biller'])     ? $_GET['biller']     : $defaults['biller'];
$defaults['customer']   = isset($_GET['customer'])   ? $_GET['customer']   : $defaults['customer'];
$defaults['preference'] = isset($_GET['preference']) ? $_GET['preference'] : $defaults['preference'];

$defaultCustomer   = Customer::getDefaultCustomer();
if (!empty( $_GET['line_items'] )) {
    $dynamicLineItems = $_GET['line_items'];
} else {
    $dynamicLineItems = $defaults['line_items'] ;
}

$customFields = [];
for($idx=1;$idx<=4;$idx++) {
    // Note that this is a 1's based array and not a 0's based array.
    $customFields[$idx] = CustomFields::showCustomField("invoice_cf$idx"  , '', "write", '',
                                                             "details_screen", '', ''     , '');
}

$smarty->assign('matrix'            , ProductAttributes::getMatrix());
$smarty->assign('billers'           , $billers);
$smarty->assign('customers'         , $customers);
$smarty->assign('subCustomers'      , SubCustomers::getSubCustomers($defaults['customer']));
$smarty->assign('taxes'             , Taxes::getActiveTaxes());
$smarty->assign('defaultTax'        , Taxes::getDefaultTax());
$smarty->assign('products'          , $products);
$smarty->assign('preferences'       , Preferences::getActivePreferences());
$smarty->assign('defaultPreference' , Preferences::getDefaultPreference());
$smarty->assign('dynamic_line_items', $dynamicLineItems);
$smarty->assign('customFields'      , $customFields);
$smarty->assign('defaultCustomerID' , $defaultCustomer['id']);
$smarty->assign('defaults'          , $defaults);
$smarty->assign('active_tab'        , '#money');
// @formatter:on
