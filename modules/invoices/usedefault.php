<?php

use Inc\Claz\Customer;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

/*
 *  Script: usedefault.php
 *      From Customer create default invoice option. This page makes a copy
 *      of the default invoice for the customer, or if none for the customer
 *      then from the si_system_defaults setting, and if no system default
 *      set, then an empty invoice.
 *
 *  Authors:
 *      Marcel van Dorp, Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *      2021-06-17 by Rich Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $databaseBuilt, $pdoDb, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$masterCustomerId = $_GET ['customer_id'];
$customer = Customer::getOne($masterCustomerId);

// NOTE: The customer record default_invoice field contains the index_id for the invoice NOT the id.
if ($_GET ['action'] == 'update_template') {
    // This section is executed when the refresh default_invoice button is selected on the invoice quickView screen.
    // Update the default template for this customer
    try {
        $invoice = Invoice::getInvoiceByIndexId($_GET['index_id']);

        $fauxPostList = ["default_invoice" => $invoice['index_id']];
        if (!Customer::updateCustomer($masterCustomerId, true, $fauxPostList)) {
            exit("Database access error(2). See error log.");
        }

        // Set up to redisplay the quickView for the invoice last accessed
        $smarty->assign("view", "quickView");
        $smarty->assign("attr1", "id");
        $smarty->assign("attr1Val", $invoice['id']);

        $smarty->assign('pageActive', 'invoice');
        $smarty->assign('subPageActive', 'invoiceView');
        $smarty->assign('activeTab', '#money');
    } catch (PdoDbException $pde) {
        exit("modules/invoices/usedefault.php Unexpected error {$pde->getMessage()}");
    }
} else {
    // Set the template to use. If there is a customer specified invoice,
    // use it. Otherwise, use the application default invoice.
    if (empty($customer['default_invoice'])) {
        $defaults = SystemDefaults::loadValues($databaseBuilt);
        if (empty($defaults['default_invoice'])) {
            // No default specified. Use the last invoice generated for this user.
            Customer::getLastInvoiceIds($masterCustomerId, $defaultInvoiceIndexId, $last_id);
        } else {
            // From system_defaults
            $defaultInvoiceIndexId = $defaults['default_invoice'];
        }
    } else {
        // From customers record.
        $defaultInvoiceIndexId = $customer['default_invoice'];
    }

    try {
        $invoice = Invoice::getInvoiceByIndexId($defaultInvoiceIndexId);
        $smarty->assign("view", "itemized");
        if (empty($invoice)) {
            // No default template defined.
            $smarty->assign("attr1"    , "customer_id");
            $smarty->assign("attr1Val" , $masterCustomerId);
        } else {
            // NOTE: The combination of view/spec being details/template invokes logic
            // in the main index.php file that resolves direction to the correct screen.
            // This will result via usedefault.tpl, the input to index.php:
            // .../index.php?module=invoices&amp;view=itemized&amp;template=[invoice::index_id]&amp;customer_id=[customer_id]
            $smarty->assign("attr1"    , "template");
            $smarty->assign("attr1Val" , $invoice ['index_id']);
            $smarty->assign('attr2'    , "customer_id");
            $smarty->assign("attr2Val" , $masterCustomerId);

            $smarty->assign('pageActive', 'invoice_new');
            $smarty->assign('subPageActive', 'invoice_new_itemized');
            $smarty->assign('activeTab', '#money');
        }
    } catch (PdoDbException $pde) {
        exit("modules/invoices/usedefault.php (2)Unexpected error {$pde->getMessage()}");
    }
}

