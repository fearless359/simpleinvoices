<?php

use Inc\Claz\Customer;
use Inc\Claz\DomainId;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

/*
 *  Script: usedefault.php
 *      page which chooses an empty page or another invoice as template
 *
 *  Authors:
 *      Marcel van Dorp, Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2016-08-02
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $databaseBuilt, $pdoDb, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

$master_customer_id = $_GET ['customer_id'];
$customer = Customer::get($master_customer_id);

// NOTE: The customer record default_invoice field contains the index_id for the invoice NOT the id.
if ($_GET ['action'] == 'update_template') {
    // This section is executed when the refresh default_invoice button is selected
    // on the invoice quick_view screen.
    // Update the default template for this customer
    $invoice = Invoice::getInvoiceByIndexId($_GET['index_id']);
    try {
        $pdoDb->setFauxPost(array("default_invoice" => $invoice['index_id']));
        $pdoDb->addSimpleWhere("id", $master_customer_id, 'AND');
        $pdoDb->addSimpleWhere("domain_id", DomainId::get());
        $pdoDb->request("UPDATE", "customers");
    } catch (PdoDbException $pde) {
        error_log("modules/invoices/usedefault.php error: " . $pde->getMessage());
        exit("Database access error(2). See error log.");
    }

    // Set up to redisplay the quick_view for the invoice last accessed
    $smarty->assign("view"     , "quick_view");
    $smarty->assign("attr1"    , "id");
    $smarty->assign("attr1_val", $invoice['id']);
} else {
    // Set the template to use. If there is a customer specified invoice,
    // use it. Otherwise, use the application default invoice.
    if (empty($customer['default_invoice'])) {
        $defaults = SystemDefaults::loadValues($databaseBuilt);
        if (empty($defaults['default_invoice'])) {
            // No default specified. Use the last invoice generated for this user. Sans that,
            // use the last invoice generated.
            $default_invoice_index_id = Customer::getLastInvoiceIndexId($master_customer_id);
        } else {
            // From system_defaults
            $default_invoice_index_id = $defaults['default_invoice'];
        }
    } else {
        // From customers record.
        $default_invoice_index_id = $customer['default_invoice'];
    }

    $invoice = Invoice::getInvoiceByIndexId($default_invoice_index_id);
    if (empty($invoice)) {
        // No default template defined.
        $smarty->assign("view"     , "itemised");
        $smarty->assign("attr1"    , "customer_id");
        $smarty->assign("attr1_val", $master_customer_id);
    } else {
        // NOTE: The combination of view/spec being details/template invokes logic
        // in the main index.php file that resolves direction to the correct screen.
        // This will result via usedefault.tpl, the input to index.php:
        // .../index.php?module=invoices&amp;view=itemised&amp;template=[invoice::index_id]&amp;customer_id=[customer_id]
        $smarty->assign("view"     , "itemised");
        $smarty->assign("attr1"    , "template");
        $smarty->assign("attr1_val", $invoice ['index_id']);
        $smarty->assign('attr2'    , "customer_id");
        $smarty->assign("attr2_val", $master_customer_id);
    }
}
