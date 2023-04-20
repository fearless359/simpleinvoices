<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
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
global $config, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$role = $_SESSION['role_name'];

$billers = Biller::getAll(true);
$billerCount = Biller::count();

$customers = Customer::getAll(['enabledOnly' => true]);
$customerCount = Customer::count();

$products = Product::getAll(true);

$smarty->assign("first_run_wizard", $billerCount == 0 || $customerCount == 0 || empty($products));

$taxes = Taxes::getActiveTaxes();
$defaultTax = Taxes::getDefaultTax();
$preferences = Preferences::getActivePreferences();
$defaultPreference = Preferences::getDefaultPreference();
$defaultCustomer = Customer::getDefaultCustomer();
$defaults = $smarty->getTemplateVars('defaults');
$matrix = ProductAttributes::getMatrix();

$defaults['biller'] = $_GET['biller'] ?? $defaults['biller'];
$defaults['customer'] = $_GET['customer'] ?? $defaults['customer'];
$defaults['preference'] = $_GET['preference'] ?? $defaults['preference'];
if (!empty($_GET['line_items'])) {
    $dynamicLineItems = $_GET['line_items'];
} else {
    $dynamicLineItems = $defaults['line_items'];
}

try {
    $customFields = [];
    for ($idx = 1; $idx <= 4; $idx++) {
        // Note that this is a 1 based array not a 0 based array.
        $customFields[$idx] = CustomFields::showCustomField("invoice_cf$idx", '', "write");
    }
    $smarty->assign("customFields", $customFields);
} catch (PdoDbException $pde) {
    error_log("modules/invoices/invoice.php Unexpected error: {$pde->getMessage()}");
    exit("Unable to process request. See error log for details.");
}

// Set default for globalization
$globalInfo = [
    'locale' => $config['localLocale'],
    'currency_code' => $config['localCurrencyCode'],
    'precision' => $config['localPrecision']
];

// Check to see if this is a default_invoice (aka from a template).
if (isset($_GET['template'])) {
    try {
        $invoice = Invoice::getInvoiceByIndexId($_GET['template']);

        // Set globalization for template invoice
        $globalInfo['locale'] = $invoice['locale'];
        $globalInfo['currency_code'] = $invoice['currency_code'];
        $globalInfo['precision'] = $invoice['precision'];

        $invoiceItems = Invoice::getInvoiceItems($invoice['id']);
        $numInvItems = count($invoiceItems);

        $smarty->assign("template", $_GET['template']);
        $smarty->assign("defaultCustomerID", $_GET['customer_id']);
        $smarty->assign('defaultInvoice', $invoice);
        $smarty->assign('defaultInvoiceItems', $invoiceItems);
        $dynamicLineItems = max($numInvItems, $dynamicLineItems);
    } catch (PdoDbException $pde) {
        error_log("modules/invoices/invoice.php Unexpected error: {$pde->getMessage()}");
        exit("Unable to process request. See error log for details.");
    }
} else {
    $smarty->assign("defaultCustomerID", empty($defaultCustomer) ? 0 : $defaultCustomer['id']);
    
    // Set globalization for default preference
    $globalInfo['locale'] = $defaultPreference['locale'];
    $globalInfo['currency_code'] = $defaultPreference['currency_code'];
    $globalInfo['precision'] = $defaultPreference['precision'];
}

$smarty->assign("matrix", $matrix);
$smarty->assign("billers", $billers);
$smarty->assign("customers", $customers);
$smarty->assign("taxes", $taxes);
$smarty->assign("defaultTax", $defaultTax);
$smarty->assign("products", $products);
$smarty->assign("preferences", $preferences);
$smarty->assign("defaultPreference", $defaultPreference);
$smarty->assign("dynamic_line_items", $dynamicLineItems);
$smarty->assign("defaults", $defaults);
$smarty->assign('globalInfo', $globalInfo);
