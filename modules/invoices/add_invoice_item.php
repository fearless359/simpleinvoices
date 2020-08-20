<?php

use Inc\Claz\Invoice;
use Inc\Claz\Product;

/*
 * Script: add_invoice_item.php
 *     add new invoice item in edit page
 *
 * License:
 *     GPL v3 or above
 *
 * Website:
 *     https://simpleinvoices.group
 */
// Added by RCR 20181014 to see if this is ever used.
/**
 * @deprecated 20181106 by Richard Rowley
 */
global $smarty;

if(isset($_POST['submit'])) {
    $id = $_POST['id'];
    Invoice::insertInvoiceItem($id, $_POST['quantity1'], $_POST['product1'],
                               $_POST['tax_id'], trim($_POST['description']), $_POST['unit_price1']);
    Invoice::updateAging($id);
} else {
    $products = Product::getAll(true);
    $smarty -> assign("products",$products);
}

$type = $_GET['type'];
$smarty->assign("type",$type);

$smarty->assign('pageActive', 'invoice');
$smarty->assign('active_tab', '#money');
