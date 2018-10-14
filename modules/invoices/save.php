<?php

/*
 * Script: save.php
 *     Invoice save file
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *   https://simpleinvoices.group
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
global $smarty, $pdoDb;

checkLogin();

$smarty -> assign('pageActive', 'invoice_new');
$smarty -> assign('active_tab', '#money');

$display_block = "<div class=\"si_message_error\">{$LANG['save_invoice_failure']}</div>";

// Deal with op and add some basic sanity checking
if(!isset( $_POST['type']) && !isset($_POST['action'])) {
    exit("no save action");
}

$saved = false;
$type = $_POST['type'];
if ($_POST['action'] == "insert" ) {
    $list = array('biller_id'     => $_POST['biller_id'],
                  'customer_id'   => $_POST['customer_id'],
                  'type_id'       => $type,
                  'preference_id' => $_POST['preference_id'],
                  'date'          => $_POST['date'],
                  'note'          => trim($_POST['note']),
                  'custom_field1' => $_POST['custom_field1'],
                  'custom_field2' => $_POST['custom_field2'],
                  'custom_field3' => $_POST['custom_field3'],
                  'custom_field4' => $_POST['custom_field4']);
    $id = Invoice::insert($list);
    if ($id > 0) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_invoice_success']}</div>";
        $refresh_total = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=quick_view&amp;id=" . urlencode($id) . "\" />";
        if ($type == TOTAL_INVOICE) {
            $product_id = Product::insertProduct(DISABLED, DISABLED);
            if ($product_id > 0) {
                $tax_id = (empty($_POST["tax_id"][0]) ? "" : $_POST["tax_id"][0]);
                Invoice::insertInvoiceItem($id, 1, $product_id, $tax_id, $_POST['description'], $_POST['unit_price']);
            } else {
                error_log("modules/invoices/save.php TOTAL_INVOICE: Unable to save description in si_products table");
                $saved = false;
            }
        } else { // itemized invoice
            $i = 0;
            while ($i <= $_POST['max_items']) {
                if (!empty($_POST["quantity$i"])) {
                    // @formatter:off
                    $tax_id = (empty($_POST["tax_id"][$i]) ? "" : $_POST["tax_id"][$i]);
                    $attr = (empty($_POST["attribute"][$i]) ? "" : $_POST["attribute"][$i]);
                    Invoice::insertInvoiceItem($id, $_POST["quantity$i"], $_POST["products$i"],
                        $tax_id, $_POST["description$i"], $_POST["unit_price$i"], $attr);
                    // @formatter:on
                }
                $i++;
            }
        }

        // Have to set the value after invoice items have been posted.
        Invoice::updateAging($id);
    }
} else if ( $_POST['action'] == "edit") {
    $id = $_POST['id'];
    $refresh_total = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=quick_view&amp;id=" . urlencode($_POST['id']) . "\" />";
    if (Invoice::updateInvoice($id)) {
        if ($type == TOTAL_INVOICE) {
            $pdoDb->setFauxPost(array("unit_price" => $_POST['unit_price'], "description" => $_POST['description0']));
            $pdoDb->addSimpleWhere("id", $_POST['products0'], "AND");
            $pdoDb->addSimpleWhere("domain_id", domain_id::get());
            if (!$pdoDb->request("UPDATE", "products")) {
                error_log("modules/invoices/save.php - Unable to update product 0 - _POST - " . print_r($_POST, true));
            }
        }

        $i = 0;
        while ($i <= $_POST['max_items']) {
            if ($_POST["delete$i"] == "yes") {
                delete('invoice_items', 'id', $_POST["line_item$i"]);
            } else {
                if ($_POST["quantity$i"] != null) {
                    //new line item added in edit page
                    $item = (isset($_POST["line_item$i"]) ? $_POST["line_item$i"] : "");
                    $qty = (isset($_POST["quantity$i"]) ? siLocal::dbStd($_POST["quantity$i"]) : "");
                    $product = (isset($_POST["products$i"]) ? $_POST["products$i"] : "");
                    $desc = (isset($_POST["description$i"]) ? $_POST["description$i"] : "");
                    $price = (isset($_POST["unit_price$i"]) ? siLocal::dbStd($_POST["unit_price$i"]) : "");
                    $attr = (isset($_POST["attribute$i"]) ? $_POST["attribute$i"] : "");
                    $tax_ids = (isset($_POST["tax_id"][$i]) ? $_POST["tax_id"][$i] : "");

                    if (empty($item)) {
                        Invoice::insertInvoiceItem($id, $qty, $product, $tax_ids, $desc, $price, $attr);
                    } else {
                        Invoice::updateInvoiceItem($item, $qty, $product, $tax_ids, $desc, $price, $attr);
                    }
                }
            }
            $i++;
        }

        // Have to update values after the invoice items are updated.
        Invoice::updateAging($id);

        $display_block = "<div class=\"si_message_ok\">{$LANG['save_invoice_success']}</div>";
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_total', $refresh_total);
$smarty->assign('id', $id);
