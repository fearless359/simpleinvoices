<?php

use Inc\Claz\Invoice;
use Inc\Claz\Payment;
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
global $pdoDb, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

// @formatter:off
$id           = $_GET['id'];
$invoice      = Invoice::getInvoice($id);
$preference   = Preferences::getPreference($invoice['preference_id']);
$defaults     = SystemDefaults::loadValues();
$invoicePaid  = Payment::calc_invoice_paid($id);
$invoiceItems = Invoice::getInvoiceItems($id);

$smarty->assign("invoice"     , $invoice);
$smarty->assign("preference"  , $preference);
$smarty->assign("defaults"    , $defaults);
$smarty->assign("invoicePaid" , $invoicePaid);
$smarty->assign("invoiceItems", $invoiceItems);
// @formatter:on

// If delete is disabled - dont allow people to view this page
if ($defaults['delete'] == 'N') {
    die('Invoice deletion has been disabled, you are not supposed to be here');
}

if (($_GET['stage'] == 2) && ($_POST['doDelete'] == 'y')) {
    $invoice_line_items = Invoice::getInvoiceItems($id);

    $pdoDb->begin(); // Start transaction
    $error = false;

    foreach($invoice_line_items as $key => $value) {
        Invoice::delete('invoice_item_tax', 'invoice_item_id', $invoice_line_items[$key]['id']);
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
        $display_block = "<div class='si_message_error'>{$LANG['delete_failed']}</div>";
    } else {
        $display_block = "<div class='si_message_ok'>{$LANG['delete_success']}</div>";
        $pdoDb->commit();
    }
    // TODO - what about the stuff in the products table for the total style invoices?
    $refresh_redirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=invoices&amp;view=manage' />";
    $smarty->assign('refresh_redirect', $refresh_redirect);
    $smarty->assign('display_block', $display_block);
}

$smarty->assign('pageActive', 'invoice');
$smarty->assign('active_tab', '#money');
