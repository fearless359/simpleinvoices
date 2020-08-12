<?php

use Inc\Claz\DomainId;
use Inc\Claz\Invoice;
use Inc\Claz\Product;
use Inc\Claz\SiLocal;
use Inc\Claz\Util;

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
global $LANG, $smarty, $pdoDb;

Util::directAccessAllowed();

$smarty -> assign('pageActive', 'invoice_new');
$smarty -> assign('active_tab', '#money');

$displayBlock = "<div class=\"si_message_error\">{$LANG['save_invoice_failure']}</div>";
$refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";


// Deal with op and add some basic sanity checking
if(!isset( $_POST['type']) && !isset($_POST['action'])) {
    exit("no save action");
}

$type = $_POST['type'];
$id = null;
if ($_POST['action'] == "insert" ) {
    $list = [
        'biller_id'     => $_POST['biller_id'],
        'customer_id'   => $_POST['customer_id'],
        'type_id'       => $type,
        'preference_id' => $_POST['preference_id'],
        'date'          => $_POST['date'],
        'note'          => empty($_POST['note']         ) ? "" : trim($_POST['note']),
        'custom_field1' => empty($_POST['custom_field1']) ? "" : $_POST['custom_field1'],
        'custom_field2' => empty($_POST['custom_field2']) ? "" : $_POST['custom_field2'],
        'custom_field3' => empty($_POST['custom_field3']) ? "" : $_POST['custom_field3'],
        'custom_field4' => empty($_POST['custom_field4']) ? "" : $_POST['custom_field4'],
        'sales_representative' => $_POST['sales_representative']
    ];
    try {
        $id = Invoice::insert($list);
        if ($id > 0) {
            $displayBlock = "<div class=\"si_message_ok\">{$LANG['save_invoice_success']}</div>";
            $refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=quick_view&amp;id=" . urlencode($id) . "\" />";
            if ($type == TOTAL_INVOICE) {
                $productId = Product::insertProduct(DISABLED, DISABLED);
                if ($productId > 0) {
                    $unitPrice = SiLocal::dbStd($_POST["unit_price"]);
                    $taxIds = empty($_POST["tax_id"][0]) ? "" : $_POST["tax_id"][0];
                    Invoice::insertInvoiceItem($id, 1, $productId, $taxIds, $_POST['description'], $unitPrice);
                } else {
                    error_log("modules/invoices/save.php TOTAL_INVOICE: Unable to save description in si_products table");
                }
            } else { // itemized invoice
                $ndx = 0;
                while ($ndx <= $_POST['max_items']) {
                    if (!empty($_POST["quantity$ndx"])) {
                        // @formatter:off
                        $unitPrice = SiLocal::dbStd($_POST["unit_price$ndx"]);
                        $taxId = empty($_POST["tax_id"][$ndx]) ? "" : $_POST["tax_id"][$ndx];
                        $attr = empty($_POST["attribute"][$ndx]) ? "" : $_POST["attribute"][$ndx];
                        Invoice::insertInvoiceItem($id, $_POST["quantity$ndx"], $_POST["products$ndx"],
                            $taxId, $_POST["description$ndx"], $unitPrice, $attr);
                        // @formatter:on
                    }
                    $ndx++;
                }
            }

            // Have to set the value after invoice items have been posted.
            Invoice::updateAging($id);
        }
    } catch (Exception $exp) {
        error_log("modules/invoices/save.php - insert exception error: " . $exp->getMessage());
    }
} elseif ( $_POST['action'] == "edit") {
    $id = $_POST['id'];
    $refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=quick_view&amp;id=" . urlencode($_POST['id']) . "\" />";
    try {
        if (Invoice::updateInvoice($id)) {
            if ($type == TOTAL_INVOICE) {
                $unitPrice = empty($_POST['unit_price']) ? 0 : SiLocal::dbStd($_POST['unit_price']);
                $pdoDb->setFauxPost([
                    "unit_price" => $unitPrice,
                    "description" => $_POST['description0']
                ]);
                $pdoDb->addSimpleWhere("id", $_POST['products0'], "AND");
                $pdoDb->addSimpleWhere("domain_id", DomainId::get());
                if (!$pdoDb->request("UPDATE", "products")) {
                    error_log("modules/invoices/save.php - Unable to update product 0 - _POST - " . print_r($_POST, true));
                }
            }

            $ndx = 0;
            while ($ndx <= $_POST['max_items']) {
                if (isset($_POST["delete$ndx"]) && $_POST["delete$ndx"] == "yes") {
                    Invoice::delete('invoice_items', 'id', $_POST["line_item$ndx"]);
                } elseif (isset($_POST["quantity$ndx"]) && $_POST["quantity$ndx"] != null) {
                    //new line item added in edit page
                    $item = isset($_POST["line_item$ndx"]) ? $_POST["line_item$ndx"] : "";
                    $qty = isset($_POST["quantity$ndx"]) ? SiLocal::dbStd($_POST["quantity$ndx"]) : "";
                    $product = isset($_POST["products$ndx"]) ? $_POST["products$ndx"] : "";
                    $desc = isset($_POST["description$ndx"]) ? $_POST["description$ndx"] : "";
                    $price = isset($_POST["unit_price$ndx"]) ? SiLocal::dbStd($_POST["unit_price$ndx"]) : "";
                    $attr = isset($_POST["attribute$ndx"]) ? $_POST["attribute$ndx"] : "";
                    $taxIds = isset($_POST["tax_id"][$ndx]) ? $_POST["tax_id"][$ndx] : [];

                    if (empty($item)) {
                        Invoice::insertInvoiceItem($id, $qty, $product, $taxIds, $desc, $price, $attr);
                    } else {
                        Invoice::updateInvoiceItem($item, $qty, $product, $taxIds, $desc, $price, $attr);
                    }
                }
                $ndx++;
            }

            // Have to update values after the invoice items are updated.
            Invoice::updateAging($id);

            $displayBlock = "<div class=\"si_message_ok\">{$LANG['save_invoice_success']}</div>";
        }
    } catch (Exception $exp) {
        error_log("modules/invoices/save.php - edit exception error: " . $exp->getMessage());
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);
$smarty->assign('id', $id);
