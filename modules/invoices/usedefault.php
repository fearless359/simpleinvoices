<?php
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
 *      https://simpleinvoices.group */
global $defaults, $pdoDb, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin ();

$master_customer_id = $_GET ['customer_id'];
try {
    $customer = Customer::get($master_customer_id);
} catch (PdoDbException $pde) {
    error_log("modules/invoices/usedefault.php error: " . $pde->getMessage());
    exit("Database access error(1). See error log.");
}

if ($_GET ['action'] == 'update_template') {
    // Update the default template for this customer
    try {
        $pdoDb->setFauxPost(array("default_invoice" => $_GET ['id']));
        $pdoDb->addSimpleWhere("id", $master_customer_id, 'AND');
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $pdoDb->request("UPDATE", "customers");
    } catch (PdoDbException $pde) {
        error_log("modules/invoices/usedefault.php error: " . $pde->getMessage());
        exit("Database access error(2). See error log.");
    }
    $smarty->assign("view", "quick_view");
    $smarty->assign("spec", "id");
    $smarty->assign("id", $_GET['id']);
} else {
    // Set the template to use. If there is a customer specified invoice,
    // use it. Otherwise, use the application default invoice.
    if (empty($customer['default_invoice'])) {
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
        $smarty->assign("view", "itemised");
        $smarty->assign("spec", "customer_id");
        $smarty->assign("id"  , $master_customer_id);
    } else {
        // NOTE: The combination of view/spec being details/template invokes logic
        // in the main index.php file that resolves direction to the correct screen.
        $smarty->assign("view", "itemised");
        $smarty->assign("spec", "template");
        $smarty->assign("id"  , $invoice ['index_id']);
        $smarty->assign('spec2', "customer_id");
        $smarty->assign("CID", $master_customer_id);
    }
}
