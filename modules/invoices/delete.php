<?php

use Inc\Claz\Invoice;
use Inc\Claz\Payment;
use Inc\Claz\PdoDbException;
use Inc\Claz\Preferences;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

/*
 *  Script: delete.php
 *      Do the deletion of an invoice page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2016-09-27
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $LANG, $pdoDb, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$id = $_GET['id'];

try {
    // @formatter:off
    $invoice      = Invoice::getOne($id);
    $invoiceItems = Invoice::getInvoiceItems($id);
    $invoicePaid  = Payment::calcInvoicePaid($id);
    $preference   = Preferences::getOne($invoice['preference_id']);
    $defaults     = SystemDefaults::loadValues();

    // If delete is disabled - dont allow people to view this page
    if ($defaults['delete'] == 'N') {
        exit('Invoice deletion has been disabled, you are not supposed to be here');
    }

    $smarty->assign("invoice"     , $invoice);
    $smarty->assign("invoiceItems", $invoiceItems);
    $smarty->assign("invoicePaid" , $invoicePaid);
    $smarty->assign("preference"  , $preference);
    $smarty->assign("defaults"    , $defaults);
    // @formatter:on

    if ($_GET['stage'] == 2 && $_POST['doDelete'] == 'y') {
        $invoiceLineItems = Invoice::getInvoiceItems($id);

        $pdoDb->begin(); // Start transaction
        $error = false;

        foreach ($invoiceLineItems as $key => $value) {
            Invoice::delete('invoice_item_tax', 'invoice_item_id', $invoiceLineItems[$key]['id']);
        }

        // Start by deleting the line items
        if (!$error && !Invoice::delete('invoice_items', 'invoice_id', $id)) {
            $error = true;
        }

        // delete products from products table for total style
        if (!$error && $invoice['type_id'] == TOTAL_INVOICE) {
            if (!Invoice::delete('products', 'id', $invoiceItems['0']['product']['id'])) {
                $error = true;
            }
        }

        // delete the info from the invoice table
        if (!$error && !Invoice::delete('invoices', 'id', $id)) {
            $error = true;
        }
        if ($error) {
            $pdoDb->rollback();
            $displayBlock = "<div class='si_message_error'>{$LANG['deleteFailed']}</div>";
        } else {
            $displayBlock = "<div class='si_message_ok'>{$LANG['deleteSuccess']}</div>";
            $pdoDb->commit();
        }
        // TODO - what about the stuff in the products table for the total style invoices?
        $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=invoices&amp;view=manage' />";
        $smarty->assign('refresh_redirect', $refreshRedirect);
        $smarty->assign('display_block', $displayBlock);
    }
} catch (PdoDbException $pde) {
    exit("modules/invoices/delete.php Unexpected error: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'invoice');
$smarty->assign('activeTab', '#money');
