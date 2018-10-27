<?php
/*
 * Script: invoice.php
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *   2016-07-05
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *   https://simpleinvoices.group */
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$billers           = Biller::get_all(true);
$customers         = Customer::get_all(true);
$taxes             = Taxes::getActiveTaxes();
$defaultTax        = Taxes::getDefaultTax();
$products          = Product::select_all();
$preferences       = Preferences::getActivePreferences();
$defaultPreference = Preferences::getDefaultPreference();
$defaultCustomer   = Customer::getDefaultCustomer();
$defaults          = $smarty->getTemplateVars('defaults');
$matrix            = ProductAttributes::getMatrix();

if (empty($billers) || empty($customers) || empty($products)) {
    $first_run_wizard = true;
    $smarty->assign("first_run_wizard", $first_run_wizard);
}

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
    $customFields[$i] = CustomFields::show_custom_field("invoice_cf$i"  , '',
                                                             "write"         , '',
                                                             "details_screen", '',
                                                             ''              , '');
}

if (isset($_GET['template'])) {
    $invoice = Invoice::getInvoiceByIndexId($_GET['template']);
    $invoiceItems = Invoice::getInvoiceItems ( $invoice['id'] );

    $smarty->assign("template", $_GET['template']);
    $smarty->assign("defaultCustomerID", $_GET['customer_id']);
    $smarty->assign('defaultInvoice', $invoice);
    $smarty->assign('defaultInvoiceItems', $invoiceItems);

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

$smarty->assign('active_tab', '#money');
