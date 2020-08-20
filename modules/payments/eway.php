<?php

use Inc\Claz\Eway;
use Inc\Claz\Invoice;

global $smarty;

$saved = false;

$invoiceAll = Invoice::getAll();

if ($_POST ['op'] == 'add' && ! empty ( $_POST ['invoice_id'] )) {
    $invoice = Invoice::getOne( $_POST ['invoice_id'] );

    $ewayCheck = new Eway();
    $ewayCheck->invoice = $invoice;
    $ewayPreCheck = $ewayCheck->preCheck ();

    if ($ewayPreCheck == 'true') {
        // do eway payment
        $eway = new Eway();
        $eway->invoice = $invoice;
        $saved = $eway->payment ();
    } else {
        $saved = 'check_failed';
    }
}

$smarty->assign ( 'invoice_all', $invoiceAll );
$smarty->assign ( 'saved', $saved );

$smarty->assign ( 'pageActive', 'payment' );
$smarty->assign ( 'subPageActive', 'payment_eway' );
$smarty->assign ( 'active_tab', '#money' );

