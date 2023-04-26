<?php

use Inc\Claz\DomainId;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\Product;
use Inc\Claz\Util;

/*
 * Script: save.php
 *     Invoice save file
 *
 * Last edited:
 *   2021-06-17 by Rich Rowley
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *   https://simpleinvoices.group
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
global $config, $LANG, $smarty, $pdoDb;

Util::directAccessAllowed();

$displayBlock = "<div class=\"si_message_error\">{$LANG['saveInvoiceFailure']}</div>";
$refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";

$op = $_POST['op'];
$type = $_POST['type'];

$locale = $_POST['locale'] ?? $config['localLocale'];
$currencyCode = $_POST['currency_code'] ?? $config['localCurrencyCode'];
$precision = $_POST['precision'] ?? $config['localPrecision'];

$id = null;
if ($op == "create" ) {
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

        $displayBlock = "<div class=\"si_message_ok\">{$LANG['saveInvoiceSuccess']}</div>";
        $refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=quickView&amp;id=" . urlencode($id) . "\" />";
        if ($type == TOTAL_INVOICE) {
            $productId = Product::insertProduct(DISABLED, DISABLED);
            if ($productId > 0) {
                $unitPrice = Util::dbStd($_POST["unit_price"], $locale);
                $taxIds = [];
                if (!empty($_POST['tax_id'])) {
                    foreach ($_POST['tax_id'] as $taxId) {
                        if (!empty($taxId)) {
                            $taxIds[] = $taxId;
                        }
                    }
                }
                Invoice::insertInvoiceItem($id, 1, $productId, $taxIds, $_POST['description'], $unitPrice, null);
            } else {
                error_log("modules/invoices/save.php TOTAL_INVOICE: Unable to save description in si_products table");
            }
        } else { // itemized invoice
            $idx = 0;
            while ($idx <= $_POST['max_items']) {
                if (!empty($_POST["quantity$idx"])) {
                    // @formatter:off
                    $qty = Util::dbStd($_POST["quantity$idx"], $locale);
                    $unitPrice = Util::dbStd($_POST["unit_price$idx"], $locale);
                    $taxIds = empty($_POST["tax_id"][$idx]) ? [] : $_POST["tax_id"][$idx];
                    $attr = empty($_POST["attribute"][$idx]) ? [] : $_POST["attribute"][$idx];
                    Invoice::insertInvoiceItem($id, $qty, $_POST["products$idx"],
                                               $taxIds, $_POST["description$idx"], $unitPrice, $attr);
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

} elseif ( $op == "edit") {
    $id = $_POST['id'];
    $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=invoices&amp;view=quickView&amp;id=$id' />";
    try {
        if (Invoice::updateInvoice($id)) {
            if ($type == TOTAL_INVOICE) {
                $unitPrice = empty($_POST['unit_price']) ? 0 : Util::dbStd($_POST['unit_price'], $locale);
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
                if (isset($_POST["delete$idx"]) && $_POST["delete$idx"] == "yes") {
                    $lineItemIdx = $_POST["line_item$idx"];
                    foreach ($_POST["tax_id"][$idx] as $key => $value) {
                        if (!empty($value)) {
                            Invoice::delete('invoice_item_tax', 'invoice_item_id',$lineItemIdx);
                            break;
                        }
                    }

                    Invoice::delete('invoice_items', 'id', $lineItemIdx);
                } elseif (isset($_POST["quantity$idx"])) {

                    //new line item added in edit page
                    $item = $_POST["line_item$idx"] ?? "";
                    $qty = Util::dbStd($_POST["quantity$idx"], $locale);
                    $product = $_POST["products$idx"] ?? "";
                    $desc = $_POST["description$idx"] ?? "";
                    $price = isset($_POST["unit_price$idx"]) ? Util::dbStd($_POST["unit_price$idx"], $locale) : "";
                    $attr = $_POST["attribute$idx"] ?? null;
                    $taxIds = $_POST["tax_id"][$idx] ?? [];

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

            $displayBlock = "<div class=\"si_message_ok\">{$LANG['saveInvoiceSuccess']}</div>";
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
    $str = "modules/invoices/save.php - Invalid \$_POST['op'] setting of [{$_POST['op']}]. Expected 'create' or 'edit'";
    error_log($str);
    exit($str);
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);
$smarty->assign('id', $id);

$smarty->assign('pageActive', $pageActive);
$smarty->assign('activeTab', '#money');
