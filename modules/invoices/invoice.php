<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\DynamicJs;
use Inc\Claz\Invoice;
use Inc\Claz\Preferences;
use Inc\Claz\Product;
use Inc\Claz\ProductAttributes;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

/*
 * Script: invoice.php
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *   2018-12-16 by Richard Rowley
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *   https://simpleinvoices.group
 */
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

DynamicJs::begin();
DynamicJs::formValidationBegin("frmpost");
DynamicJs::validateRequired('date', $LANG['date_formatted']);
DynamicJs::valueValidation("biller_id","Biller Name",1,1000000, true);
DynamicJs::valueValidation("customer_id","Customer Name",1,1000000, true);
DynamicJs::validateIfNumZero("i_quantity0","Quantity");
DynamicJs::validateIfNum("i_quantity0","Quantity");
DynamicJs::validateRequired("select_products0","Product");
DynamicJs::valueValidation("select_tax","Tax Rate",1,100, false);
DynamicJs::lengthValidation("select_preferences","Invoice Preference",1,1000000);
DynamicJs::formValidationEnd();
DynamicJs::end();

// @formatter:off
$billers           = Biller::getAll(true);
$customers         = Customer::getAll(true);
$taxes             = Taxes::getActiveTaxes();
$defaultTax        = Taxes::getDefaultTax();
$products          = Product::getAll(true);
$preferences       = Preferences::getActivePreferences();
$defaultPreference = Preferences::getDefaultPreference();
$defaultCustomer   = Customer::getDefaultCustomer();
$defaults          = $smarty->getTemplateVars('defaults');
$matrix            = ProductAttributes::getMatrix();


$first_run_wizard = false;
if (empty($billers) || empty($customers) || empty($products)) {
    $first_run_wizard =true;
}
$smarty->assign("first_run_wizard",$first_run_wizard);

$defaults['biller']     = (isset($_GET['biller'])    ) ? $_GET['biller']     : $defaults['biller'];
$defaults['customer']   = (isset($_GET['customer'])  ) ? $_GET['customer']   : $defaults['customer'];
$defaults['preference'] = (isset($_GET['preference'])) ? $_GET['preference'] : $defaults['preference'];
if (!empty($_GET['line_items'])) {
    $dynamic_line_items = $_GET['line_items'];
} else {
    $dynamic_line_items = $defaults['line_items'];
}

$customFields = array();
for ($i = 1; $i <= 4; $i++) {
    // Note that this is a 1 based array not a 0 based array.
    $customFields[$i] = CustomFields::showCustomField("invoice_cf$i"  , '',
                                                             "write"         , '',
                                                             "details_screen", '',
                                                             ''              , '');
}

// Check to see if this is a default_invoice (aka from a template).
if (isset($_GET['template'])) {
    $invoice = Invoice::getInvoiceByIndexId($_GET['template']);
    $invoiceItems = Invoice::getInvoiceItems ( $invoice['id'] );
    $num_inv_items = count($invoiceItems);

    $smarty->assign("template", $_GET['template']);
    $smarty->assign("defaultCustomerID", $_GET['customer_id']);
    $smarty->assign('defaultInvoice', $invoice);
    $smarty->assign('defaultInvoiceItems', $invoiceItems);
    $dynamic_line_items = ($num_inv_items > $dynamic_line_items ? $num_inv_items : $dynamic_line_items);

} else {
    $smarty->assign("defaultCustomerID" , (empty($defaultCustomer) ? 0 : $defaultCustomer['id']));
}
$smarty->assign("matrix"            , $matrix);
$smarty->assign("billers"           , $billers);
$smarty->assign("customers"         , $customers);
$smarty->assign("taxes"             , $taxes);
$smarty->assign("defaultTax"        , $defaultTax);
$smarty->assign("products"          , $products);
$smarty->assign("preferences"       , $preferences);
$smarty->assign("defaultPreference" , $defaultPreference);
$smarty->assign("dynamic_line_items", $dynamic_line_items);
$smarty->assign("customFields"      , $customFields);
$smarty->assign("defaults"          , $defaults);
