<?php

use Inc\Claz\DomainId;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
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
        if ($id == 0) {
            $str = "modules/invoices/save.php - Unable to insert new Invoice.";
            error_log($str);
            exit($str);
        }

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
            $idx = 0;
            while ($idx <= $_POST['max_items']) {
                if (!empty($_POST["quantity{$idx}"])) {
                    // @formatter:off
                    $unitPrice = SiLocal::dbStd($_POST["unit_price{$idx}"]);
                    $taxId = empty($_POST["tax_id"][$idx]) ? "" : $_POST["tax_id"][$idx];
                    $attr = empty($_POST["attribute"][$idx]) ? "" : $_POST["attribute"][$idx];
                    Invoice::insertInvoiceItem($id, $_POST["quantity{$idx}"], $_POST["products{$idx}"],
                                               $taxId, $_POST["description{$idx}"], $unitPrice, $attr);
                    // @formatter:on
                }
                $idx++;
            }
        }

        // Have to set the value after invoice items have been posted.
        Invoice::updateAging($id);
    } catch (PdoDbException $pde) {
        error_log("modules/invoices/save.php - insert exception error: " . $pde->getMessage());
        exit("Unable to process request. See error log for details.");
    }

    $pageActive = 'invoice_new';

} elseif ( $_POST['action'] == "edit") {
var_dump("In edit");
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

            $idx = 0;
            while ($idx <= $_POST['max_items']) {
                if (isset($_POST["delete{$idx}"]) && $_POST["delete{$idx}"] == "yes") {
                    Invoice::delete('invoice_items', 'id', $_POST["line_item{$idx}"]);
                } elseif (isset($_POST["quantity{$idx}"])) {
                    //new line item added in edit page
                    $item = isset($_POST["line_item{$idx}"]) ? $_POST["line_item{$idx}"] : "";
                    $qty = isset($_POST["quantity{$idx}"]) ? SiLocal::dbStd($_POST["quantity{$idx}"]) : "";
                    $product = isset($_POST["products{$idx}"]) ? $_POST["products{$idx}"] : "";
                    $desc = isset($_POST["description{$idx}"]) ? $_POST["description{$idx}"] : "";
                    $price = isset($_POST["unit_price{$idx}"]) ? SiLocal::dbStd($_POST["unit_price{$idx}"]) : "";
                    $attr = isset($_POST["attribute{$idx}"]) ? $_POST["attribute{$idx}"] : "";
                    $taxIds = isset($_POST["tax_id"][$idx]) ? $_POST["tax_id"][$idx] : [];

                    if (empty($item)) {
                        Invoice::insertInvoiceItem($id, $qty, $product, $taxIds, $desc, $price, $attr);
                    } else {
                        Invoice::updateInvoiceItem($item, $qty, $product, $taxIds, $desc, $price, $attr);
                    }
                }
                $idx++;
            }

            // Have to update values after the invoice items are updated.
            Invoice::updateAging($id);

            $displayBlock = "<div class=\"si_message_ok\">{$LANG['save_invoice_success']}</div>";
            $pageActive = 'invoice';
        } else {
            $str = "modules/invoices/save.php - Unable to update existing Invoice.";
            error_log($str);
            exit($str);
        }
    } catch (PdoDbException $pde) {
        $str = "modules/invoices/save.php - edit exception error: " . $pde->getMessage();
        error_log($str);
        exit($str);
    }
} else {
    $str = "modules/invoices/save.php - Invalid \$_POST action[{$_POST['action']}]. Expected 'insert' or 'edit'";
    error_log($str);
    exit($str);
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);
$smarty->assign('id', $id);

$smarty -> assign('pageActive', $pageActive);
$smarty -> assign('active_tab', '#money');
