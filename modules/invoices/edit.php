<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\Preferences;
use Inc\Claz\Product;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

/*
 *  Script: details.php
 *      invoice details page
 *
 *  Author:
 *      Marcel van Dorp.
 *
 *  Last modified
 *      2018-10-20 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $pdoDb, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// See if we are generating this from a default_invoice template
$defaultTemplateSet = !empty($_GET['template']);
$masterInvoiceId = $defaultTemplateSet ? $_GET ['template'] : $_GET ['id'];

try {
    $invoice = Invoice::getOne($masterInvoiceId);
    if ($defaultTemplateSet) {
        $invoice['id'] = null;
    }

    $invoiceItems = Invoice::getInvoiceItems($masterInvoiceId);

    $customFields = [];
    for ($idx = 1; $idx <= 4; $idx++) {
        $customFields[$idx] = CustomFields::showCustomField("invoice_cf$idx", $invoice["custom_field$idx"], "write");
    }
    // @formatter:off
    $smarty->assign ("invoice"     , $invoice);
    $smarty->assign ("invoiceItems", $invoiceItems);
    $smarty->assign ("lines"       , count ($invoiceItems));
    $smarty->assign ("customFields", $customFields);

    $smarty->assign ("billers"     , Biller::getAll(true));
    $smarty->assign ("customers"   , Customer::getAll(['enabledOnly' => true, 'includeCustId' => "{$invoice['customer_id']}"]));
    $smarty->assign ("defaults"    , SystemDefaults::loadValues());
    $smarty->assign ("preference"  , Preferences::getOne($invoice['preference_id']));
    $smarty->assign ("preferences" , Preferences::getActivePreferences());
    $smarty->assign ("products"    , Product::getAll(true));
    $smarty->assign ("taxes"       , Taxes::getAll());
    // @formatter:on
} catch (PdoDbException $pde) {
    error_log("modules/invoices/details.php: error " . $pde->getMessage());
    exit("Unable to process request. See error log for details.");
}

$smarty->assign('pageActive', 'invoice');
$smarty->assign('subPageActive', 'invoiceEdit');
$smarty->assign('activeTab', '#money');

