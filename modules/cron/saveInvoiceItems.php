<?php

use Inc\Claz\Cron;
use Inc\Claz\PdoDbException;
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
global $LANG, $smarty, $pdoDb;

Util::directAccessAllowed();

$cronId = $_POST['cron_id'];
$op = $_POST['op'] ?? "";

$displayBlock = "<div class=\"si_message_error\">{$LANG['saveCronItemsFailure']}</div>";
$refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=cron&amp;view=editItemized&amp;id=$cronId' />";
if ($op == 'edit') {
    $type = $_POST['type'];
    $locale = $_POST['locale'];
    try {
        $idx = 0;
        while ($idx <= $_POST['max_items']) {
            $id = $_POST["line_item$idx"] ?? 0;
            $qty = isset($_POST["quantity$idx"]) ? Util::dbStd($_POST["quantity$idx"]) : 0;
            if (isset($_POST["delete$idx"]) && $_POST["delete$idx"] == "yes" ||
                $id != 0 && $qty == 0) {
                Cron::deleteCronInvoiceItem($id);
            } elseif (isset($_POST["quantity$idx"])) {

                //new line item added in edit page
                $item = $_POST["line_item$idx"] ?? 0;
                $product = $_POST["products$idx"] ?? 0;
                $desc = $_POST["description$idx"] ?? "";
                if ($desc == "Description") {
                    $desc = "";
                }
                $unitPrice = isset($_POST["unit_price$idx"]) ? Util::dbStd($_POST["unit_price$idx"], $locale) : "";

                $attr = $_POST["attribute$idx"] ?? null;
                $taxIds = $_POST["tax_id"][$idx] ?? [];
                if (empty($item)) {
                    if ($qty != 0) {
                        Cron::insertCronInvoiceItem($cronId, $qty, $product, $taxIds, $desc, $unitPrice, $attr);
                    }
                } else {
                    Cron::updateCronInvoiceItem($id, $qty, $product, $taxIds, $desc, $unitPrice, $attr);
                }
            }
            $idx++;
        }
        $displayBlock = "<div class=\"si_message_ok\">{$LANG['saveCronItemsSuccess']}</div>";
        $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=cron&amp;view=edit&amp;id=$cronId' />";
    } catch (PdoDbException $pde) {
        $str = "modules/cron/saveInvoiceItems.php - edit exception error: " . $pde->getMessage();
        error_log($str);
        exit($str);
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);
$smarty->assign('cron_id', $cronId);

$smarty->assign('pageActive', 'cron');
$smarty->assign('activeTab', '#money');
