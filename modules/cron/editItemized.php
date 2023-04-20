<?php

use Inc\Claz\Cron;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\Preferences;
use Inc\Claz\Product;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

/*
 *  Script: editItemized.php
 *      cron invoice items details page
 *
 *  Author:
 *      Richard Rowley.
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
$cronId = $_GET ['cronId'];

try {
    $cron = Cron::getOne($cronId);
    $smarty->assign('cron', $cron);

    $invoice = Invoice::getOne($cron['invoice_id']);
    $smarty->assign('invoice', $invoice);

    $smarty->assign('preference', Preferences::getOne($invoice['preference_id']));

    $cronInvoiceItems = Cron::getCronInvoiceItems($cron['id'], $invoice['domain_id']);

    $defaults = SystemDefaults::loadValues();
    if (!empty($_GET['line_items'])) {
        $dynamicLineItems = $_GET['line_items'];
    } else {
        $dynamicLineItems = $defaults['line_items'];
    }

    $smarty->assign("cronInvoiceItems", $cronInvoiceItems);
    $smarty->assign("cronInvoiceItemCount", count($cronInvoiceItems));
    $smarty->assign("dynamic_line_items", $dynamicLineItems);
    $smarty->assign("defaults", $defaults);
    $smarty->assign("preferences", Preferences::getActivePreferences());
    $smarty->assign("products", Product::getAll(true));
    $smarty->assign("taxes", Taxes::getAll());
} catch (PdoDbException $pde) {
    error_log("modules/cron/editItemized.php: error " . $pde->getMessage());
    exit("Unable to process request. See error log for details.");
}

$smarty->assign('pageActive', 'cron');
$smarty->assign('subPageActive', 'cronInvoiceItems');
$smarty->assign('activeTab', '#money');
